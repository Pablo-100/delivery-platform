<?php

namespace App\Services;

use App\Models\HistoriqueLivreur;
use App\Models\User;
use App\Models\Camion;

class HistoriqueService
{
    /**
     * Enregistre une livraison dans l'historique
     * 
     * @param int $livreurId - ID du livreur
     * @param int|null $camionId - ID du camion (optionnel, prend celui du livreur)
     * @param int|array $colisIds - ID(s) du/des colis
     * @param string $destination - Nom de la destination
     * @param string $type - 'colis' ou 'produit'
     */
    public static function enregistrerLivraison(
        int $livreurId, 
        ?int $camionId = null, 
        $colisIds = [], 
        string $destination = '',
        string $type = 'produit'
    ): ?HistoriqueLivreur {
        
        $livreur = User::find($livreurId);
        if (!$livreur) {
            return null;
        }

        // Récupérer le camion si pas fourni
        if (!$camionId) {
            $camionId = $livreur->camion_id;
        }

        // Normaliser les IDs
        if (!is_array($colisIds)) {
            $colisIds = [$colisIds];
        }

        // Chercher une tournée active pour ce livreur aujourd'hui
        $tourneeActive = HistoriqueLivreur::where('livreur_id', $livreurId)
            ->where('statut', 'en_cours')
            ->whereDate('heure_debut', today())
            ->first();

        if ($tourneeActive) {
            // Mettre à jour la tournée existante
            $existingColisIds = $tourneeActive->colis_ids ?? [];
            $existingDestinations = $tourneeActive->depots_visites ?? [];

            // Ajouter les nouveaux colis
            foreach ($colisIds as $id) {
                if (!in_array($id, $existingColisIds)) {
                    $existingColisIds[] = $id;
                }
            }

            // Ajouter la destination
            if ($destination && !in_array($destination, $existingDestinations)) {
                $existingDestinations[] = $destination;
            }

            $tourneeActive->update([
                'colis_ids' => $existingColisIds,
                'nombre_colis' => count($existingColisIds),
                'colis_livres' => $tourneeActive->colis_livres + count($colisIds),
                'depots_visites' => $existingDestinations,
                'distance_km' => ($tourneeActive->distance_km ?? 0) + rand(5, 20),
            ]);

            return $tourneeActive;
        }

        // Créer une nouvelle tournée
        $tournee = HistoriqueLivreur::create([
            'livreur_id' => $livreurId,
            'camion_id' => $camionId,
            'tournee_code' => 'TOUR-' . date('YmdHis') . '-' . $livreurId,
            'heure_debut' => now(),
            'heure_fin' => null,
            'colis_ids' => $colisIds,
            'nombre_colis' => count($colisIds),
            'colis_livres' => count($colisIds),
            'colis_en_cours' => 0,
            'depots_visites' => $destination ? [$destination] : [],
            'distance_km' => rand(10, 50),
            'statut' => 'en_cours',
            'notes' => "Tournée créée automatiquement ({$type})",
        ]);

        return $tournee;
    }

    /**
     * Termine la tournée active d'un livreur
     */
    public static function terminerTournee(int $livreurId): ?HistoriqueLivreur
    {
        $tournee = HistoriqueLivreur::where('livreur_id', $livreurId)
            ->where('statut', 'en_cours')
            ->first();

        if ($tournee) {
            $tournee->update([
                'heure_fin' => now(),
                'statut' => 'termine',
            ]);
        }

        return $tournee;
    }
}
