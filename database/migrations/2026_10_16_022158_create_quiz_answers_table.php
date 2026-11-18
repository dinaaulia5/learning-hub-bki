
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('quiz_answers', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('attempt_id')->constrained('quiz_attempts')->cascadeOnDelete();
            $table->foreignUuid('question_id')->constrained('quiz_questions')->restrictOnDelete();
            $table->foreignUuid('option_id')->nullable()->constrained('quiz_options')->nullOnDelete();
            $table->text('answer_text')->nullable();
            $table->text('selected_option_text')->nullable();
            $table->boolean('is_correct')->nullable();
            $table->decimal('points', 8, 2)->default(0);
            $table->timestamps();
            $table->unique(['attempt_id', 'question_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('quiz_answers');
    }
};
