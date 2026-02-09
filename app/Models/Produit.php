<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Produit extends Model
{
    protected $fillable = [
        'nom',
        'description',
        'poids',
        'volume',
        // Dimensions et poids volumétrique
        'longueur',
        'largeur',
        'hauteur',
        'poids_volumetrique',
        // Dépôts
        'depot_source_id',
        'depot_destination_id',
        'depot_actuel_id',
        // Poids facturable (choisi par admin)
        'poids_facturable',
        // Trajet
        'source',
        'destination',
        // Expéditeur
        'expediteur_nom',
        'expediteur_email',
        'expediteur_phone',
        'expediteur_societe',
        'expediteur_matricule_fiscale',
        // Destinataire
        'destinataire_nom',
        'destinataire_prenom',
        'destinataire_phone',
        'destinataire_adresse',
        'destinataire_ville',
        'destinataire_cin_3_derniers_chiffres',
        // Statut
        'qr_code',
        'statut',
        'statut_depot',
        'camion_id',
        'livreur_id', // Livreur qui a livré
        'admin_id',
        'photos_livraison',
    ];

    protected $casts = [
        'longueur' => 'decimal:2',
        'largeur' => 'decimal:2',
        'hauteur' => 'decimal:2',
        'poids_volumetrique' => 'decimal:2',
        'photos_livraison' => 'array',
    ];

    /**
     * Boot method for model events
     */
    protected static function boot()
    {
        parent::boot();

        // Calculer automatiquement le poids volumétrique avant la sauvegarde
        static::saving(function ($produit) {
            if ($produit->longueur && $produit->largeur && $produit->hauteur) {
                $produit->poids_volumetrique = ($produit->longueur * $produit->largeur * $produit->hauteur) / 5000;
            }
        });
    }

    /**
     * Relations
     */
    
    // Un produit appartient à un admin (créateur)
    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    // Livreur qui a livré le produit
    public function livreur()
    {
        return $this->belongsTo(User::class, 'livreur_id');
    }

    // Un produit peut être assigné à un camion
    public function camion()
    {
        return $this->belongsTo(Camion::class);
    }

    // Un produit peut être dans plusieurs camions (historique)
    public function camions()
    {
        return $this->belongsToMany(Camion::class, 'camion_produit')
                    ->withTimestamps();
    }

    // Dépôt source
    public function depotSource()
    {
        return $this->belongsTo(Depot::class, 'depot_source_id');
    }

    // Dépôt destination
    public function depotDestination()
    {
        return $this->belongsTo(Depot::class, 'depot_destination_id');
    }

    // Dépôt actuel
    public function depotActuel()
    {
        return $this->belongsTo(Depot::class, 'depot_actuel_id');
    }

    // Étapes de suivi (avec clé étrangère explicite)
    public function etapes()
    {
        return $this->hasMany(Etape::class, 'produit_id')->orderBy('created_at', 'desc');
    }

    /**
     * Méthodes utilitaires
     */
    
    // Générer un QR code unique
    public static function generateUniqueQRCode()
    {
        do {
            $qrCode = \Illuminate\Support\Str::uuid()->toString();
        } while (self::where('qr_code', $qrCode)->exists());
        
        return $qrCode;
    }

    // Obtenir le poids facturable (le plus grand entre poids réel et volumétrique)
    public function getPoidsFacturableAttribute()
    {
        return max($this->poids ?? 0, $this->poids_volumetrique ?? 0);
    }

    // Vérifier si le produit peut changer de statut
    public function canChangeStatusTo($newStatus)
    {
        $statusOrder = ['stockage', 'valide', 'prepare', 'en_route', 'livre'];
        $currentIndex = array_search($this->statut, $statusOrder);
        $newIndex = array_search($newStatus, $statusOrder);
        
        return $newIndex > $currentIndex;
    }

    // Ajouter une étape de suivi avec photo optionnelle
    public function ajouterEtape($statut, $description, $photos = [], $userId = null, $depotId = null)
    {
        return $this->etapes()->create([
            'statut' => $statut,
            'description' => $description,
            'photos' => $photos,
            'user_id' => $userId,
            'depot_id' => $depotId,
            'localisation' => null,
        ]);
    }
}
