<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Super Admin
        $superAdmin = User::create([
            'nom' => 'Super',
            'prenom' => 'Admin',
            'email' => 'superadmin@delivery.com',
            'password' => bcrypt('password'),
            'role' => 'super_admin',
        ]);

        // Admin 1
        $admin1 = User::create([
            'nom' => 'Dupont',
            'prenom' => 'Jean',
            'email' => 'admin@delivery.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
            'company_info' => 'Transport Express SARL',
        ]);

        // Admin 2
        $admin2 = User::create([
            'nom' => 'Martin',
            'prenom' => 'Sophie',
            'email' => 'admin2@delivery.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
            'company_info' => 'Logistique Pro',
        ]);

        // Camions pour Admin 1
        $camion1 = \App\Models\Camion::create([
            'immatriculation' => 'TN-1234-A',
            'modele' => 'Mercedes Actros',
            'capacite' => 20.5,
            'admin_id' => $admin1->id,
            'statut' => 'disponible',
        ]);

        $camion2 = \App\Models\Camion::create([
            'immatriculation' => 'TN-5678-B',
            'modele' => 'Volvo FH16',
            'capacite' => 25.0,
            'admin_id' => $admin1->id,
            'statut' => 'disponible',
        ]);

        // Camion pour Admin 2
        $camion3 = \App\Models\Camion::create([
            'immatriculation' => 'TN-9012-C',
            'modele' => 'Scania R500',
            'capacite' => 22.0,
            'admin_id' => $admin2->id,
            'statut' => 'disponible',
        ]);

        // Livreurs
        $livreur1 = User::create([
            'nom' => 'Ben Ali',
            'prenom' => 'Mohamed',
            'email' => 'livreur1@delivery.com',
            'password' => bcrypt('password'),
            'role' => 'livreur',
            'camion_id' => $camion1->id,
        ]);

        $livreur2 = User::create([
            'nom' => 'Trabelsi',
            'prenom' => 'Ahmed',
            'email' => 'livreur2@delivery.com',
            'password' => bcrypt('password'),
            'role' => 'livreur',
            'camion_id' => $camion2->id,
        ]);

        // Assigner les livreurs aux camions
        $camion1->update(['livreur_id' => $livreur1->id, 'statut' => 'en_service']);
        $camion2->update(['livreur_id' => $livreur2->id, 'statut' => 'en_service']);

        // Produits pour Admin 1
        $produit1 = \App\Models\Produit::create([
            'nom' => 'Colis Électronique',
            'description' => 'Ordinateurs portables - Fragile',
            'poids' => 15.5,
            'volume' => 0.8,
            'source' => 'Tunis',
            'destination' => 'Sfax',
            'expediteur_email' => 'exp1@company.com',
            'expediteur_phone' => '+216 20 123 456',
            'expediteur_societe' => 'Tech Solutions',
            'expediteur_matricule_fiscale' => '1234567A',
            'destinataire_nom' => 'Gharbi',
            'destinataire_prenom' => 'Fatma',
            'destinataire_cin_3_derniers_chiffres' => '789',
            'qr_code' => \App\Models\Produit::generateUniqueQRCode(),
            'statut' => 'valide',
            'camion_id' => $camion1->id,
            'admin_id' => $admin1->id,
        ]);

        $produit2 = \App\Models\Produit::create([
            'nom' => 'Matériel Médical',
            'description' => 'Équipements hospitaliers',
            'poids' => 45.0,
            'volume' => 2.5,
            'source' => 'Sousse',
            'destination' => 'Monastir',
            'expediteur_email' => 'medical@hospital.tn',
            'expediteur_phone' => '+216 73 456 789',
            'expediteur_societe' => 'MediCare',
            'expediteur_matricule_fiscale' => '9876543B',
            'destinataire_nom' => 'Haddad',
            'destinataire_prenom' => 'Karim',
            'destinataire_cin_3_derniers_chiffres' => '456',
            'qr_code' => \App\Models\Produit::generateUniqueQRCode(),
            'statut' => 'prepare',
            'camion_id' => $camion1->id,
            'admin_id' => $admin1->id,
        ]);

        $produit3 = \App\Models\Produit::create([
            'nom' => 'Pièces Automobiles',
            'description' => 'Pièces de rechange',
            'poids' => 120.0,
            'volume' => 5.0,
            'source' => 'Bizerte',
            'destination' => 'Tunis',
            'expediteur_email' => 'auto@parts.tn',
            'expediteur_phone' => '+216 72 987 654',
            'expediteur_societe' => 'AutoParts TN',
            'destinataire_nom' => 'Mejri',
            'destinataire_prenom' => 'Sami',
            'destinataire_cin_3_derniers_chiffres' => '123',
            'qr_code' => \App\Models\Produit::generateUniqueQRCode(),
            'statut' => 'en_route',
            'camion_id' => $camion2->id,
            'admin_id' => $admin1->id,
        ]);

        $produit4 = \App\Models\Produit::create([
            'nom' => 'Produits Alimentaires',
            'description' => 'Denrées périssables',
            'poids' => 200.0,
            'volume' => 8.0,
            'source' => 'Nabeul',
            'destination' => 'Hammamet',
            'expediteur_email' => 'food@export.tn',
            'expediteur_phone' => '+216 72 111 222',
            'expediteur_societe' => 'FoodExport',
            'destinataire_nom' => 'Sassi',
            'destinataire_prenom' => 'Leila',
            'destinataire_cin_3_derniers_chiffres' => '999',
            'qr_code' => \App\Models\Produit::generateUniqueQRCode(),
            'statut' => 'livre',
            'camion_id' => null,
            'admin_id' => $admin2->id,
        ]);

        echo "✓ Données de test créées avec succès!\n";
        echo "  - Super Admin: superadmin@delivery.com / password\n";
        echo "  - Admin 1: admin@delivery.com / password\n";
        echo "  - Admin 2: admin2@delivery.com / password\n";
        echo "  - Livreur 1: livreur1@delivery.com / password\n";
        echo "  - Livreur 2: livreur2@delivery.com / password\n";
    }
}
