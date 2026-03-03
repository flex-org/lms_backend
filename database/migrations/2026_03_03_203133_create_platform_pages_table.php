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
        Schema::create('platform_pages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('platform_id')->constrained()->cascadeOnDelete();
            $table->foreignId('page_id')->constrained()->cascadeOnDelete();
            $table->boolean('active')->default(true);
            $table->primary(['platform_id', 'page_id']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('platform_pages');
    }
};
