<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Etape extends Model
{
    protected $fillable = [
        'colis_id',
        'produit_id',
        'type',
        'depot_depart_id',
        'depot_arrivee_id',
        'depot_id',
        'user_id',
        'livreur_id',
        'camion_id',
        'photos',
        'commentaire',
        'description',
        'statut',
        'latitude',
        'longitude',
        'localisation',
        'date_etape',
    ];

    protected $casts = [
        'photos' => 'array',
        'date_etape' => 'datetime',
    ];

    // Relations
    public function colis()
    {
        return $this->belongsTo(Colis::class);
    }

    public function produit()
    {
        return $this->belongsTo(Produit::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function depot()
    {
        return $this->belongsTo(Depot::class);
    }

    public function livreur()
    {
        return $this->belongsTo(User::class, 'livreur_id');
    }

    public function camion()
    {
        return $this->belongsTo(Camion::class);
    }

    public function depotDepart()
    {
        return $this->belongsTo(Depot::class, 'depot_depart_id');
    }

    public function depotArrivee()
    {
        return $this->belongsTo(Depot::class, 'depot_arrivee_id');
    }

    // Méthodes utilitaires
    public function addPhoto($photoPath)
    {
        $photos = $this->photos ?? [];
        $photos[] = $photoPath;
        $this->photos = $photos;
        $this->save();
    }

    public function getTypeLabel()
    {
        $labels = [
            'creation' => 'Création du colis',
            'reception_depot' => 'Réception au dépôt',
            'affectation_flotte' => 'Affectation à la flotte',
            'prise_en_charge' => 'Prise en charge',
            'transfert_depot' => 'Transfert vers dépôt',
            'reception_depot_intermediaire' => 'Réception dépôt intermédiaire',
            'en_cours_livraison' => 'En cours de livraison',
            'livre' => 'Livré',
            'retour' => 'Retour',
        ];

        return $labels[$this->type] ?? $this->type;
    }
}
