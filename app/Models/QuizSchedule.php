<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Quizzes as Quiz;


class QuizSchedule extends Model
{
    use HasFactory;
       protected $fillable = [
        'user_id',
        'quiz_id',
        'schedule_at',
        'is_processed',
    ];

      public function user() {
        return $this->belongsTo(User::class);
    }

    public function quiz() {
        return $this->belongsTo(Quiz::class);
    }
}
