<?php

namespace App\Services;

use App\Models\Exam\Grade;
use App\Models\Exam\Exam;
use App\Models\Exam\ExamGroup;
use App\Models\Exam\ExamGroupUser;
use App\Models\Lesson\ValueCategory;
use App\Models\Lesson\DetailValueCategory;
use Ramsey\Uuid\Uuid as Generator;
use Carbon\Carbon;

class CalculateGradeServiceBackup 
{
    function calculateGradeCategory($id) 
    {
        $grade = Grade::find($id);
        $exam = Exam::find($grade->exam_id);
        $gradeAnswers = is_string($grade->answers) ? unserialize($grade->answers) : $grade->answers;

        $totalCorrect = array_filter($gradeAnswers, function ($var) {
            return ($var['is_correct'] === "Y" && !empty($var['answer']));
        });

        $totalAnswer = array_filter($gradeAnswers, function ($var) {
            return (!empty($var['answer']));
        });

        $totalWrong = array_filter($gradeAnswers, function ($var) {
            return ($var['is_correct'] === "N");
        });

        $totalQuestionGrade = count($gradeAnswers);
        $count_correct_answer = count($totalCorrect);

        $grade_exam = 0;
        $gradeIsMinimum = 0;

        if($exam->questionTitle->assessment_type == 1 || $exam->questionTitle->assessment_type == 2) {
            $grade_exam = 0;
        } elseif ($exam->questionTitle->assessment_type == 3) {
            $grade_exam = array_reduce($totalCorrect, function ($carry, $correct) {
                return $carry + $correct['correct_point'];
            }, 0);
        } elseif ($exam->questionTitle->assessment_type == 4) {
            $grade_exam = array_reduce($totalAnswer, function ($carry, $correct) {
                return $carry + ( empty($correct['answer']) ? 0 : $correct['point_' . $correct['answer'][0]]);
            }, 0);
        } else {
            $grade_exam = $count_correct_answer ? round($count_correct_answer / $totalQuestionGrade * 100, 2) : 0;
        }
        
        $valueCategories = ValueCategory::where([
            'lesson_id' => $exam->lesson_id, 
            'value_category_group_id' => $exam->questionTitle->value_category_group_id
        ])->orderBy('created_at', 'ASC')->get();
        
        $valueCategorySumMultiplier = $valueCategories->sum('multiplier');
        $countValueCategory = count($valueCategories) ?? 0;
        
        $resultGradeDetails = [];

        $totalFinalScore = null;

        if ($exam->questionTitle->add_value_category == 1 && ($exam->questionTitle->assessment_type == 1 || $exam->questionTitle->assessment_type == 2)) {
            $grades = [];

            foreach ($valueCategories as $index => $valueCategory) {
                if ($exam->questionTitle->assessment_type == 1) {
                    $totalCorrect = array_filter($gradeAnswers, function ($var) use ($valueCategory) {
                        return ($var['value_category_id'] == $valueCategory->id && $var['is_correct'] == "Y");
                    });

                    $totalWrong = array_filter($gradeAnswers, function ($var) use ($valueCategory) {
                        return ($var['value_category_id'] == $valueCategory->id && $var['is_correct'] == "N");
                    });
                }

                $totalCorrectFinal = count($totalCorrect);
                $totalWrongFinal = count($totalWrong);
                $totalQuestion = (int) $totalCorrectFinal + (int) $totalWrongFinal;

                $grades[] = [
                    'category_id' => $exam->category_id,
                    'lesson_category_id' => $exam->lesson_category_id,
                    'lesson_id' => $exam->lesson_id,
                    'exam_id' => $exam->id,
                    'total_correct' => $totalCorrectFinal,
                    'total_wrong' => $totalWrongFinal,
                    'value_category_id' => $valueCategory->id,
                    'value_category_name' => $valueCategory->name,
                    'multiplier' => (int) $valueCategory->multiplier,
                    'value_category_assessment_type' => $valueCategory->assessment_type,
                ];

                if ($valueCategory->assessment_type == 1) {
                    $grades[$index]['grade'] = round(($totalCorrectFinal == 0 ? 0 : ($totalCorrectFinal / $totalQuestion)) * 100);
                }

                if ($valueCategory->assessment_type == 2) {
                    $grades[$index]['grade'] = $totalCorrectFinal;
                }

                if ($valueCategory->assessment_type == 3) {
                    $grades[$index]['grade'] = $this->slope($grade->total_answer_per_section) * 100;
                }

                if ($valueCategory->assessment_type == 4) {
                    $grades[$index]['grade'] = ($totalCorrectFinal == 0 || count($totalAnswer) == 0 ? 100 : round((count($totalAnswer) - $totalCorrectFinal) / count($totalAnswer) * 100));
                }

                if ($valueCategory->assessment_type == 5) {
                    $grades[$index]['grade'] = count($totalAnswer);
                }
            }

            $valueCategoryIds = array_unique(array_column($grades, 'value_category_id'));
            foreach ($grades as $gradeData) {
                $finalGrade = null;
                $finalCategoryGrade = null;
                $finalConvertionGradeCategory = null;

                $detailValueCategories = DetailValueCategory::where('value_category_id', $gradeData['value_category_id'])
                ->orderBy('value_category_id')
                ->orderBy('final_grade')
                ->get();

                foreach ($detailValueCategories as $index => $detailValueCategory) {
                    if ($gradeData['grade'] >= ($detailValueCategory->min_grade ?? PHP_INT_MIN) && $gradeData['grade'] <= ($detailValueCategory->max_grade ?? PHP_INT_MAX)) {
                        $gradeIsMinimum = $index == 0 || $gradeIsMinimum;
                        $finalGrade = $detailValueCategory->final_grade;
                        $finalCategoryGrade = $detailValueCategory->category_grade;
                        $totalFinalScore += ($finalGrade < 0 ? 0 : $finalGrade);
                        $finalConvertionGradeCategory = $valueCategorySumMultiplier == 0 || $detailValueCategories->count() == 0 ? 0 : (100 / $valueCategorySumMultiplier / ($detailValueCategories->count() - 1)) * $index * $gradeData['multiplier']; 
                        $grade_exam += $finalConvertionGradeCategory;
                        break;
                    }                    
                }

                $resultGradeDetails[] = [
                    'value_category_id' => $gradeData['value_category_id'],
                    'grade_category_name' => $gradeData['value_category_name'],
                    'total_correct' => $gradeData['total_correct'],
                    'total_wrong' => $gradeData['total_wrong'],
                    'grade_category' => $finalCategoryGrade,
                    'final_grade_category' => $finalConvertionGradeCategory,
                    'grade' => $finalGrade,
                ];
            }
        }

        $examGroup = ExamGroup::with([
            'grade' => function ($query) use ($grade) {
                $query->where('user_id', $grade->user_id);
            }
        ])
        ->with(['exam', 'exam.questionTitle'])
        ->withCount('exam')
        ->withCount('grade')
        ->find($exam->exam_group_id);

        $updates = [
            'end_time' => Carbon::now(),
            'total_correct' => $count_correct_answer,
            'grade' => $gradeIsMinimum == 1 ? 30 : $grade_exam,
            'final_score' => $totalFinalScore,
            'grade_calculate' => 1,
            'grade_details' => $resultGradeDetails
        ];

        if($exam->exam_group_id && $examGroup->assessment_type == 2) {
            $updates['percentage_grade'] = $exam->percentage_grade ?? 0;
            $updates['final_grade'] = $grade_exam * ($updates['percentage_grade'] / 100);
        } else {
            $updates['final_grade'] = $grade_exam;
        }

        $grade->update($updates);

        if ($exam->exam_group_id) {
        $gradeCount = Grade::where(['exam_group_id' => $examGroup->id, 'user_id' => $grade->user_id])->count();
            if($gradeCount == $examGroup->exam_count) {
                $gradeExamGroup = 0;
                
                if ($examGroup->assessment_type == 1 || $examGroup->assessment_type == 2) {
                    $gradeExamGroup = Grade::where([
                        'exam_group_id' => $examGroup->id, 
                        'user_id' => $grade->user_id]
                        )->sum('final_grade');
                }
            
                if ($examGroup->assessment_type == 3) {
                    $gradeExamGroup = Grade::where([
                        'exam_group_id' => $examGroup->id, 
                        'user_id' => $grade->user_id
                        ])->avg('final_grade');
                }
            
                if ($examGroup->assessment_type == 4) {
                    $gradeCategories = Grade::where(['exam_group_id' => $examGroup->id, 'user_id' => $grade->user_id])->get();

                    foreach ($gradeCategories as $gradeCategory) {
                        if($gradeCategory->grade_details) {
                            foreach ($gradeCategory->grade_details as $grade_detail) {
                                $gradeExamGroup += $grade_detail['grade'];
                            }
                        }
                    }
                }

                if ($examGroup->smallest_value_limit) {
                    if ($gradeExamGroup >= $examGroup->minimal_grade) {
                        $finalGradeExamGroup = $examGroup->biggest_value_limit && $gradeExamGroup >= $examGroup->biggest_value_limit ? $examGroup->biggest_value_limit : $gradeExamGroup;
                    } else {
                        $finalGradeExamGroup = $examGroup->smallest_value_limit && $gradeExamGroup <= $examGroup->smallest_value_limit ? $examGroup->smallest_value_limit : $gradeExamGroup;
                    }
                } else {
                    switch (true) {
                        case $gradeExamGroup >= -80 && $gradeExamGroup <= 0:
                            $finalGradeExamGroup = 38;
                            break;
                        case $gradeExamGroup >= -240 && $gradeExamGroup < -80:
                            $finalGradeExamGroup = 34;
                            break;
                        case $gradeExamGroup >= -400 && $gradeExamGroup < -240:
                            $finalGradeExamGroup = 32;
                            break;
                        case $gradeExamGroup < -400:
                            $finalGradeExamGroup = 30;
                            break;
                        default:
                            $finalGradeExamGroup = $gradeExamGroup;
                    }
                }                
            
                $examGroup = $examGroup;
                
                $reason = null;
                $passed = null;

                if ($examGroup->minimal_grade_type != 0) {
                    if ($examGroup->minimal_grade_type == 1) {
                        if($gradeCount == $this->totalAbovePassingGrade($examGroup)) {
                            $reason = $examGroup->description_grade_more_than_minimal;
                            $passed = 1;
                        } else {
                            $reason = $examGroup->description_grade_less_than_minimal;
                            $passed = 0;
                        }
                    }
                
                    if ($examGroup->minimal_grade_type == 2) {
                        
                        if($finalGradeExamGroup >= $examGroup->minimal_grade) {
                            $reason = $examGroup->description_grade_more_than_minimal;
                            $passed = 1;
                        } else {
                            $reason = $examGroup->description_grade_less_than_minimal;
                            $passed = 0;
                        }
                    }
                } else {
                    $reason = null;
                }
                
                ExamGroupUser::where([
                    'exam_group_id' => $examGroup->id,
                    'user_id' => $grade->user_id,
                ])->update([
                    'grade' => $finalGradeExamGroup,
                    'is_finished' => 1,
                    'passed' => $passed,
                    'description' => $reason
                ]);
            }
        }

        return $grade;
    }

    function totalAbovePassingGrade($gradesArray) 
    {
        $total = 0;
        if($gradesArray->grade) {
            foreach($gradesArray->grade as $grade) {
                $passingGrade = isset($grade->exam->questionTitle->passing_grade) ? floatval($grade->exam->questionTitle->passing_grade) : 0;
                if (!is_nan($passingGrade) && $grade->grade >= $passingGrade) {
                    $total += 1;
                }
            } 
        }

        return $total;
    }

    function slope($answerData) 
    {
       if($answerData) {
            $n = count($answerData);
            $sumX = 0;
            $sumY = 0;
            $sumXY = 0;
            $sumX2 = 0;

            for ($i = 0; $i < $n; $i++) {
                $x = $i + 1;
                $y = $answerData[$i];
                
                $sumX += $x;
                $sumY += $y;
                $sumXY += $x * $y;
                $sumX2 += $x * $x;
            }
            
            $slope = ($n * $sumXY - $sumX * $sumY) / ($n * $sumX2 - $sumX * $sumX);

            return $slope;
       } else {
            return 0;
       }

    }
}