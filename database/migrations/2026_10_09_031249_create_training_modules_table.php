
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('training_modules', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->foreignUuid('training_id')
                ->constrained('trainings')
                ->restrictOnDelete();

            $table->string('title');
            $table->text('description')->nullable();

            $table->unsignedInteger('sequence')->default(1);
            $table->unsignedInteger('duration_minutes')->nullable();

            $table->boolean('is_required')->default(true);
            $table->boolean('is_active')->default(true);

            $table->timestamps();
            $table->softDeletes();

            $table->index(['training_id', 'sequence']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('training_modules');
    }
};
