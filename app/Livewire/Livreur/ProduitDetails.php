<?php

namespace App\Livewire\Livreur;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Etape;
use App\Services\HistoriqueService;
use Illuminate\Support\Facades\Storage;

class ProduitDetails extends Component
{
    use WithFileUploads;

    public \App\Models\Produit $produit;
    public $confirmingStatusChange = false;
    public $nextStatus = '';
    
    // Photos de livraison
    public $photos = [];
    public $uploadedPhotos = [];

    public function mount(\App\Models\Produit $produit)
    {
        // Sécurité stricte : Le livreur ne peut voir le produit que s'il est dans SON camion
        if (auth()->user()->role === 'livreur' && $produit->camion_id !== auth()->user()->camion_id) {
            abort(403, 'Vous n\'avez pas accès à ce colis.');
        }
        
        $this->produit = $produit;
        $this->uploadedPhotos = $produit->photos_livraison ?? [];
    }

    public function updatedPhotos()
    {
        $this->validate([
            'photos.*' => 'image|max:5120', // 5MB max par photo
        ]);

        foreach ($this->photos as $photo) {
            // Stocker la photo
            $path = $photo->store('livraisons/' . $this->produit->id, 'public');
            $this->uploadedPhotos[] = $path;
        }

        // Sauvegarder dans le produit
        $this->produit->update([
            'photos_livraison' => $this->uploadedPhotos
        ]);

        // Reset
        $this->photos = [];
        
        session()->flash('photo_message', '📸 Photo(s) ajoutée(s) avec succès !');
    }

    public function removePhoto($index)
    {
        if (isset($this->uploadedPhotos[$index])) {
            // Supprimer le fichier
            Storage::disk('public')->delete($this->uploadedPhotos[$index]);
            
            // Retirer du tableau
            unset($this->uploadedPhotos[$index]);
            $this->uploadedPhotos = array_values($this->uploadedPhotos);
            
            // Mettre à jour le produit
            $this->produit->update([
                'photos_livraison' => $this->uploadedPhotos
            ]);
        }
    }

    public function getAvailableStatusActionsProperty()
    {
        $current = $this->produit->statut;
        
        // Vérifier si la destination est un dépôt ou le client final
        $hasDepotDestination = !is_null($this->produit->depot_destination_id);
        
        // Workflow linéaire : valide -> prepare -> en_route -> livre
        return match($current) {
            'valide' => ['prepare' => 'Marquer comme Préparé'],
            'prepare' => ['en_route' => 'Commencer la Livraison'],
            'en_route' => $hasDepotDestination 
                ? ['arrive_depot' => 'Arrivé au Dépôt'] 
                : ['livre' => 'Confirmer la Livraison'],
            'arrive_depot' => [], // Le dépôt prend le relais
            default => [],
        };
    }

    public function getDestinationLabelProperty()
    {
        if ($this->produit->depotDestination) {
            return '🏪 Dépôt: ' . $this->produit->depotDestination->nom;
        }
        return '🏠 Client: ' . $this->produit->destination;
    }

    public function initiateStatusChange($status)
    {
        $this->nextStatus = $status;
        $this->confirmingStatusChange = true;
    }

    public function confirmStatusChange()
    {
        $newStatus = $this->nextStatus;
        
        // Si arrivé au dépôt destination
        if ($newStatus === 'arrive_depot') {
            // NE PAS transférer automatiquement - mettre en attente de réception
            $depotDestination = $this->produit->depotDestination;
            $camionId = $this->produit->camion_id; // Sauvegarder avant de libérer
            
            $this->produit->update([
                'statut' => 'en_attente_reception', // En attente de confirmation par le dépôt destinataire
                'livreur_id' => auth()->id(), // Enregistrer le livreur
                'camion_id' => null, // Libérer le camion
            ]);
            
            // Créer une étape de suivi
            Etape::create([
                'produit_id' => $this->produit->id,
                'user_id' => auth()->id(),
                'depot_id' => $depotDestination->id,
                'statut' => 'en_attente_reception',
                'description' => 'Colis livré au dépôt ' . $depotDestination->code . ' - En attente de confirmation de réception par ' . $depotDestination->nom,
                'date_etape' => now(),
            ]);
            
            // 📊 ENREGISTRER DANS L'HISTORIQUE
            HistoriqueService::enregistrerLivraison(
                livreurId: auth()->id(),
                camionId: $camionId,
                colisIds: $this->produit->id,
                destination: $depotDestination->nom ?? 'Dépôt',
                type: 'produit'
            );
            
            session()->flash('message', '✅ Colis livré au dépôt ' . $depotDestination->nom . ' ! En attente de confirmation par l\'admin du dépôt.');
            
        } elseif ($newStatus === 'livre') {
            // Livraison au client final
            $camionId = $this->produit->camion_id; // Sauvegarder avant de libérer
            
            $this->produit->update([
                'statut' => 'livre',
                'livreur_id' => auth()->id(), // Enregistrer le livreur
                'camion_id' => null, // Libérer le camion
            ]);
            
            // Créer une étape de suivi
            Etape::create([
                'produit_id' => $this->produit->id,
                'user_id' => auth()->id(),
                'depot_id' => $this->produit->depot_actuel_id,
                'statut' => 'livre',
                'description' => 'Colis livré au client final par ' . auth()->user()->nom,
                'date_etape' => now(),
            ]);
            
            // 📊 ENREGISTRER DANS L'HISTORIQUE
            HistoriqueService::enregistrerLivraison(
                livreurId: auth()->id(),
                camionId: $camionId,
                colisIds: $this->produit->id,
                destination: $this->produit->destination ?? $this->produit->destinataire_ville ?? 'Client',
                type: 'produit'
            );
            
            session()->flash('message', '✅ Colis livré au client avec succès !');
            
        } else {
            // Autres changements de statut
            $this->produit->update(['statut' => $newStatus]);
            
            Etape::create([
                'produit_id' => $this->produit->id,
                'user_id' => auth()->id(),
                'depot_id' => $this->produit->depot_actuel_id,
                'statut' => $newStatus,
                'description' => 'Statut changé à: ' . ucfirst(str_replace('_', ' ', $newStatus)),
                'date_etape' => now(),
            ]);
            
            session()->flash('message', 'Statut mis à jour avec succès !');
        }
        
        $this->produit->refresh();
        $this->confirmingStatusChange = false;
        $this->nextStatus = '';
    }

    public function cancelStatusChange()
    {
        $this->confirmingStatusChange = false;
        $this->nextStatus = '';
    }

    #[\Livewire\Attributes\Layout('layouts.app')]
    public function render()
    {
        return view('livewire.livreur.produit-details');
    }
}
