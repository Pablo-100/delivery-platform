<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Depot;
use Illuminate\Database\Seeder;

class AddNewAdminsSeeder extends Seeder
{
    public function run(): void
    {
        // Admin 3 pour Dépôt C
        $admin3 = User::firstOrCreate(
            ['email' => 'admin3@delivery.com'],
            [
                'nom' => 'Benali',
                'prenom' => 'Ahmed',
                'password' => bcrypt('password'),
                'role' => 'admin',
                'company_info' => 'Dépôt Nord C',
            ]
        );

        // Admin 4 pour Dépôt D
        $admin4 = User::firstOrCreate(
            ['email' => 'admin4@delivery.com'],
            [
                'nom' => 'Trabelsi',
                'prenom' => 'Sami',
                'password' => bcrypt('password'),
                'role' => 'admin',
                'company_info' => 'Dépôt Sud D',
            ]
        );

        // Assigner les admins aux dépôts
        Depot::where('code', 'C')->update(['admin_id' => $admin3->id]);
        Depot::where('code', 'D')->update(['admin_id' => $admin4->id]);

        // Aussi assigner les anciens admins (si pas déjà fait)
        $admin1 = User::where('email', 'admin@delivery.com')->first();
        $admin2 = User::where('email', 'admin2@delivery.com')->first();
        
        if ($admin1) {
            Depot::where('code', 'A')->update(['admin_id' => $admin1->id]);
        }
        if ($admin2) {
            Depot::where('code', 'B')->update(['admin_id' => $admin2->id]);
        }

        echo "✅ Admins créés et assignés aux dépôts!\n";
        echo "   admin3@delivery.com -> Dépôt C\n";
        echo "   admin4@delivery.com -> Dépôt D\n";
    }
}
