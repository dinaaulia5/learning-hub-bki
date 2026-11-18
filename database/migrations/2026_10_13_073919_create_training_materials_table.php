
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('training_materials', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('module_id')
                ->constrained('training_modules')
                ->cascadeOnDelete();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('type', 50);
            $table->string('file_path')->nullable();
            $table->string('file_name')->nullable();
            $table->unsignedBigInteger('file_size')->nullable();
            $table->string('mime_type')->nullable();
            $table->string('video_url')->nullable();
            $table->unsignedInteger('sequence')->default(1);
            $table->unsignedInteger('duration_minutes')->nullable();
            $table->boolean('is_preview')->default(false);
            $table->timestamps();
            $table->index(['module_id', 'sequence']);
            // $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('training_materials');
    }
};
