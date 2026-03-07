<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('structures', function (Blueprint $table) {
            $table->id();
            $table->foreignId('section_id')->constrained()->cascadeOnDelete();
            $table->foreignId('parent_id')->nullable()->constrained('structures')->nullOnDelete();
            $table->string('key');
            $table->string('type');
            $table->boolean('is_array')->default(false);
            $table->unsignedInteger('position')->default(0);
            $table->json('settings')->nullable();
            $table->timestamps();

            $table->unique(['section_id', 'key']);
            $table->index(['section_id', 'parent_id', 'position']);
        });

        Schema::create('structure_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('structure_id')->constrained('structures')->cascadeOnDelete();
            $table->string('locale', 10);
            $table->string('label');
            $table->string('placeholder')->nullable();
            $table->unique(['structure_id', 'locale']);
            $table->index('locale');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('structure_translations');
        Schema::dropIfExists('structures');
    }
};
