<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Colis;

echo "=== DERNIERS COLIS ===\n\n";

$colis = Colis::orderBy('id', 'desc')->take(10)->get();
foreach ($colis as $c) {
    echo "ID {$c->id}: {$c->nom} | Statut: {$c->statut} | Livreur: {$c->livreur_id} | Créé: {$c->created_at}\n";
}

echo "\n=== COLIS LIVRÉS ===\n\n";
$livres = Colis::where('statut', 'livre')->orderBy('updated_at', 'desc')->take(10)->get();
foreach ($livres as $c) {
    echo "ID {$c->id}: {$c->nom} | Livreur: {$c->livreur_id} | Destination: {$c->destinataire_ville} | Livré: {$c->updated_at}\n";
}
