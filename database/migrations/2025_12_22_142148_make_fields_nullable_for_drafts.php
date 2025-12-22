<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Seniority
        Schema::table('seniority_records', function (Blueprint $table) {
            $table->date('dal')->nullable()->change();
            $table->date('al')->nullable()->change();
            $table->string('esperienza_come')->nullable()->change();
            $table->string('nell_area')->nullable()->change();
            $table->string('ente')->nullable()->change();
        });

        // Disciplinary
        Schema::table('disciplinary_proceedings', function (Blueprint $table) {
            $table->date('data')->nullable()->change();
            $table->text('oggetto')->nullable()->change();
        });

        // Titles
        Schema::table('titles', function (Blueprint $table) {
            $table->string('descrizione')->nullable()->change();
        });
        
        // Trainings
        Schema::table('trainings', function (Blueprint $table) {
            $table->string('descrizione')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Reverting not trivial without doctrine/dbal or strict knowledge of previous state, 
        // typically drafts allow nulls so this is a permanent structural improvement.
    }
};
