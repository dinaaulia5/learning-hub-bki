
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('quiz_attempts', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('quiz_id')->constrained('quizzes')->restrictOnDelete();
            $table->foreignUuid('employee_id')->constrained('employees')->restrictOnDelete();
            $table->foreignUuid('enrollment_id')->constrained('enrollments')->restrictOnDelete();
            $table->unsignedInteger('attempt_number');
            $table->timestamp('started_at');
            $table->timestamp('submitted_at')->nullable();
            $table->unsignedInteger('time_spent_seconds')->default(0);
            $table->decimal('score', 8, 2)->nullable();
            $table->boolean('passed')->nullable();
            $table->timestamps();
            $table->unique([
                'quiz_id',
                'employee_id',
                'attempt_number',
            ]);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('quiz_attempts');
    }
};
