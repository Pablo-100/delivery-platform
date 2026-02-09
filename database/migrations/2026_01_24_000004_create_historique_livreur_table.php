<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('historique_livreur', function (Blueprint $table) {
            $table->id();
            $table->foreignId('livreur_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('camion_id')->nullable()->constrained('camions')->nullOnDelete();
            
            // Voyage/tournée
            $table->string('tournee_code')->nullable();
            $table->timestamp('heure_debut');
            $table->timestamp('heure_fin')->nullable();
            
            // Colis transportés
            $table->json('colis_ids')->nullable(); // array des IDs de colis
            $table->integer('nombre_colis')->default(0);
            $table->integer('colis_livres')->default(0);
            $table->integer('colis_en_cours')->default(0);
            
            // Dépôts visités
            $table->json('depots_visites')->nullable();
            
            // Distance et performances
            $table->decimal('distance_km', 10, 2)->nullable();
            $table->enum('statut', ['en_cours', 'termine', 'annule'])->default('en_cours');
            
            $table->text('notes')->nullable();
            $table->timestamps();
            
            $table->index('livreur_id');
            $table->index('tournee_code');
            $table->index(['heure_debut', 'heure_fin']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('historique_livreur');
    }
};
