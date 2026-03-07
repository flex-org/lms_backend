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
        Schema::create('section_values', function (Blueprint $table) {
            $table->id();
            $table->foreignId('platform_section_id')->constrained()->cascadeOnDelete();
            $table->foreignId('structure_id')->constrained('structures')->cascadeOnDelete();
            $table->unsignedInteger('group_index')->default(0);
            $table->unsignedInteger('position')->default(0);
            $table->timestamps();

            $table->unique(['platform_section_id', 'structure_id', 'group_index', 'position'], 'section_values_scope_unique');
            $table->index(['platform_section_id', 'group_index', 'position']);
        });

        Schema::create('section_value_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('section_value_id')->constrained()->cascadeOnDelete();
            $table->string('locale', 10);
            $table->longText('content')->nullable();
            $table->unique(['section_value_id', 'locale']);
            $table->index('locale');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('section_value_translations');
        Schema::dropIfExists('section_values');
    }
};
