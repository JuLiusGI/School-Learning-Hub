<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('competencies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('subject_id')->constrained()->cascadeOnDelete();
            $table->foreignId('grade_id')->constrained()->cascadeOnDelete();
            $table->string('code');
            $table->text('description');
            $table->string('matatag_tag');
            $table->timestamps();
            $table->unique(['subject_id', 'grade_id', 'code']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('competencies');
    }
};
