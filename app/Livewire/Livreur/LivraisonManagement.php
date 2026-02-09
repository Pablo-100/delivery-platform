<?php

namespace App\Livewire\Livreur;

use App\Models\Colis;
use App\Models\Etape;
use App\Models\HistoriqueLivreur;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Storage;

#[Layout('layouts.app')]
class LivraisonManagement extends Component
{
    use WithFileUploads;

    public $showModal = false;
    public $selectedColis = null;
    public $action = ''; // 'prendre_en_charge', 'transferer', 'livrer'
    
    public $photo;
    public $commentaire;
    public $depot_destination_id;
    
    public $tourneeActive = null;

    public function mount()
    {
        // Vérifier s'il y a une tournée active
        $this->tourneeActive = HistoriqueLivreur::where('livreur_id', auth()->id())
            ->where('statut', 'en_cours')
            ->first();
    }

    public function demarrerTournee()
    {
        $camion = auth()->user()->camion;
        
        if (!$camion) {
            session()->flash('error', 'Vous devez être assigné à un camion pour démarrer une tournée');
            return;
        }

        $this->tourneeActive = HistoriqueLivreur::create([
            'livreur_id' => auth()->id(),
            'camion_id' => $camion->id,
            'tournee_code' => 'TOUR-' . date('YmdHis'),
            'heure_debut' => now(),
            'statut' => 'en_cours',
            'colis_ids' => [],
            'nombre_colis' => 0,
            'colis_livres' => 0,
            'colis_en_cours' => 0,
        ]);

        session()->flash('message', 'Tournée démarrée avec succès!');
    }

    public function terminerTournee()
    {
        if ($this->tourneeActive) {
            $this->tourneeActive->update([
                'heure_fin' => now(),
                'statut' => 'termine',
            ]);

            session()->flash('message', 'Tournée terminée!');
            $this->tourneeActive = null;
        }
    }

    public function prendreEnCharge($colisId)
    {
        $this->selectedColis = Colis::findOrFail($colisId);
        $this->action = 'prendre_en_charge';
        $this->showModal = true;
    }

    public function transferer($colisId)
    {
        $this->selectedColis = Colis::findOrFail($colisId);
        $this->action = 'transferer';
        $this->showModal = true;
    }

    public function livrer($colisId)
    {
        $this->selectedColis = Colis::findOrFail($colisId);
        $this->action = 'livrer';
        $this->showModal = true;
    }

    public function save()
    {
        $this->validate([
            'photo' => 'required|image|max:5120',
            'commentaire' => 'nullable|string',
        ]);

        if (!$this->selectedColis) {
            return;
        }

        // Sauvegarder la photo
        $photoPath = $this->photo->store('etapes/photos', 'public');

        switch ($this->action) {
            case 'prendre_en_charge':
                $this->executePriseEnCharge($photoPath);
                break;
            case 'transferer':
                $this->executeTransfert($photoPath);
                break;
            case 'livrer':
                $this->executeLivraison($photoPath);
                break;
        }

        $this->showModal = false;
        $this->reset(['photo', 'commentaire', 'selectedColis', 'action', 'depot_destination_id']);
    }

    private function executePriseEnCharge($photoPath)
    {
        // Créer l'étape
        Etape::create([
            'colis_id' => $this->selectedColis->id,
            'type' => 'prise_en_charge',
            'user_id' => auth()->id(),
            'livreur_id' => auth()->id(),
            'camion_id' => auth()->user()->camion_id,
            'depot_depart_id' => $this->selectedColis->depot_actuel_id,
            'photos' => [$photoPath],
            'commentaire' => $this->commentaire,
            'date_etape' => now(),
        ]);

        // Mettre à jour le colis
        $this->selectedColis->update([
            'statut' => 'en_transit',
            'livreur_id' => auth()->id(),
        ]);

        // Créer une tournée automatiquement si pas active
        if (!$this->tourneeActive) {
            $camion = auth()->user()->camion;
            if ($camion) {
                $this->tourneeActive = HistoriqueLivreur::create([
                    'livreur_id' => auth()->id(),
                    'camion_id' => $camion->id,
                    'tournee_code' => 'TOUR-' . date('YmdHis'),
                    'heure_debut' => now(),
                    'statut' => 'en_cours',
                    'colis_ids' => [],
                    'nombre_colis' => 0,
                    'colis_livres' => 0,
                    'colis_en_cours' => 0,
                    'depots_visites' => [],
                    'distance_km' => 0,
                ]);
            }
        }

        // Mettre à jour la tournée
        if ($this->tourneeActive) {
            $colisIds = $this->tourneeActive->colis_ids ?? [];
            if (!in_array($this->selectedColis->id, $colisIds)) {
                $colisIds[] = $this->selectedColis->id;
            }
            
            // Ajouter la destination visitée
            $depotsVisites = $this->tourneeActive->depots_visites ?? [];
            $depotNom = $this->selectedColis->depotActuel?->nom ?? $this->selectedColis->depot_actuel_id;
            if ($depotNom && !in_array($depotNom, $depotsVisites)) {
                $depotsVisites[] = $depotNom;
            }
            
            $this->tourneeActive->update([
                'colis_ids' => $colisIds,
                'nombre_colis' => count($colisIds),
                'colis_en_cours' => $this->tourneeActive->colis_en_cours + 1,
                'depots_visites' => $depotsVisites,
            ]);
        }

        session()->flash('message', 'Colis pris en charge avec succès!');
    }

