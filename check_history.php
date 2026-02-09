<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\HistoriqueLivreur;
use App\Models\User;
use App\Models\Camion;

echo "=== VÉRIFICATION HISTORIQUE ===\n\n";

$historiques = HistoriqueLivreur::all();
echo "Total tournées: " . $historiques->count() . "\n\n";

foreach ($historiques as $h) {
    $livreur = User::find($h->livreur_id);
    $camion = Camion::find($h->camion_id);
    
    echo "--- Tournée #{$h->id} ---\n";
    echo "Code: {$h->tournee_code}\n";
    echo "Livreur ID: {$h->livreur_id} → " . ($livreur ? "{$livreur->nom} {$livreur->prenom}" : "INTROUVABLE") . "\n";
    echo "Camion ID: {$h->camion_id} → " . ($camion ? $camion->immatriculation : "INTROUVABLE") . "\n";
    echo "Colis: {$h->nombre_colis} | Livrés: {$h->colis_livres} | En cours: {$h->colis_en_cours}\n";
    echo "Statut: {$h->statut}\n";
    echo "Destinations: " . json_encode($h->depots_visites) . "\n";
    echo "Colis IDs: " . json_encode($h->colis_ids) . "\n";
    echo "\n";
}

echo "\n=== TOUS LES LIVREURS ===\n\n";
$livreurs = User::where('role', 'livreur')->get();
foreach ($livreurs as $l) {
    $tournees = HistoriqueLivreur::where('livreur_id', $l->id)->count();
    echo "ID {$l->id}: {$l->nom} {$l->prenom} ({$l->email}) → {$tournees} tournées\n";
}

echo "\n=== TOUS LES CAMIONS ===\n\n";
$camions = Camion::all();
foreach ($camions as $c) {
    $tournees = HistoriqueLivreur::where('camion_id', $c->id)->count();
    echo "ID {$c->id}: {$c->immatriculation} → {$tournees} tournées\n";
}
