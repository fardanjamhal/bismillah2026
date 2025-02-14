<?php

namespace App\Imports;

use App\Models\Lesson\Question;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Validators\ValidationException;

class GenerateQuestionImport implements ToModel, WithHeadingRow, WithValidation
{
    protected $questionTitle;
    protected $questionTemplate;
    protected $index = 1;

    public function __construct($questionTitle, $questionTemplate)
    {
        $this->questionTitle = $questionTitle;
        $this->questionTemplate = $questionTemplate;
    }

    public function model(array $row)
    {
        // Menghapus spasi dari inputan question
        $cleanQuestion = preg_replace('/[\p{C}\s]+/u', '', $row['question']);

        // Mengecek panjang question setelah menghapus spasi
        if (mb_strlen($cleanQuestion) !== 4) {
            throw new \Exception("Question harus tepat 4 karakter.");
        }

        // Mengubah string yang bersih menjadi array
        $columnQuestion = mb_str_split($cleanQuestion);

        $columnData = [
            $row['answer_text_a'],
            $row['answer_text_b'],
            $row['answer_text_c'],
            $row['answer_text_d'],
            $row['answer_text_e'],
            $columnQuestion[0] ?? '',
            $columnQuestion[1] ?? '',
            $columnQuestion[2] ?? '',
            $columnQuestion[3] ?? '',
        ];

        $questionText = $this->generateQuestion($columnData, ltrim($row['column']), $this->questionTemplate);

        $importData = [
            'question_title_id' => $this->questionTitle->id,
            'question' => $questionText['question'],
            'option_1' => $row['answer_text_a'],
            'option_2' => $row['answer_text_b'],
            'option_3' => $row['answer_text_c'],
            'option_4' => $row['answer_text_d'],
            'option_5' => $row['answer_text_e'],
            'answer' => [$this->convertAnswerToNumber(ltrim($row['answer']))],
            'section' => ltrim($row['column']),
            'created_at' => Carbon::now()->subDays(1)->addMinutes($this->index),
            'updated_at' => Carbon::now()->subDays(1)->addMinutes($this->index),
        ];

        $this->index++;

        $this->questionTitle->update(['updated_at' => Carbon::now()]);
        return new Question($importData);
    }

    private function convertAnswerToNumber($answer)
    {
        $answer = strtoupper($answer);
        $letterToNumber = ['A' => 1, 'B' => 2, 'C' => 3, 'D' => 4, 'E' => 5];

        return $letterToNumber[$answer] ?? null;
    }

    public function headingRow(): int
    {
        return 1;
    }

    private function generateQuestion($columnData, $section, $template)
    {
        $missingIndex = array_search($columnData[5], array_slice($columnData, 5)); // Index yang hilang
        $missingLetter = $columnData[5 + $missingIndex] ?? '';

        $replacements = [
            '{column}' => $section,
            '{oa}' => $columnData[0],
            '{ob}' => $columnData[1],
            '{oc}' => $columnData[2],
            '{od}' => $columnData[3],
            '{oe}' => $columnData[4],
            '{q1}' => $columnData[5],
            '{q2}' => $columnData[6],
            '{q3}' => $columnData[7],
            '{q4}' => $columnData[8],
        ];

        $questionHtml = str_replace(array_keys($replacements), array_values($replacements), $template);

        return [
            'question' => $questionHtml,
            'correct_answer' => $missingLetter,
        ];
    }

    public function rules(): array
    {
        return [
            '*.no' => 'required|numeric',
            '*.column' => 'required|numeric',
            '*.question' => ['required', function ($attribute, $value, $fail) {
                $cleanValue = str_replace(' ', '', $value);
                if (mb_strlen($cleanValue) !== 4) {
                    $fail($attribute . ' harus tepat 4 karakter.');
                }

                $charArray = mb_str_split($cleanValue);
                if (count(array_unique($charArray)) !== count($charArray)) {
                    $fail($attribute . ' tidak boleh mengandung karakter yang sama.');
                }
            }],
            '*.answer_text_a' => 'required',
            '*.answer_text_b' => 'required',
            '*.answer_text_c' => 'required',
            '*.answer_text_d' => 'required',
            '*.answer_text_e' => 'required',
            '*.answer' => 'required|in:A,B,C,D,E',
        ];
    }

    public function customValidationMessages()
    {
        return [
            '*.no.required' => 'No wajib diisi',
            '*.no.numeric' => 'No harus berupa angka',
            '*.column.required' => 'Column wajib diisi',
            '*.column.numeric' => 'Column harus berupa angka',
            '*.question.required' => 'Question wajib diisi',
            '*.question.size' => 'Question harus tepat 4 karakter',
            '*.answer_text_a.required' => 'Answer Text A wajib diisi',
            '*.answer_text_b.required' => 'Answer Text B wajib diisi',
            '*.answer_text_c.required' => 'Answer Text C wajib diisi',
            '*.answer_text_d.required' => 'Answer Text D wajib diisi',
            '*.answer_text_e.required' => 'Answer Text E wajib diisi',
            '*.answer.required' => 'Answer wajib diisi',
            '*.answer.in' => 'Answer harus salah satu dari: A, B, C, D, E',
        ];
    }
}