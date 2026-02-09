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
        // Modifier la colonne statut pour inclure 'stockage'
        DB::statement("ALTER TABLE produits MODIFY COLUMN statut ENUM('stockage', 'valide', 'prepare', 'en_route', 'livre') DEFAULT 'stockage'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("ALTER TABLE produits MODIFY COLUMN statut ENUM('valide', 'prepare', 'en_route', 'livre') DEFAULT 'valide'");
    }
};
