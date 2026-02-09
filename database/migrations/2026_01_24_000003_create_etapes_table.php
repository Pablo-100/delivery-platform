<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('etapes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('colis_id')->constrained('colis')->cascadeOnDelete();
            
            // Type d'étape
            $table->enum('type', [
                'creation', // création du colis
                'reception_depot', // réception au dépôt par admin
                'affectation_flotte', // affectation à une flotte
                'prise_en_charge', // pris en charge par livreur
                'transfert_depot', // transfert vers un autre dépôt
                'reception_depot_intermediaire', // réception au dépôt intermédiaire
                'en_cours_livraison', // en cours de livraison finale
                'livre', // livré au destinataire
                'retour'
            ]);
            
            // Localisation
            $table->foreignId('depot_depart_id')->nullable()->constrained('depots')->nullOnDelete();
            $table->foreignId('depot_arrivee_id')->nullable()->constrained('depots')->nullOnDelete();
            
            // Qui a fait l'action
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete(); // admin ou livreur
            $table->foreignId('livreur_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('camion_id')->nullable()->constrained('camions')->nullOnDelete();
            
            // Photos de preuve
            $table->json('photos')->nullable(); // array de chemins de photos
            
            // Notes et commentaires
            $table->text('commentaire')->nullable();
            $table->string('statut')->default('termine');
            
            // Géolocalisation
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            
            $table->timestamp('date_etape');
            $table->timestamps();
            
            $table->index('colis_id');
            $table->index('type');
            $table->index('date_etape');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('etapes');
    }
};
