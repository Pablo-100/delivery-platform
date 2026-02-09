<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Produit;

echo "=== TABLE PRODUITS ===\n\n";
echo "Total: " . Produit::count() . " produits\n\n";

$produits = Produit::orderBy('id', 'desc')->take(10)->get();
foreach ($produits as $p) {
    echo "ID {$p->id}: {$p->nom} | Statut: {$p->statut} | Livreur: {$p->livreur_id} | Destination: {$p->destination}\n";
}

echo "\n=== PRODUITS LIVRÉS ===\n";
$livres = Produit::where('statut', 'livre')->orderBy('updated_at', 'desc')->take(5)->get();
foreach ($livres as $p) {
    echo "ID {$p->id}: {$p->nom} | Livreur: {$p->livreur_id} | Livré: {$p->updated_at}\n";
}
