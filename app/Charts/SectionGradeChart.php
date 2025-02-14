<?php

namespace App\Charts;

use ArielMejiaDev\LarapexCharts\LarapexChart;

class SectionGradeChart
{
    protected $chart;

    public function __construct($chart, $grade)
    {
        $this->chart = $chart;
        $this->grade = $grade;
    }

    public function build(): array
    {
        $totalCorrectPerSection = 
        
        $generateLabel = [];

        for($i = 1; $i <= $this->grade->exam->questionTitle->total_section; $i++) {
            $generateLabel[] = 'Kolom '.$i;
        }

        return $this->chart->lineChart()
            ->setTitle('Jumlah Pengerjaan Tiap Kolom')
            ->setSubtitle('Menampilkan Data Total Menjawab & Jawaban Yang Benar Per Kolom')
            ->addData('Total Menjawab', $this->grade->total_answer_per_section ?? [])
            ->addData('Total Jawaban Benar', $this->grade->total_correct_per_section ?? [])
            ->setXAxis($generateLabel)
            ->setDataLabels(true) // Enable data labels
            ->setGrid('#222', 0.01)
            ->toVue();
    }
}
