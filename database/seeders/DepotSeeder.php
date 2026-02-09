<?php

namespace Database\Seeders;

use App\Models\Depot;
use Illuminate\Database\Seeder;

class DepotSeeder extends Seeder
{
    public function run(): void
    {
        $depots = [
            [
                'code' => 'A',
                'nom' => 'Dépôt Central A',
                'adresse' => 'Rue de la Liberté',
                'ville' => 'Tunis',
                'phone' => '71123456',
                'actif' => true,
            ],
            [
                'code' => 'B',
                'nom' => 'Dépôt Centre B',
                'adresse' => 'Avenue Habib Bourguiba',
                'ville' => 'Sfax',
                'phone' => '74123456',
                'actif' => true,
            ],
            [
                'code' => 'C',
                'nom' => 'Dépôt Nord C',
                'adresse' => 'Route de Bizerte',
                'ville' => 'Ariana',
                'phone' => '71234567',
                'actif' => true,
            ],
            [
                'code' => 'D',
                'nom' => 'Dépôt Sud D',
                'adresse' => 'Avenue de la République',
                'ville' => 'Sousse',
                'phone' => '73123456',
                'actif' => true,
            ],
        ];

        foreach ($depots as $depot) {
            Depot::create($depot);
        }
    }
}
