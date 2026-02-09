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
        Schema::create('produits', function (Blueprint $table) {
            $table->id();
            $table->string('nom');
            $table->text('description')->nullable();
            $table->decimal('poids', 10, 2)->nullable(); // en kg
            $table->decimal('volume', 10, 2)->nullable(); // en m³
            
            // Localisation
            $table->string('source'); // Lieu de départ
            $table->string('destination'); // Lieu d'arrivée
            
            // Informations expéditeur
            $table->string('expediteur_email');
            $table->string('expediteur_phone');
            $table->string('expediteur_societe')->nullable();
            $table->string('expediteur_matricule_fiscale')->nullable();
            
            // Informations destinataire
            $table->string('destinataire_nom');
            $table->string('destinataire_prenom');
            $table->string('destinataire_cin_3_derniers_chiffres', 3);
            
            // QR Code et statut
            $table->string('qr_code')->unique(); // UUID unique
            $table->enum('statut', ['valide', 'prepare', 'en_route', 'livre'])->default('valide');
            
            // Relations
            $table->foreignId('camion_id')->nullable()->constrained('camions')->onDelete('set null');
            $table->foreignId('admin_id')->constrained('users')->onDelete('cascade'); // Créateur
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('produits');
    }
};
