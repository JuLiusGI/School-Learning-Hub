<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Section extends Model
{
    use HasFactory;

    protected $fillable = [
        'grade_id',
        'name',
        'adviser_user_id',
    ];

    public function grade()
    {
        return $this->belongsTo(Grade::class);
    }

    public function adviser()
    {
        return $this->belongsTo(User::class, 'adviser_user_id');
    }

    public function teachers()
    {
        return $this->belongsToMany(User::class)
            ->withTimestamps();
    }

    public function enrollments()
    {
        return $this->hasMany(Enrollment::class);
    }

    public function assessments()
    {
        return $this->hasMany(Assessment::class);
    }

    public function reports()
    {
        return $this->hasMany(Report::class);
    }
}
