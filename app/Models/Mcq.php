<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mcq extends Model
{
    use HasFactory;
     protected $fillable = [
        'question',
        'option_a',
        'option_b',
        'option_c',
        'option_d',
        'correct_answer',
        'quiz_id',
        'marks',
        'correct_answer_text',
        'category_id'
    ];

public function getCorrectAnswerFullAttribute()
{
    $label = strtoupper($this->correct_answer);        
    $text  = $this->{'option_'.$this->correct_answer};  

    return "({$label}) - {$text}";
}

 public function category()
    {
        return $this->belongsTo(Category::class);
    }
   public function quizzes()
{
    return $this->belongsToMany(Quiz::class, 'quiz_mcq', 'mcq_id', 'quiz_id')
                ->withPivot('marks', 'mcq_per_timer')
                ->withTimestamps();
}
}
