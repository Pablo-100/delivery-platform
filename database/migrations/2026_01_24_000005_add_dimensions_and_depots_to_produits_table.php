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
        Schema::table('produits', function (Blueprint $table) {
            // Dimensions pour calcul poids volumétrique
            $table->decimal('longueur', 10, 2)->nullable()->after('volume')->comment('Longueur en cm');
            $table->decimal('largeur', 10, 2)->nullable()->after('longueur')->comment('Largeur en cm');
            $table->decimal('hauteur', 10, 2)->nullable()->after('largeur')->comment('Hauteur en cm');
            $table->decimal('poids_volumetrique', 10, 2)->nullable()->after('hauteur')->comment('Poids volumétrique calculé (L×l×h)/5000');
            
            // Dépôts
            $table->foreignId('depot_source_id')->nullable()->after('poids_volumetrique')->constrained('depots')->nullOnDelete();
            $table->foreignId('depot_destination_id')->nullable()->after('depot_source_id')->constrained('depots')->nullOnDelete();
            $table->foreignId('depot_actuel_id')->nullable()->after('depot_destination_id')->constrained('depots')->nullOnDelete()->comment('Dépôt actuel où se trouve le colis');
            
            // Infos supplémentaires expéditeur
            $table->string('expediteur_nom')->nullable()->after('expediteur_phone');
            
            // Infos supplémentaires destinataire
            $table->string('destinataire_phone')->nullable()->after('destinataire_prenom');
            $table->string('destinataire_adresse')->nullable()->after('destinataire_phone');
            $table->string('destinataire_ville')->nullable()->after('destinataire_adresse');
            
            // Statut étendu pour le stockage
            $table->enum('statut_depot', ['stockage', 'en_transit', 'au_depot', 'en_livraison', 'livre', 'retour'])->default('stockage')->after('destinataire_ville');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('produits', function (Blueprint $table) {
            // Supprimer les contraintes de clé étrangère
            $table->dropForeign(['depot_source_id']);
            $table->dropForeign(['depot_destination_id']);
            $table->dropForeign(['depot_actuel_id']);
            
            // Supprimer les colonnes
            $table->dropColumn([
                'longueur',
                'largeur',
                'hauteur',
                'poids_volumetrique',
                'depot_source_id',
                'depot_destination_id',
                'depot_actuel_id',
                'expediteur_nom',
                'destinataire_phone',
                'destinataire_adresse',
                'destinataire_ville',
                'statut_depot'
            ]);
        });
    }
};
