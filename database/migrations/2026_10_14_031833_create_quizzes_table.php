<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('quizzes', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('training_id')->constrained('trainings')->cascadeOnDelete();
            $table->foreignUuid('module_id')->nullable()->constrained('training_modules')->nullOnDelete();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('assessment_type', 30)->default('MODULE_QUIZ');
            $table->decimal('passing_score', 5, 2)->default(70);
            $table->unsignedInteger('duration_minutes')->nullable();
            $table->unsignedInteger('max_attempts')->default(1);
            $table->boolean('shuffle_questions')->default(false);
            $table->boolean('shuffle_options')->default(false);
            $table->string('show_explanation_policy', 30)->default('AFTER_SUBMIT');
            $table->boolean('is_published')->default(false);
            $table->timestamps();
            $table->index([
                'training_id',
                'assessment_type',
            ]);
            $table->index([
                'module_id',
                'assessment_type',
            ]);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('quizzes');
    }
};
