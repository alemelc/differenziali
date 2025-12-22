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
        Schema::create('seniority_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('application_id')->constrained()->onDelete('cascade');
            $table->string('esperienza_come');
            $table->string('nell_area');
            $table->date('dal');
            $table->date('al');
            $table->boolean('tempo_pieno')->default(false);
            $table->boolean('tempo_parziale')->default(false);
            $table->decimal('percentuale_tempo_parziale', 5, 2)->nullable();
            $table->string('ente');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('seniority_records');
    }
};