    private function executeTransfert($photoPath)
    {
        $this->validate([
            'depot_destination_id' => 'required|exists:depots,id',
        ]);

        // Créer l'étape
        Etape::create([
            'colis_id' => $this->selectedColis->id,
            'type' => 'transfert_depot',
            'user_id' => auth()->id(),
            'livreur_id' => auth()->id(),
            'camion_id' => auth()->user()->camion_id,
            'depot_depart_id' => $this->selectedColis->depot_actuel_id,
            'depot_arrivee_id' => $this->depot_destination_id,
            'photos' => [$photoPath],
            'commentaire' => $this->commentaire,
            'date_etape' => now(),
        ]);

        // Mettre à jour le colis
        $this->selectedColis->update([
            'statut' => 'en_depot',
            'depot_actuel_id' => $this->depot_destination_id,
        ]);

        // Mettre à jour la tournée - ajouter destination visitée
        if ($this->tourneeActive) {
            $depotsVisites = $this->tourneeActive->depots_visites ?? [];
            $depot = \App\Models\Depot::find($this->depot_destination_id);
            if ($depot && !in_array($depot->nom, $depotsVisites)) {
                $depotsVisites[] = $depot->nom;
                $this->tourneeActive->update(['depots_visites' => $depotsVisites]);
            }
        }

        session()->flash('message', 'Colis transféré avec succès!');
    }

    private function executeLivraison($photoPath)
    {
        // Créer l'étape
        Etape::create([
            'colis_id' => $this->selectedColis->id,
            'type' => 'livre',
            'user_id' => auth()->id(),
            'livreur_id' => auth()->id(),
            'camion_id' => auth()->user()->camion_id,
            'depot_depart_id' => $this->selectedColis->depot_actuel_id,
            'photos' => [$photoPath],
            'commentaire' => $this->commentaire,
            'date_etape' => now(),
        ]);

        // Mettre à jour le colis
        $this->selectedColis->update([
            'statut' => 'livre',
            'date_livraison' => now(),
        ]);

        // Mettre à jour la tournée
        if ($this->tourneeActive) {
            // Ajouter destination de livraison
            $depotsVisites = $this->tourneeActive->depots_visites ?? [];
            $destination = $this->selectedColis->destinataire_ville ?? $this->selectedColis->destination;
            if ($destination && !in_array($destination, $depotsVisites)) {
                $depotsVisites[] = $destination;
            }
            
            $this->tourneeActive->update([
                'colis_livres' => $this->tourneeActive->colis_livres + 1,
                'colis_en_cours' => max(0, $this->tourneeActive->colis_en_cours - 1),
                'depots_visites' => $depotsVisites,
            ]);
        }

        session()->flash('message', 'Colis livré avec succès!');
    }

    public function render()
    {
        // Colis affectés au livreur
        $mesColis = Colis::where('livreur_id', auth()->id())
            ->whereIn('statut', ['affecte', 'en_transit'])
            ->with(['depotActuel', 'depotDestination'])
            ->orderBy('created_at', 'desc')
            ->get();

        // Historique
        $historique = Colis::where('livreur_id', auth()->id())
            ->where('statut', 'livre')
            ->with(['depotActuel'])
            ->orderBy('date_livraison', 'desc')
            ->limit(10)
            ->get();

        $depots = \App\Models\Depot::where('actif', true)->get();

        return view('livewire.livreur.livraison-management', [
            'mesColis' => $mesColis,
            'historique' => $historique,
            'depots' => $depots,
        ]);
    }
}
