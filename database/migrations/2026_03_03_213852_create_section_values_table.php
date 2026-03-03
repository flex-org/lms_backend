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
            $table->foreignId('section_structure_id')->constrained()->cascadeOnDelete();
            $table->timestamps();
        });

        Schema::create('section_value_translations', function (Blueprint $table) {
            $table->id();
            $table->string('locale')->index();
            $table->foreignId('section_value_id')->constrained()->cascadeOnDelete();
            $table->json('value')->nullable();
            $table->unique(['section_value_id', 'locale']);
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
