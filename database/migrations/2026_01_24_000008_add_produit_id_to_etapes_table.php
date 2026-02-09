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
        Schema::table('etapes', function (Blueprint $table) {
            // Ajouter produit_id comme alternative à colis_id
            $table->foreignId('produit_id')->nullable()->after('colis_id')->constrained('produits')->cascadeOnDelete();
            
            // Ajouter des colonnes manquantes pour la compatibilité
            $table->string('description')->nullable()->after('commentaire');
            $table->text('localisation')->nullable()->after('longitude');
            $table->foreignId('depot_id')->nullable()->after('depot_arrivee_id')->constrained('depots')->nullOnDelete();
        });

        // Rendre colis_id nullable pour permettre l'utilisation de produit_id
        Schema::table('etapes', function (Blueprint $table) {
            $table->foreignId('colis_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('etapes', function (Blueprint $table) {
            $table->dropForeign(['produit_id']);
            $table->dropColumn(['produit_id', 'description', 'localisation']);
            $table->dropForeign(['depot_id']);
            $table->dropColumn('depot_id');
        });
    }
};
