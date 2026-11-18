
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('quiz_question_items', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->foreignUuid('quiz_id')
                ->constrained('quizzes')
                ->cascadeOnDelete();

            $table->foreignUuid('question_id')
                ->constrained('quiz_questions')
                ->restrictOnDelete();

            $table->unsignedInteger('sequence')->default(1);
            $table->decimal('weight_points', 8, 2)->default(1);

            $table->unique(['quiz_id', 'question_id']);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('quiz_question_items');
    }
};
