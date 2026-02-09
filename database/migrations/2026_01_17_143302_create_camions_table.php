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
        Schema::create('camions', function (Blueprint $table) {
            $table->id();
            $table->string('immatriculation')->unique();
            $table->string('modele');
            $table->decimal('capacite', 10, 2); // Capacité en tonnes ou m³
            $table->foreignId('admin_id')->constrained('users')->onDelete('cascade'); // Propriétaire
            $table->foreignId('livreur_id')->nullable()->constrained('users')->onDelete('set null'); // Conducteur assigné
            $table->enum('statut', ['disponible', 'en_service', 'maintenance'])->default('disponible');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('camions');
    }
};
