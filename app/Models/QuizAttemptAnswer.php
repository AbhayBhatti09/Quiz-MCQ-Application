<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuizAttemptAnswer extends Model
{
    use HasFactory;
     protected $fillable = ['attempt_id', 'mcq_id', 'selected_option', 'is_correct'];

    public function attempt()
    {
        return $this->belongsTo(QuizAttempt::class, 'attempt_id');
    }

    public function mcq()
    {
        return $this->belongsTo(Mcq::class);
    }
}
