<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('colis', function (Blueprint $table) {
            $table->id();
            $table->string('code_colis')->unique(); // ID Colis unique
            
            // Informations produit
            $table->string('nom');
            $table->text('description')->nullable();
            $table->decimal('poids', 10, 2); // poids réel en kg
            $table->decimal('longueur', 10, 2)->nullable(); // cm
            $table->decimal('largeur', 10, 2)->nullable(); // cm
            $table->decimal('hauteur', 10, 2)->nullable(); // cm
            $table->decimal('poids_volumetrique', 10, 2)->nullable(); // calculé
            
            // Expéditeur
            $table->string('expediteur_nom');
            $table->string('expediteur_email');
            $table->string('expediteur_phone');
            $table->string('expediteur_societe')->nullable();
            $table->string('expediteur_matricule_fiscale')->nullable();
            
            // Destinataire
            $table->string('destinataire_nom');
            $table->string('destinataire_prenom');
            $table->string('destinataire_phone');
            $table->string('destinataire_adresse');
            $table->string('destinataire_ville');
            $table->string('destinataire_cin_3_derniers_chiffres')->nullable();
            
            // Localisation
            $table->foreignId('depot_source_id')->nullable()->constrained('depots')->nullOnDelete();
            $table->foreignId('depot_actuel_id')->nullable()->constrained('depots')->nullOnDelete();
            $table->foreignId('depot_destination_id')->nullable()->constrained('depots')->nullOnDelete();
            
            // Affectation
            $table->foreignId('admin_id')->constrained('users')->cascadeOnDelete(); // créateur
            $table->foreignId('camion_id')->nullable()->constrained('camions')->nullOnDelete();
            $table->foreignId('livreur_id')->nullable()->constrained('users')->nullOnDelete();
            
            // Statut
            $table->enum('statut', [
                'en_attente', // au dépôt, pas encore affecté
                'affecte', // affecté à une flotte
                'en_transit', // en cours de livraison
                'en_depot', // arrivé à un dépôt intermédiaire
                'livre', // livré au destinataire
                'retour', // retour à l'expéditeur
                'annule'
            ])->default('en_attente');
            
            $table->string('qr_code')->nullable();
            $table->timestamp('date_livraison')->nullable();
            $table->timestamps();
            
            // Index pour recherche rapide
            $table->index('code_colis');
            $table->index('statut');
            $table->index(['expediteur_email', 'destinataire_phone']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('colis');
    }
};
