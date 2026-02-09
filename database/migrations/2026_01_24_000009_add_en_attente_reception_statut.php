<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Ajouter le nouveau statut "en_attente_reception"
        DB::statement("ALTER TABLE produits MODIFY COLUMN statut ENUM('stockage', 'valide', 'prepare', 'en_route', 'en_attente_reception', 'livre') DEFAULT 'stockage'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE produits MODIFY COLUMN statut ENUM('stockage', 'valide', 'prepare', 'en_route', 'livre') DEFAULT 'stockage'");
    }
};
