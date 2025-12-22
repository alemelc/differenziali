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
        Schema::table('users', function (Blueprint $table) {
            $table->string('role')->default('user')->after('password'); // 'admin' or 'user'
            $table->string('matricola')->unique()->nullable()->after('role');
            $table->string('surname')->after('name')->nullable();
            
            // Anagrafica
            $table->string('area_appartenenza')->nullable();
            $table->string('profilo_attuale')->nullable();
            $table->string('nato_a')->nullable();
            $table->date('data_nascita')->nullable();
            $table->string('residente_a')->nullable();
            $table->string('via')->nullable();
            $table->string('cap')->nullable();
            $table->string('prov')->nullable();
            $table->string('codice_fiscale')->nullable();
            $table->string('telefono')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'role', 'matricola', 'surname', 
                'area_appartenenza', 'profilo_attuale', 
                'nato_a', 'data_nascita', 
                'residente_a', 'via', 'cap', 'prov', 
                'codice_fiscale', 'telefono'
            ]);
        });
    }
};
