<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$cols = DB::select("SHOW COLUMNS FROM produits WHERE Field = 'livreur_id'");
if (count($cols) > 0) {
    echo "✅ Colonne livreur_id EXISTE dans produits\n";
} else {
    echo "❌ Colonne livreur_id MANQUE dans produits\n";
    echo "→ Création de la migration...\n";
}

$cols2 = DB::select("SHOW COLUMNS FROM produits");
echo "\nColonnes de la table produits:\n";
foreach ($cols2 as $col) {
    echo "- {$col->Field} ({$col->Type})\n";
}
