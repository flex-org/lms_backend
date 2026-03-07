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
        Schema::create('values', function (Blueprint $table) {
            $table->id();
            $table->foreignId('platform_section_id')->constrained()->cascadeOnDelete();
            $table->foreignId('structure_id')->constrained()->cascadeOnDelete();
            $table->timestamps();
        });

        Schema::create('value_translations', function (Blueprint $table) {
            $table->id();
            $table->string('locale')->index();
            $table->foreignId('value_id')->constrained()->cascadeOnDelete();
            $table->json('value')->nullable();
            $table->unique(['value_id', 'locale']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('value_translations');
        Schema::dropIfExists('values');
    }
};
