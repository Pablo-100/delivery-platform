<?php

namespace App\Livewire\Admin\Produits;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\Produit;
use App\Models\Camion;

#[Layout('layouts.app')]
class GestionStock extends Component
{
    public $search = '';
    public $filterStatut = 'stockage';
    public $selectedProduits = [];
    public $camion_id;
    public $showModal = false;
    public $produitToAssign = null;

    // Ouvrir le modal pour affecter un seul produit
    public function openAssignModal($produitId)
    {
        $this->produitToAssign = Produit::find($produitId);
        $this->camion_id = null;
        $this->showModal = true;
    }

    // Fermer le modal
    public function closeModal()
    {
        $this->showModal = false;
        $this->produitToAssign = null;
        $this->camion_id = null;
    }

    // Affecter un produit à un camion
    public function assignToCamion()
    {
        if (!$this->camion_id) {
            session()->flash('error', 'Veuillez sélectionner un camion.');
            return;
        }

        if ($this->produitToAssign) {
            // Affectation d'un seul produit
            $this->produitToAssign->update([
                'camion_id' => $this->camion_id,
                'statut' => 'prepare',
                'statut_depot' => 'en_livraison',
            ]);
            
            // Ajouter à l'historique
            $this->produitToAssign->camions()->attach($this->camion_id);
            
            session()->flash('message', 'Colis "' . $this->produitToAssign->nom . '" affecté à la flotte avec succès !');
        }

        $this->closeModal();
    }

    // Affectation en masse
    public function assignMultiple()
    {
        if (empty($this->selectedProduits)) {
            session()->flash('error', 'Veuillez sélectionner au moins un colis.');
            return;
        }

        if (!$this->camion_id) {
            session()->flash('error', 'Veuillez sélectionner un camion.');
            return;
        }

        $count = 0;
        foreach ($this->selectedProduits as $produitId) {
            $produit = Produit::find($produitId);
            if ($produit && $produit->statut === 'stockage') {
                $produit->update([
                    'camion_id' => $this->camion_id,
                    'statut' => 'prepare',
                    'statut_depot' => 'en_livraison',
                ]);
                $produit->camions()->attach($this->camion_id);
                $count++;
            }
        }

        $this->selectedProduits = [];
        $this->camion_id = null;
        
        session()->flash('message', $count . ' colis affecté(s) à la flotte avec succès !');
    }

    // Retirer un colis de la flotte (retour au stockage)
    public function removeFromFleet($produitId)
    {
        $produit = Produit::find($produitId);
        if ($produit) {
            $produit->update([
                'camion_id' => null,
                'statut' => 'stockage',
                'statut_depot' => 'stockage',
            ]);
            session()->flash('message', 'Colis retiré de la flotte et remis en stockage.');
        }
    }

    public function render()
    {
        $user = auth()->user();
        
        // Admin de dépôt : Ne voit que les produits dans SON dépôt
        // Super Admin : Voit tout
        if ($user->role === 'super_admin') {
            $query = Produit::query();
        } else {
            // Récupérer le dépôt géré par cet admin
            $adminDepot = \App\Models\Depot::where('admin_id', $user->id)->first();
            
            if ($adminDepot) {
                // Afficher les produits :
                // - Dans le dépôt actuel de cet admin
                // - Créés par cet admin et sans dépôt actuel
                // - En attente de réception vers le dépôt de cet admin
                $query = Produit::where(function($q) use ($adminDepot, $user) {
                    $q->where('depot_actuel_id', $adminDepot->id)
                      ->orWhere(function($q2) use ($user) {
                          $q2->where('admin_id', $user->id)
                             ->whereNull('depot_actuel_id');
                      })
                      ->orWhere(function($q3) use ($adminDepot) {
                          // Produits en attente de réception vers ce dépôt
                          $q3->where('depot_destination_id', $adminDepot->id)
                             ->where('statut', 'en_attente_reception');
                      });
                });
            } else {
                // Admin sans dépôt : Seulement ses propres créations
                $query = Produit::where('admin_id', $user->id);
            }
        }

        // Filtrer par statut
        if ($this->filterStatut) {
            $query->where('statut', $this->filterStatut);
        }

        // Recherche
        if ($this->search) {
            $query->where(function($q) {
                $q->where('nom', 'like', '%' . $this->search . '%')
                  ->orWhere('qr_code', 'like', '%' . $this->search . '%')
                  ->orWhere('destinataire_nom', 'like', '%' . $this->search . '%')
                  ->orWhere('destination', 'like', '%' . $this->search . '%');
            });
        }

        $produits = $query->with(['camion', 'depotSource', 'depotActuel', 'depotDestination'])->latest()->get();
        $camions = Camion::where('admin_id', auth()->id())->get();

        return view('livewire.admin.produits.gestion-stock', [
            'produits' => $produits,
            'camions' => $camions,
        ]);
    }
}
