<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Grade extends Model
{
    use HasFactory;

    protected $fillable = [
        'level',
    ];

    public function sections()
    {
        return $this->hasMany(Section::class);
    }

    public function competencies()
    {
        return $this->hasMany(Competency::class);
    }

    public function lessons()
    {
        return $this->hasMany(Lesson::class);
    }
}
