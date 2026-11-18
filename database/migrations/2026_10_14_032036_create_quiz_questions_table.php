
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('quiz_questions', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->text('question');
            $table->string('type');
            $table->string('difficulty')->nullable();
            $table->text('explanation')->nullable();

            $table->string('source')->nullable();
            $table->uuid('source_document_id')->nullable();

            $table->boolean('ai_generated')->default(false);
            $table->text('generation_prompt')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('quiz_questions');
    }
};
