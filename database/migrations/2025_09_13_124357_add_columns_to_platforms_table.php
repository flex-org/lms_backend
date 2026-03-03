<?php

use App\Models\User;
use App\Modules\Plans\Models\Plan;
use function Laravel\Prompts\table;
use App\Modules\Themes\Models\Theme;
use Illuminate\Support\Facades\Schema;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('platforms', function (Blueprint $table) {
            $table->string('name')->nullable();
            $table->text('about')->nullable();
            $table->json('key_words')->nullable();
        });
    }
};
