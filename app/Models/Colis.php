<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class Colis extends Model
{
    protected $fillable = [
        'code_colis',
        'nom',
        'description',
        'poids',
        'longueur',
        'largeur',
        'hauteur',
        'poids_volumetrique',
        'expediteur_nom',
        'expediteur_email',
        'expediteur_phone',
        'expediteur_societe',
        'expediteur_matricule_fiscale',
        'destinataire_nom',
        'destinataire_prenom',
        'destinataire_phone',
        'destinataire_adresse',
        'destinataire_ville',
        'destinataire_cin_3_derniers_chiffres',
        'depot_source_id',
        'depot_actuel_id',
        'depot_destination_id',
        'admin_id',
        'camion_id',
        'livreur_id',
        'statut',
        'qr_code',
        'date_livraison',
    ];

    protected $casts = [
        'date_livraison' => 'datetime',
    ];

    // Calcul automatique du poids volumétrique
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($colis) {
            if ($colis->longueur && $colis->largeur && $colis->hauteur) {
                $colis->poids_volumetrique = ($colis->longueur * $colis->largeur * $colis->hauteur) / 5000;
            }
        });

        static::created(function ($colis) {
            // Générer le code QR
            if (!$colis->qr_code) {
                $qrCodePath = 'qrcodes/colis_' . $colis->id . '.png';
                $fullPath = storage_path('app/public/' . $qrCodePath);
                
                // Créer le dossier si nécessaire
                if (!file_exists(dirname($fullPath))) {
                    mkdir(dirname($fullPath), 0755, true);
                }
                
                QrCode::format('png')->size(300)->generate($colis->code_colis, $fullPath);
                $colis->qr_code = $qrCodePath;
                $colis->saveQuietly();
            }
        });
    }

    // Relations
    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    public function camion()
    {
        return $this->belongsTo(Camion::class);
    }

    public function livreur()
    {
        return $this->belongsTo(User::class, 'livreur_id');
    }

    public function depotSource()
    {
        return $this->belongsTo(Depot::class, 'depot_source_id');
    }

    public function depotActuel()
    {
        return $this->belongsTo(Depot::class, 'depot_actuel_id');
    }

    public function depotDestination()
    {
        return $this->belongsTo(Depot::class, 'depot_destination_id');
    }

    public function etapes()
    {
        return $this->hasMany(Etape::class)->orderBy('date_etape', 'desc');
    }

    // Méthodes utilitaires
    public function getPoidsFacture()
    {
        // Retourne le poids le plus élevé entre poids réel et poids volumétrique
        return max($this->poids, $this->poids_volumetrique ?? 0);
    }

    public function getDerniereEtape()
    {
        return $this->etapes()->latest('date_etape')->first();
    }

    public function getPhotos()
    {
        return $this->etapes()->whereNotNull('photos')->get()->flatMap(function ($etape) {
            return $etape->photos;
        });
    }
}
