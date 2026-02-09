<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Depot extends Model
{
    protected $fillable = [
        'code',
        'nom',
        'adresse',
        'ville',
        'phone',
        'admin_id',
        'actif',
    ];

    protected $casts = [
        'actif' => 'boolean',
    ];

    // Relations
    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    public function colisSource()
    {
        return $this->hasMany(Colis::class, 'depot_source_id');
    }

    public function colisActuel()
    {
        return $this->hasMany(Colis::class, 'depot_actuel_id');
    }

    public function colisDestination()
    {
        return $this->hasMany(Colis::class, 'depot_destination_id');
    }
}
