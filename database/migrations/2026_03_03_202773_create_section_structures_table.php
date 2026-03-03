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
        Schema::create('section_structures', function (Blueprint $table) {
            $table->id();
            $table->foreignId('section_id')->constrained()->cascadeOnDelete();
            $table->string('type'); // text, description, image, composite
            $table->string('name');
            $table->boolean('is_array')->default(false);
            $table->unique(['section_id', 'name']);
            $table->timestamps();
        });

        Schema::create('section_structure_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('section_structure_id')->constrained()->cascadeOnDelete();
            $table->string('locale')->index();
            $table->text('label')->nullable();
            $table->text('placeholder')->nullable();
            $table->string('locale')->index();
            $table->unique(['section_structure_id', 'locale']);
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('section_structures');
    }
};
