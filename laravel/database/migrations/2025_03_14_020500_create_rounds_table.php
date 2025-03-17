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
        Schema::create('rounds', function (Blueprint $table) {
            $table->id();
            $table->foreignId('game_id')->constrained()->onDelete('cascade');
            $table->integer('round_number');
            $table->foreignId('prompt_id')->nullable()->constrained('prompts')->onDelete('set null');
            $table->integer('imposter_round_count')->default(1);
            $table->foreignId('imposter_id')->nullable()->constrained('players')->onDelete('set null');
            $table->enum('status',['waiting', 'in_progress', 'completed'])->default('waiting');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rounds');
    }
};
