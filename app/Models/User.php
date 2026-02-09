<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'nom',
        'prenom',
        'email',
        'password',
        'role',
        'camion_id',
        'company_info',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Relations
     */
    
    // Un livreur appartient à un camion
    public function camion()
    {
        return $this->belongsTo(Camion::class);
    }

    // Un admin possède plusieurs camions
    public function camionsOwned()
    {
        return $this->hasMany(Camion::class, 'admin_id');
    }

    // Un admin crée plusieurs produits
    public function produits()
    {
        return $this->hasMany(Produit::class, 'admin_id');
    }

    // Un admin crée plusieurs colis
    public function colis()
    {
        return $this->hasMany(Colis::class, 'admin_id');
    }

    // Un livreur livre plusieurs colis
    public function colisLivres()
    {
        return $this->hasMany(Colis::class, 'livreur_id');
    }

    // Historique des tournées du livreur
    public function historiqueTournees()
    {
        return $this->hasMany(HistoriqueLivreur::class, 'livreur_id');
    }

    // Dépôts gérés par l'admin
    public function depotsGeres()
    {
        return $this->hasMany(Depot::class, 'admin_id');
    }

    /**
     * Méthodes utilitaires
     */
    
    public function isSuperAdmin()
    {
        return $this->role === 'super_admin';
    }

    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isLivreur()
    {
        return $this->role === 'livreur';
    }

    // Accessor pour la compatibilité avec Breeze (qui utilise 'name')
    public function getNameAttribute()
    {
        return "{$this->prenom} {$this->nom}";
    }

    /**
     * Récupérer l'historique complet pour un livreur
     * Avec tous les camions conduits, dates, destinations, produits
     */
    public function getHistoriqueCompletLivreur()
    {
        if (!$this->isLivreur()) {
            return collect();
        }

        return HistoriqueLivreur::where('livreur_id', $this->id)
            ->with(['camion', 'camion.admin'])
            ->orderBy('heure_debut', 'desc')
            ->get()
            ->map(function($historique) {
                $produits = $historique->getProduits();
                return [
                    'id' => $historique->id,
                    'tournee_code' => $historique->tournee_code,
                    'camion' => [
                        'id' => $historique->camion?->id,
                        'immatriculation' => $historique->camion?->immatriculation,
                        'modele' => $historique->camion?->modele,
                        'proprietaire' => $historique->camion?->admin?->nom . ' ' . $historique->camion?->admin?->prenom,
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
                    'produits' => $historique->getProduitsWithDetails(),
                    'statut' => $historique->statut,
                ];
            });
    }

    /**
     * Statistiques pour un livreur
     */
    public function getStatsLivreur()
    {
        if (!$this->isLivreur()) {
            return null;
        }

        $historiques = $this->historiqueTournees;
        
        return [
            'total_tournees' => $historiques->count(),
            'total_colis_transportes' => $historiques->sum('nombre_colis'),
            'total_colis_livres' => $historiques->sum('colis_livres'),
            'total_distance_km' => $historiques->sum('distance_km'),
            'camions_differents' => $historiques->pluck('camion_id')->unique()->count(),
            'taux_reussite' => $historiques->sum('nombre_colis') > 0 
                ? round(($historiques->sum('colis_livres') / $historiques->sum('nombre_colis')) * 100, 2)
                : 0,
        ];
    }
}

