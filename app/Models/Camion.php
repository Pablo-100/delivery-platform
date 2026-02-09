<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Camion extends Model
{
    protected $fillable = [
        'immatriculation',
        'modele',
        'capacite',
        'admin_id',
        'livreur_id',
        'statut',
    ];

    /**
     * Relations
     */
    
    // Un camion appartient à un admin (propriétaire)
    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    // Un camion est conduit par un livreur
    public function livreur()
    {
        return $this->belongsTo(User::class, 'livreur_id');
    }

    // Relation inverse fiable : Chercher le user qui a ce camion_id
    public function conducteur()
    {
        return $this->hasOne(User::class, 'camion_id');
    }

    // Un camion a plusieurs produits
    public function produits()
    {
        return $this->belongsToMany(Produit::class, 'camion_produit')
                    ->withTimestamps();
    }

    // Historique des tournées de ce camion
    public function historiques()
    {
        return $this->hasMany(HistoriqueLivreur::class)->orderBy('heure_debut', 'desc');
    }

    /**
     * Récupérer l'historique complet du camion
     * Avec tous les livreurs, dates, destinations, produits
     */
    public function getHistoriqueCompletCamion()
    {
        return HistoriqueLivreur::where('camion_id', $this->id)
            ->with(['livreur'])
            ->orderBy('heure_debut', 'desc')
            ->get()
            ->map(function($historique) {
                return [
                    'id' => $historique->id,
                    'tournee_code' => $historique->tournee_code,
                    'livreur' => [
                        'id' => $historique->livreur?->id,
                        'nom_complet' => $historique->livreur?->nom . ' ' . $historique->livreur?->prenom,
                        'email' => $historique->livreur?->email,
                    ],
                    'periode' => [
                        'debut' => $historique->heure_debut,
                        'fin' => $historique->heure_fin,
                        'duree' => $historique->getDureeFormatee(),
                    ],
                    'statistiques' => [
                        'total_colis' => $historique->nombre_colis,
                        'colis_livres' => $historique->colis_livres,
                        'colis_en_cours' => $historique->colis_en_cours,
                        'distance_km' => $historique->distance_km,
                    ],
                    'destinations' => $historique->depots_visites ?? [],
                    'destinations_avec_stats' => $historique->getDestinationsWithCount(),
                    'produits' => $historique->getProduitsWithDetails(),
                    'statut' => $historique->statut,
                ];
            });
    }

    /**
     * Statistiques pour un camion
     */
    public function getStatsCamion()
    {
        $historiques = $this->historiques;
        
        return [
            'total_tournees' => $historiques->count(),
            'total_colis_transportes' => $historiques->sum('nombre_colis'),
            'total_colis_livres' => $historiques->sum('colis_livres'),
            'total_distance_km' => $historiques->sum('distance_km'),
            'livreurs_differents' => $historiques->pluck('livreur_id')->unique()->filter()->count(),
            'taux_reussite' => $historiques->sum('nombre_colis') > 0 
                ? round(($historiques->sum('colis_livres') / $historiques->sum('nombre_colis')) * 100, 2)
                : 0,
            'derniere_tournee' => $historiques->first()?->heure_debut,
        ];
    }
}
