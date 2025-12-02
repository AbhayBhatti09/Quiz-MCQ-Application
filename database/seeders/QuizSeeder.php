<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Quizzes;


class QuizSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Quizzes::updateOrCreate(
           [
            'title' => 'Test Quiz',
            'question_count' => 10,
            'total_marks' => 10,
            'quiz_time' => 10,
            'description' => 'This quiz is designed to test basic knowledge.',
            'rules' => 'No negative marking. Do not refresh the page.',
           ]
        );
    }
}
