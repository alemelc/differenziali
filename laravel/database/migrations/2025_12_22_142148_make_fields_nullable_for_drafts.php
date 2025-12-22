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
        DB::statement("ALTER TABLE seniority_records MODIFY dal DATE NULL");
        DB::statement("ALTER TABLE seniority_records MODIFY al DATE NULL");
        DB::statement("ALTER TABLE seniority_records MODIFY esperienza_come VARCHAR(255) NULL");
        DB::statement("ALTER TABLE seniority_records MODIFY nell_area VARCHAR(255) NULL");
        DB::statement("ALTER TABLE seniority_records MODIFY ente VARCHAR(255) NULL");

        // Disciplinary
        DB::statement("ALTER TABLE disciplinary_proceedings MODIFY data DATE NULL");
        DB::statement("ALTER TABLE disciplinary_proceedings MODIFY oggetto TEXT NULL");

        // Titles
        DB::statement("ALTER TABLE titles MODIFY descrizione VARCHAR(255) NULL");
        
        // Trainings
        DB::statement("ALTER TABLE trainings MODIFY descrizione VARCHAR(255) NULL");
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
