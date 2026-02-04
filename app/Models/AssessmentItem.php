<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssessmentItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'assessment_id',
        'item_no',
        'question_text',
        'correct_answer',
        'points',
    ];

    public function assessment()
    {
        return $this->belongsTo(Assessment::class);
    }
}
