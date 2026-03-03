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
        Schema::create('platform_sections', function (Blueprint $table) {
            $table->id();
            $table->foreignId('platform_page_id')->constrained()->cascadeOnDelete();
            $table->foreignId('section_id')->constrained()->cascadeOnDelete();
            $table->unique(['platform_page_id', 'section_id']);
            $table->boolean('active')->default(true);
            $table->integer('position')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('platform_sections');
    }
};
