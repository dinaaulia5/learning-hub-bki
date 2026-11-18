<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('trainings', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->string('code')->unique();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description')->nullable();

            $table->foreignUuid('category_id')
                ->constrained('training_categories')
                ->restrictOnDelete();

            $table->foreignUuid('type_id')
                ->constrained('training_types')
                ->restrictOnDelete();

            $table->string('level')->nullable();

            $table->integer('duration_minutes')->nullable();
            $table->integer('capacity')->nullable();

            $table->boolean('is_mandatory')->default(false);

            $table->string('status')->default('DRAFT');

            $table->string('thumbnail')->nullable();

            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();

            $table->date('registration_start')->nullable();
            $table->date('registration_end')->nullable();
            $table->softDeletes();
            $table->foreignUuid('created_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamps();
        });
    }


    public function down(): void
    {
        Schema::dropIfExists('trainings');
    }
};
