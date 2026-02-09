<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Camion;
use App\Models\Colis;
use App\Models\HistoriqueLivreur;
use Carbon\Carbon;

class GenerateHistoryTestData extends Command
{
    protected $signature = 'history:generate-test-data';
    protected $description = 'Génère des données de test pour l\'historique des livreurs et camions';

    public function handle()
    {
        $this->info('🚀 Génération des données de test pour l\'historique...');

        // Trouver un livreur
        $livreur = User::where('role', 'livreur')->first();
        if (!$livreur) {
            $this->error('❌ Aucun livreur trouvé. Créez d\'abord un livreur.');
            return 1;
        }

        // Trouver un camion
        $camion = Camion::first();
        if (!$camion) {
            $this->error('❌ Aucun camion trouvé. Créez d\'abord un camion.');
            return 1;
        }

        // Créer des colis de test si nécessaire
        $this->info('📦 Création de colis de test...');
        $colisIds = [];
        
        // Utiliser des colis existants si possible
        $existingColis = Colis::where('statut', 'livre')->take(5)->get();
        if ($existingColis->count() >= 3) {
            $this->info('✅ Utilisation de colis existants...');
            $colisIds = $existingColis->pluck('id')->toArray();
        } else {
            // Créer des colis sans générer le QR Code (désactiver les événements)
            for ($i = 1; $i <= 5; $i++) {
                $colis = new Colis([
                    'code_colis' => 'TEST-' . time() . '-' . $i,
                    'nom' => 'Colis Test ' . $i,
                    'description' => 'Colis de test pour l\'historique',
                    'poids' => rand(1, 20),
                    'expediteur_nom' => 'Expéditeur Test',
                    'expediteur_email' => 'expediteur' . $i . '@test.com',
                    'expediteur_phone' => '12345678',
                    'destinataire_nom' => 'Destinataire Test ' . $i,
                    'destinataire_prenom' => 'Test',
                    'destinataire_email' => 'destinataire' . $i . '@test.com',
                    'destinataire_phone' => '87654321',
                    'destinataire_ville' => ['Tunis', 'Sousse', 'Sfax', 'Bizerte', 'Nabeul'][rand(0, 4)],
                    'destinataire_adresse' => 'Adresse Test ' . $i,
                    'statut' => 'livre',
                    'livreur_id' => $livreur->id,
                    'date_livraison' => now()->subDays(rand(1, 7)),
                    'admin_id' => User::where('role', 'admin')->first()?->id,
                    'qr_code' => 'qrcodes/test_' . $i . '.png', // QR code factice
                ]);
                $colis->saveQuietly(); // Sauvegarder sans déclencher les événements
                $colisIds[] = $colis->id;
            }
        }

        // Créer 3 tournées historiques
        $this->info('📋 Création de tournées historiques...');
        
        for ($j = 1; $j <= 3; $j++) {
            $dateDebut = now()->subDays(rand(1, 14));
            $dateFin = (clone $dateDebut)->addHours(rand(2, 6))->addMinutes(rand(0, 59));
            
            $tourneeColisIds = array_slice($colisIds, ($j-1) * 2, 2);
            
            $historique = HistoriqueLivreur::create([
                'livreur_id' => $livreur->id,
                'camion_id' => $camion->id,
                'tournee_code' => 'TOUR-TEST-' . $dateDebut->format('YmdHis'),
                'heure_debut' => $dateDebut,
                'heure_fin' => $dateFin,
                'colis_ids' => $tourneeColisIds,
                'nombre_colis' => count($tourneeColisIds),
                'colis_livres' => count($tourneeColisIds),
                'colis_en_cours' => 0,
                'depots_visites' => ['Tunis', 'Sousse', 'Sfax'],
                'distance_km' => rand(50, 200),
                'statut' => 'termine',
                'notes' => 'Tournée de test n°' . $j,
            ]);
            
            $this->line("✅ Tournée {$j} créée: {$historique->tournee_code}");
        }

        $this->info('');
        $this->info('✨ Données de test générées avec succès!');
        $this->info("👷 Livreur: {$livreur->nom} {$livreur->prenom} (ID: {$livreur->id})");
        $this->info("🚛 Camion: {$camion->immatriculation} (ID: {$camion->id})");
        $this->info('');
        $this->info('🔗 Accès aux historiques:');
        $this->line("   Livreur: /super-admin/livreur/{$livreur->id}/history");
        $this->line("   Camion: /super-admin/camion/{$camion->id}/history");
        
        return 0;
    }
}
