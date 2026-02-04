<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    use HasFactory;

    protected $fillable = [
        'section_id',
        'school_year_id',
        'report_type',
        'generated_at',
        'payload_json',
    ];

    protected function casts(): array
    {
        return [
            'generated_at' => 'datetime',
            'payload_json' => 'array',
        ];
    }

    public function section()
    {
        return $this->belongsTo(Section::class);
    }

    public function schoolYear()
    {
        return $this->belongsTo(SchoolYear::class);
    }
}
