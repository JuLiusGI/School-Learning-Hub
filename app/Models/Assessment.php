<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Assessment extends Model
{
    use HasFactory;

    protected $fillable = [
        'lesson_id',
        'section_id',
        'title',
        'type',
        'max_score',
        'date_given',
    ];

    protected function casts(): array
    {
        return [
            'date_given' => 'date',
        ];
    }

    public function lesson()
    {
        return $this->belongsTo(Lesson::class);
    }

    public function section()
    {
        return $this->belongsTo(Section::class);
    }

    public function items()
    {
        return $this->hasMany(AssessmentItem::class);
    }

    public function scores()
    {
        return $this->hasMany(Score::class);
    }
}
