
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('enrollments', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('employee_id')->constrained('employees')->cascadeOnDelete();
            $table->foreignUuid('training_id')->constrained('trainings')->cascadeOnDelete();
            $table->string('status')->default('REGISTERED');
            $table->timestamp('registered_at')->nullable();
            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->decimal('progress', 8, 2)->default(0);
            $table->decimal('final_score', 8, 2)->nullable();
            $table->timestamps();
            $table->unique(['employee_id', 'training_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('enrollments');
    }
};
