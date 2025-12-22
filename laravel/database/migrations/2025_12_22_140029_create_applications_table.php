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
        Schema::create('applications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->enum('status', ['draft', 'submitted'])->default('draft');
            $table->dateTime('submission_date')->nullable();
            
            // Posizione Attuale / Differenziale
            $table->string('area_differenziale')->nullable();
            $table->string('profilo_differenziale')->nullable();
            $table->boolean('tempo_pieno')->default(false);
            $table->boolean('tempo_parziale')->default(false);
            $table->decimal('percentuale_tempo_parziale', 5, 2)->nullable();
            
            // Valutazioni
            $table->decimal('valutazione_2022', 5, 2)->nullable();
            $table->decimal('valutazione_2023', 5, 2)->nullable();
            $table->decimal('valutazione_2024', 5, 2)->nullable();
            $table->decimal('media_valutazioni', 5, 2)->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('applications');
    }
};
