<?php

namespace App\Livewire;

use App\Models\Colis;
use App\Models\Produit;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;

#[Layout('layouts.guest')]
class TrackingPublic extends Component
{
    #[Url(as: 'code')]
    public $code_colis = '';
    
    public $colis = null;
    public $produit = null;
    public $etapes = [];
    public $error = '';
    public $isProduit = false;

    public function mount()
    {
        // Si un code est passé dans l'URL, lancer la recherche automatiquement
        if (!empty($this->code_colis)) {
            $this->rechercher();
        }
    }

    public function rechercher()
    {
        $this->error = '';
        $this->colis = null;
        $this->produit = null;
        $this->etapes = [];
        $this->isProduit = false;

        if (empty($this->code_colis)) {
            $this->error = 'Veuillez entrer un code de colis';
            return;
        }

        // D'abord chercher dans Produit (nouveau système)
        $this->produit = Produit::where('qr_code', $this->code_colis)
            ->with(['depotActuel', 'depotSource', 'depotDestination', 'camion'])
            ->first();

        if ($this->produit) {
            $this->isProduit = true;
            $this->etapes = $this->produit->etapes()
                ->with(['user', 'depot'])
                ->orderBy('date_etape', 'asc')
                ->get();
            return;
        }

        // Sinon chercher dans Colis (ancien système)
        $this->colis = Colis::where('code_colis', $this->code_colis)
            ->with(['depotActuel', 'depotSource', 'depotDestination', 'livreur', 'admin'])
            ->first();

        if ($this->colis) {
            $this->etapes = $this->colis->etapes()
                ->with(['user', 'livreur', 'depotDepart', 'depotArrivee'])
                ->orderBy('date_etape', 'asc')
                ->get();
            return;
        }

        $this->error = 'Aucun colis trouvé avec ce code';
    }

    public function render()
    {
        return view('livewire.tracking-public');
    }
}
