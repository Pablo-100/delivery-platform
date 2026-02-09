<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HistoriqueLivreur extends Model
{
    protected $table = 'historique_livreur';

    protected $fillable = [
        'livreur_id',
        'camion_id',
        'tournee_code',
        'heure_debut',
        'heure_fin',
        'colis_ids',
        'nombre_colis',
        'colis_livres',
        'colis_en_cours',
        'depots_visites',
        'distance_km',
        'statut',
        'notes',
    ];

    protected $casts = [
        'heure_debut' => 'datetime',
        'heure_fin' => 'datetime',
        'colis_ids' => 'array',
        'depots_visites' => 'array',
    ];

    // Relations
    public function livreur()
    {
        return $this->belongsTo(User::class, 'livreur_id');
    }

    public function camion()
    {
        return $this->belongsTo(Camion::class);
    }

    // Méthodes utilitaires
    public function getDuree()
    {
        if (!$this->heure_fin) {
            return now()->diffInMinutes($this->heure_debut);
        }
        return $this->heure_fin->diffInMinutes($this->heure_debut);
    }

    // Récupérer les colis transportés
    public function getColis()
    {
        if (!$this->colis_ids) {
            return collect();
        }
        return Colis::whereIn('id', $this->colis_ids)->get();
    }

    // Récupérer les produits transportés (alias pour compatibilité)
    public function getProduits()
    {
        // Essayer d'abord avec Colis
        if (!$this->colis_ids) {
            return collect();
        }
        
        $colis = Colis::whereIn('id', $this->colis_ids)->get();
        if ($colis->count() > 0) {
            return $colis;
        }
        
        // Sinon essayer avec Produit
        return Produit::whereIn('id', $this->colis_ids)->get();
    }

    // Durée formatée
    public function getDureeFormatee()
    {
        $minutes = $this->getDuree();
        $heures = floor($minutes / 60);
        $mins = $minutes % 60;
        
        if ($heures > 0) {
            return "{$heures}h {$mins}min";
        }
        return "{$mins} min";
    }

    // Destinations visitées (extraites des produits)
    public function getDestinations()
    {
        $produits = $this->getProduits();
        return $produits->pluck('destination')->unique()->filter()->values();
    }

    // Récupérer tous les détails des produits avec dates de livraison
    public function getProduitsWithDetails()
    {
        if (!$this->colis_ids) {
            return collect();
        }
        
        // Essayer d'abord avec Colis
        $colis = Colis::whereIn('id', $this->colis_ids)
            ->select([
                'id', 'nom', 'destinataire_ville as destination', 'destinataire_nom', 
                'statut', 'updated_at', 'created_at', 'poids', 'date_livraison'
            ])
            ->get();
        
        if ($colis->count() > 0) {
            return $colis->map(function($item) {
                return [
                    'id' => $item->id,
                    'nom' => $item->nom,
                    'destination' => $item->destination,
                    'destinataire' => $item->destinataire_nom,
                    'ville' => $item->destination,
                    'statut' => $item->statut,
                    'poids' => $item->poids,
                    'date_livraison' => $item->statut === 'livre' ? $item->date_livraison : null,
                ];
            });
        }
        
        // Sinon essayer avec Produit
        $produits = Produit::whereIn('id', $this->colis_ids)
            ->select([
                'id', 'nom', 'destination', 'destinataire_nom', 'destinataire_ville',
                'statut', 'updated_at', 'created_at', 'poids', 'poids_facturable'
            ])
            ->get();
            
        return $produits->map(function($produit) {
            return [
                'id' => $produit->id,
                'nom' => $produit->nom,
                'destination' => $produit->destination,
                'destinataire' => $produit->destinataire_nom,
                'ville' => $produit->destinataire_ville,
                'statut' => $produit->statut,
                'poids' => $produit->poids_facturable ?? $produit->poids,
                'date_livraison' => $produit->statut === 'livre' ? $produit->updated_at : null,
            ];
        });
    }

    // Récupérer toutes les destinations uniques avec compteurs
    public function getDestinationsWithCount()
    {
        $produits = $this->getProduits();
        $destinations = [];
        
        foreach ($produits as $produit) {
            $dest = $produit->destination ?? $produit->destinataire_ville;
            if ($dest) {
                if (!isset($destinations[$dest])) {
                    $destinations[$dest] = [
                        'nom' => $dest,
                        'total' => 0,
                        'livres' => 0,
                        'en_cours' => 0,
                    ];
                }
                $destinations[$dest]['total']++;
                if ($produit->statut === 'livre') {
                    $destinations[$dest]['livres']++;
                } elseif (in_array($produit->statut, ['en_route', 'prepare'])) {
                    $destinations[$dest]['en_cours']++;
                }
            }
        }
        
        return collect($destinations)->values();
    }
}
