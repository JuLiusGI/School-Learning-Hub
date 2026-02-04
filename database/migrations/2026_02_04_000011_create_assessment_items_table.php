<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('assessment_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('assessment_id')->constrained()->cascadeOnDelete();
            $table->unsignedInteger('item_no');
            $table->text('question_text');
            $table->text('correct_answer')->nullable();
            $table->unsignedInteger('points');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('assessment_items');
    }
};
