<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Services\HistoriqueService;
use App\Models\User;
use App\Models\Camion;
use App\Models\Produit;
use App\Models\HistoriqueLivreur;

echo "=== TEST DU SYSTÈME D'HISTORIQUE ===\n\n";

// Trouver un livreur avec camion
$livreur = User::where('role', 'livreur')->whereNotNull('camion_id')->first();

if (!$livreur) {
    // Assigner un camion au premier livreur
    $livreur = User::where('role', 'livreur')->first();
    $camion = Camion::first();
    if ($livreur && $camion) {
        $livreur->update(['camion_id' => $camion->id]);
        echo "✅ Camion {$camion->immatriculation} assigné à {$livreur->nom}\n";
    }
}

if (!$livreur) {
    echo "❌ Aucun livreur trouvé!\n";
    exit(1);
}

echo "👷 Livreur: {$livreur->nom} {$livreur->prenom} (ID: {$livreur->id})\n";
echo "🚛 Camion: " . ($livreur->camion ? $livreur->camion->immatriculation : 'Aucun') . "\n\n";

// Trouver un produit non-livré ou en créer un
$produit = Produit::where('statut', '!=', 'livre')->first();
if (!$produit) {
    echo "Tous les produits sont déjà livrés. Utilisation d'un produit existant...\n";
    $produit = Produit::first();
}

if ($produit) {
    echo "📦 Produit: {$produit->nom} (ID: {$produit->id})\n";
    echo "   Destination: {$produit->destination}\n\n";
    
    // Simuler une livraison
    echo "🔄 Simulation d'une livraison...\n";
    
    $historique = HistoriqueService::enregistrerLivraison(
        livreurId: $livreur->id,
        camionId: $livreur->camion_id,
        colisIds: $produit->id,
        destination: $produit->destination ?? $produit->destinataire_ville ?? 'Test Destination',
        type: 'produit'
    );
    
    if ($historique) {
        echo "✅ Historique créé/mis à jour!\n";
        echo "   → Tournée: {$historique->tournee_code}\n";
        echo "   → Colis: {$historique->nombre_colis}\n";
        echo "   → Livrés: {$historique->colis_livres}\n";
        echo "   → Destinations: " . json_encode($historique->depots_visites) . "\n";
    } else {
        echo "❌ Échec de création de l'historique\n";
    }
}

echo "\n=== ÉTAT ACTUEL ===\n\n";

$historiques = HistoriqueLivreur::with(['livreur', 'camion'])->get();
echo "Total tournées: " . $historiques->count() . "\n\n";

foreach ($historiques as $h) {
    echo "📋 {$h->tournee_code}\n";
    echo "   Livreur: " . ($h->livreur ? $h->livreur->nom : 'N/A') . "\n";
    echo "   Camion: " . ($h->camion ? $h->camion->immatriculation : 'N/A') . "\n";
    echo "   Colis: {$h->nombre_colis} (livrés: {$h->colis_livres})\n";
    echo "   Destinations: " . json_encode($h->depots_visites) . "\n\n";
}
