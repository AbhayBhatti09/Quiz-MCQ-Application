<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Quizzes extends Model
{
    use HasFactory;
     protected $fillable = [
        'title',
        'question_count',
        'total_marks',
        'quiz_time',
        'description',
        'rules',
        'marks_per_question',
        'time_per_question',
        'time_type',
    ];
    public function category()
{
    return $this->belongsTo(Category::class);
}

public function categories()
{
    return $this->belongsToMany(Category::class, 'quiz_category', 'quiz_id', 'category_id')->withTimestamps();
}

public function mcqs()
{
    return $this->belongsToMany(Mcq::class, 'quiz_mcq', 'quiz_id', 'mcq_id')
                ->withTimestamps();
}

 
}
