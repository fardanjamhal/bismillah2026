<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\MenuUser;

class MenuUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        MenuUser::insert([
            [
                'code' => 'question_practice',
                'description' => 'Latihan Soal',
                'order' => 1,
                'is_active' => 1,
                'type' => 'sale',
            ],
            [
                'code' => 'tryout',
                'description' => 'Tryout',
                'order' => 2,
                'is_active' => 1,
                'type' => 'sale',
            ],
            [
                'code' => 'module',
                'description' => 'Modul / Materi',
                'order' => 3,
                'is_active' => 1,
                'type' => 'sale',
            ],
            [
                'code' => 'video_module',
                'description' => 'Video Pembelajaran',
                'order' => 4,
                'is_active' => 1,
                'type' => 'sale',
            ],
            [
                'code' => 'course',
                'description' => 'Course',
                'order' => 5,
                'is_active' => 1,
                'type' => 'sale',
            ],
        ]);
    }
}
