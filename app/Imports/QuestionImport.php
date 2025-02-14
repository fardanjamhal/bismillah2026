<?php

namespace App\Imports;

use App\Models\Lesson\Question;
use App\Models\Lesson\ValueCategory;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Carbon\Carbon;
use App\Models\Exam\ExamGroup;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Validators\ValidationException;

class QuestionImport implements ToModel, WithHeadingRow, WithValidation
{
    protected $questionTitle;

    protected $index = 1;

    public function __construct($questionTitle)
    {
        $this->questionTitle = $questionTitle;
    }

    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        $importdata = [
            'question_title_id' => $this->questionTitle->id,
            'question' => ltrim($row['soal']),
            'discussion' => ltrim($row['pembahasan']),
            'created_at' => Carbon::now()->subDays(1)->addSeconds($this->index),
            'updated_at' => Carbon::now()->subDays(1)->addSeconds($this->index),
        ];

        if ($this->questionTitle->total_section > 1) {
            $importdata['section'] = ltrim($row['kolom_ke']);
        }

        if ($this->questionTitle->assessment_type == 1) {
            $importdata['value_category_id'] = $this->valueCategory(ltrim($row['kategori_penilaian']));
        }

        for ($i = 0; $i < $this->questionTitle->total_choices; $i++) {
            $importdata['option_' . ($i + 1)] = ltrim($row['pilihan_' . strtolower(chr(65 + $i))]);
        }

        if ($this->questionTitle->assessment_type != 4) {

            $answers = explode(',', preg_replace('/\s*,\s*/', ',', $row['jawaban']));

            $answersInsert = [];

            foreach ($answers as $answer) {
                if($this->convertAnswerToNumber(ltrim($answer)) != null) {
                    $answersInsert[] =  $this->convertAnswerToNumber(ltrim($answer));

                }
            }

            $answer = array_values(array_unique($answersInsert));
            $importdata['answer'] = $answer;
            $importdata['total_answer_limit'] = count($answer);
        }

        if ($this->questionTitle->assessment_type == 4) {
            for ($i = 0; $i < $this->questionTitle->total_choices; $i++) {
                $importdata['point_' . ($i + 1)] = ltrim($row['bobot_jawaban_' . strtolower(chr(65 + $i))]);
            }
        }

        if ($this->questionTitle->assessment_type == 3) {
            $importdata['correct_point'] = ltrim($row['poin_jawaban_benar']);
        }

        $this->index++;

        foreach($this->questionTitle->exam as $exam) {
            if(!empty($exam->exam_group_id)) {
                ExamGroup::find($exam->exam_group_id)->update(['updated_at' => Carbon::now()]);
            }
        }

        $this->questionTitle->update(['updated_at' => Carbon::now()]);
        
        return new Question($importdata);
    }

    private function convertAnswerToNumber($answer)
    {
        $answer = strtoupper($answer);
        $letterToNumber = ['A' => 1, 'B' => 2, 'C' => 3, 'D' => 4, 'E' => 5];
        
        return $letterToNumber[$answer] ?? null;
    }

    private function valueCategory($value)
    {
        $valueCategory = ValueCategory::where([
            'lesson_id' => $this->questionTitle->lesson_id, 
            'value_category_group_id' => $this->questionTitle->value_category_group_id, 
            'name' => $value
        ])->first();

        return $valueCategory ? $valueCategory->id : null;
    }

    public function headingRow(): int
    {
        return 1;
    }

    public function rules(): array
    {   
        $validation = [
            '*.no' => 'required|numeric',
            '*.soal' => 'required',
        ];

        if ($this->questionTitle->total_section > 1) {
            $validation['*.kolom_ke'] = ltrim($row['kolom_ke']);
        }

        if ($this->questionTitle->assessment_type == 1) {

            $allowedValues = ValueCategory::where([
                'lesson_id' => $this->questionTitle->lesson_id, 
                'value_category_group_id' => $this->questionTitle->value_category_group_id
            ])->pluck('name')->toArray();
            
            $validation['*.kategori_penilaian'] = [
                'required',
                function ($attribute, $value, $fail) use ($allowedValues) {
                    if (!in_array(strtolower($value), array_map('strtolower', $allowedValues))) {
                        // Custom error message
                        $fail('Kategori Penilaian harus berisi salah satu dari: ' . implode(', ', $allowedValues));
                    }
                },
            ];
        }

        for ($i = 0; $i < $this->questionTitle->total_choices; $i++) {
            $validation['*.pilihan_' . strtolower(chr(65 + $i))] = 'required';
        }

        if ($this->questionTitle->assessment_type != 4) {
            $validation['*.jawaban'] = 'required|regex:' . '/^([' . implode('', range('A', chr(64 + $this->questionTitle->total_choices))) . '](?:\s*,\s*[' . implode('', range('A', chr(64 + $this->questionTitle->total_choices))) . '])*)$/';
        }

        if ($this->questionTitle->assessment_type == 3) {
            $validation['poin_jawaban_benar'] = 'required|numeric';
        }

        if ($this->questionTitle->assessment_type == 4) {
            for ($i = 0; $i < $this->questionTitle->total_choices; $i++) {
                $validation['bobot_jawaban_' . strtolower(chr(65 + $i))] = 'required|numeric';
            }
        }
        
        return $validation;
    }

    public function customValidationMessages()
    {
        return [
            '*.no.required' => 'No wajib diisi',
            '*.no.numeric' => 'No harus berupa angka',

            '*.poin_jawaban_benar.required' => 'Poin Jawaban Benar wajib diisi',
            '*.poin_jawaban_benar.numeric' => 'Poin Jawaban Benar harus berupa angka',

            '*.kategori_penilaian.required' => 'Kategori Penilaian wajib diisi',
            '*.soal.required' => 'Soal wajib diisi',

            '*.pilihan_a.required' => 'Pilihan A wajib diisi',
            '*.pilihan_b.required' => 'Pilihan B wajib diisi',
            '*.pilihan_c.required' => 'Pilihan C wajib diisi',
            '*.pilihan_d.required' => 'Pilihan D wajib diisi',
            '*.pilihan_e.required' => 'Pilihan E wajib diisi',

            '*.bobot_jawaban_a.required' => 'Bobot jawaban A wajib diisi',
            '*.bobot_jawaban_b.required' => 'Bobot jawaban B wajib diisi',
            '*.bobot_jawaban_c.required' => 'Bobot jawaban C wajib diisi',
            '*.bobot_jawaban_d.required' => 'Bobot jawaban D wajib diisi',
            '*.bobot_jawaban_e.required' => 'Bobot jawaban E wajib diisi',

            '*.bobot_jawaban_a.numeric' => 'Bobot jawaban A harus berupa angka',
            '*.bobot_jawaban_b.numeric' => 'Bobot jawaban B harus berupa angka',
            '*.bobot_jawaban_c.numeric' => 'Bobot jawaban C harus berupa angka',
            '*.bobot_jawaban_d.numeric' => 'Bobot jawaban D harus berupa angka',
            '*.bobot_jawaban_e.numeric' => 'Bobot jawaban E harus berupa angka',

            '*.jawaban.required' => 'Jawaban wajib diisi',
            '*.jawaban.regex' => 'Jawaban harus salah satu dari: ' . implode(', ', range('A', chr(64 + $this->questionTitle->total_choices))) . ' atau kombinasinya yang dipisah dengan koma'
        ];
    }
}
