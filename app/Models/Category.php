<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;
    protected $fillable = [
        'title',
    ];
       public function quizzes()
{
    return $this->belongsToMany(Quiz::class, 'quiz_category', 'category_id', 'quiz_id')->withTimestamps();
}

    public function mcqs()
    {
        return $this->hasMany(Mcq::class);
    }
 
}
