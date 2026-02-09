<?php

namespace App\Livewire\Admin\Produits;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\WithFileUploads;
use App\Models\Produit;
use App\Models\Camion;
use App\Models\Depot;
use App\Models\Etape;
use Illuminate\Support\Facades\Storage;

#[Layout('layouts.app')]
class DetailProduit extends Component
{
    use WithFileUploads;

    public Produit $produit;
    
    // Onglet actif
    public $activeTab = 'info';
    
    // Édition des infos
    public $editMode = false;
    public $nom, $description, $poids, $volume;
    public $longueur, $largeur, $hauteur, $poids_volumetrique, $poids_facturable;
    public $source, $destination;
    public $destinataire_nom, $destinataire_prenom, $destinataire_phone, $destinataire_adresse, $destinataire_ville;
    
    // Affectation flotte
    public $camion_id;
    public $depot_source_id, $depot_destination_id, $depot_actuel_id;
    
    // Ajout d'étape/photo
    public $newEtapeStatut = '';
    public $newEtapeDescription = '';
    public $newEtapePhotos = [];
    public $showAddEtapeModal = false;

    public function mount($id)
    {
        $this->produit = Produit::with(['camion', 'depotSource', 'depotDestination', 'depotActuel', 'etapes.user', 'etapes.depot'])->findOrFail($id);
        $this->loadProduitData();
    }

    public function loadProduitData()
    {
        $this->nom = $this->produit->nom;
        $this->description = $this->produit->description;
        $this->poids = $this->produit->poids;
        $this->volume = $this->produit->volume;
        $this->longueur = $this->produit->longueur;
        $this->largeur = $this->produit->largeur;
        $this->hauteur = $this->produit->hauteur;
        $this->poids_volumetrique = $this->produit->poids_volumetrique;
        $this->poids_facturable = $this->produit->poids_facturable;
        $this->source = $this->produit->source;
        $this->destination = $this->produit->destination;
        $this->destinataire_nom = $this->produit->destinataire_nom;
        $this->destinataire_prenom = $this->produit->destinataire_prenom;
        $this->destinataire_phone = $this->produit->destinataire_phone;
        $this->destinataire_adresse = $this->produit->destinataire_adresse;
        $this->destinataire_ville = $this->produit->destinataire_ville;
        $this->camion_id = $this->produit->camion_id;
        $this->depot_source_id = $this->produit->depot_source_id;
        $this->depot_destination_id = $this->produit->depot_destination_id;
        $this->depot_actuel_id = $this->produit->depot_actuel_id;
    }

    public function toggleEditMode()
    {
        $this->editMode = !$this->editMode;
        if (!$this->editMode) {
            $this->loadProduitData();
        }
    }

    public function saveInfo()
    {
        $this->validate([
            'nom' => 'required|string|max:255',
            'destination' => 'required|string|max:255',
            'destinataire_nom' => 'required|string',
            'destinataire_prenom' => 'required|string',
        ]);

        $this->produit->update([
            'nom' => $this->nom,
            'description' => $this->description,
            'poids' => $this->poids,
            'volume' => $this->volume,
            'longueur' => $this->longueur,
            'largeur' => $this->largeur,
            'hauteur' => $this->hauteur,
            'poids_facturable' => $this->poids_facturable,
            'source' => $this->source,
            'destination' => $this->destination,
            'destinataire_nom' => $this->destinataire_nom,
            'destinataire_prenom' => $this->destinataire_prenom,
            'destinataire_phone' => $this->destinataire_phone,
            'destinataire_adresse' => $this->destinataire_adresse,
            'destinataire_ville' => $this->destinataire_ville,
        ]);

        $this->editMode = false;
        $this->produit->refresh();
        session()->flash('message', 'Informations mises à jour avec succès !');
    }

    // Affecter à une flotte
    public function assignToFleet()
    {
        if (!$this->camion_id) {
            session()->flash('error', 'Veuillez sélectionner un camion.');
            return;
        }

        $this->produit->update([
            'camion_id' => $this->camion_id,
            'statut' => 'prepare',
            'statut_depot' => 'en_livraison',
        ]);

        // Historique
        $this->produit->camions()->syncWithoutDetaching([$this->camion_id]);
        
        // Ajouter une étape
        $this->createEtape('affecte_flotte', 'Colis affecté au camion ' . $this->produit->camion->immatriculation);

        $this->produit->refresh();
        session()->flash('message', 'Colis affecté à la flotte !');
    }

    // Retirer de la flotte
    public function removeFromFleet()
    {
        $oldCamion = $this->produit->camion?->immatriculation;
        
        $this->produit->update([
            'camion_id' => null,
            'statut' => 'stockage',
            'statut_depot' => 'stockage',
        ]);

        $this->camion_id = null;
        
        if ($oldCamion) {
            $this->createEtape('retour_stockage', 'Colis retiré du camion ' . $oldCamion . ' et remis en stockage');
        }

        $this->produit->refresh();
        session()->flash('message', 'Colis retiré de la flotte et remis en stockage.');
    }

    // Changer le statut
    public function changeStatut($newStatut)
    {
        $statutLabels = [
            'stockage' => 'En Stockage',
            'valide' => 'Validé',
            'prepare' => 'Préparé',
            'en_route' => 'En Route',
            'livre' => 'Livré',
        ];

        $this->produit->update(['statut' => $newStatut]);
        $this->createEtape($newStatut, 'Statut changé à: ' . ($statutLabels[$newStatut] ?? $newStatut));
        
        $this->produit->refresh();
        session()->flash('message', 'Statut mis à jour !');
    }

    // Changer le dépôt actuel
    public function updateDepotActuel()
    {
        if ($this->depot_actuel_id) {
            $depot = Depot::find($this->depot_actuel_id);
            $this->produit->update([
                'depot_actuel_id' => $this->depot_actuel_id,
                'statut_depot' => 'au_depot',
            ]);
            
            $this->createEtape('arrivee_depot', 'Colis arrivé au dépôt ' . $depot->code . ' - ' . $depot->nom);
            
            $this->produit->refresh();
            session()->flash('message', 'Dépôt actuel mis à jour !');
        }
    }

    // Modifier la destination cible (Admin Choice)
    public function updateDestinationTarget()
    {
        // Si la valeur est vide, on met à null pour indiquer une livraison client directe
        $target = $this->depot_destination_id ?: null;

        $this->produit->update([
            'depot_destination_id' => $target
        ]);
        
        $this->produit->refresh();
        
        $msg = $target ? 'Cible définie : Dépôt' : 'Cible définie : Client Final';
        session()->flash('message', $msg);
    }

    // Modal ajout étape
    public function openAddEtapeModal()
    {
        $this->showAddEtapeModal = true;
        $this->newEtapeStatut = '';
        $this->newEtapeDescription = '';
        $this->newEtapePhotos = [];
    }

    public function closeAddEtapeModal()
    {
        $this->showAddEtapeModal = false;
        $this->newEtapePhotos = [];
    }

    // Ajouter une étape avec photos
    public function addEtape()
    {
        $this->validate([
            'newEtapeStatut' => 'required|string',
            'newEtapeDescription' => 'required|string',
            'newEtapePhotos.*' => 'nullable|image|max:5120', // 5MB max
        ]);

        $photoPaths = [];
        
        if ($this->newEtapePhotos) {
            foreach ($this->newEtapePhotos as $photo) {
                $path = $photo->store('etapes/' . $this->produit->id, 'public');
                $photoPaths[] = $path;
            }
        }

        $this->createEtape($this->newEtapeStatut, $this->newEtapeDescription, $photoPaths);

        $this->closeAddEtapeModal();
        $this->produit->refresh();
        session()->flash('message', 'Étape ajoutée avec succès !');
    }

    private function createEtape($statut, $description, $photos = [])
    {
        Etape::create([
            'produit_id' => $this->produit->id,
            'user_id' => auth()->id(),
            'depot_id' => $this->produit->depot_actuel_id,
            'statut' => $statut,
            'description' => $description,
            'photos' => $photos,
            'localisation' => null,
            'date_etape' => now(),
        ]);
    }

    public function getCanManageProperty()
    {
        $user = auth()->user();

        if ($user->role === 'super_admin') {
            return true;
        }

        if ($user->role !== 'admin') {
            return false;
        }

        // Récupérer le dépôt géré par cet admin
        $adminDepot = Depot::where('admin_id', $user->id)->first();

        // 1. Cas : Admin créateur du produit -> Peut toujours gérer
        if ($this->produit->admin_id === $user->id) {
            return true;
        }

        // 2. Cas : Produit dans le dépôt SOURCE de cet admin -> Peut gérer
        if ($adminDepot && $this->produit->depot_source_id === $adminDepot->id) {
            return true;
        }

        // 3. Cas : Produit dans le dépôt ACTUEL de cet admin -> Peut gérer
        if ($adminDepot && $this->produit->depot_actuel_id === $adminDepot->id) {
            return true;
        }

        // 4. Cas : Produit en attente de réception dans le dépôt DESTINATION de cet admin -> Peut confirmer
        if ($adminDepot && $this->produit->depot_destination_id === $adminDepot->id && $this->produit->statut === 'en_attente_reception') {
            return true;
        }

        return false;
    }

    // Vérifier si l'admin peut confirmer la réception (seulement admin du dépôt destination)
    public function getCanConfirmReceptionProperty()
    {
        $user = auth()->user();
        
        if ($user->role === 'super_admin') {
            return $this->produit->statut === 'en_attente_reception';
        }
        
        if ($user->role !== 'admin') {
            return false;
        }
        
        $adminDepot = Depot::where('admin_id', $user->id)->first();
        
        return $adminDepot 
            && $this->produit->depot_destination_id === $adminDepot->id 
            && $this->produit->statut === 'en_attente_reception';
    }

    // Confirmer la réception du produit
    public function confirmReception()
    {
        if (!$this->canConfirmReception) {
            session()->flash('error', 'Vous n\'êtes pas autorisé à confirmer cette réception.');
            return;
        }

        $depotDestination = $this->produit->depotDestination;
        
        $this->produit->update([
            'statut' => 'stockage',
            'depot_actuel_id' => $this->produit->depot_destination_id,
            'depot_source_id' => $this->produit->depot_destination_id, // Nouveau point de départ
            'depot_destination_id' => null, // Plus de destination intermédiaire
            'admin_id' => auth()->id(), // Transfert de propriété
        ]);

        $this->createEtape('reception_confirmee', 'Réception confirmée par ' . auth()->user()->nom . ' - Produit intégré au stock du dépôt ' . $depotDestination->code);

        $this->produit->refresh();
        session()->flash('message', '✅ Réception confirmée ! Le produit est maintenant dans votre stock.');
    }

    /**
     * Extraire le code pays d'un numéro de téléphone
     */
    public function getPhoneCode(string $phone = null): string
    {
        if (empty($phone)) return '+216';
        
        $codes = ['+213','+212','+218','+966','+971','+974','+965','+973','+968','+962','+961','+970',
                   '+221','+225','+237','+234','+351','+358','+216','+33','+49','+39','+34','+44',
                   '+32','+41','+31','+90','+20','+27','+1','+55','+86','+81','+82','+91'];
        
        // Trier par longueur décroissante
        usort($codes, fn($a, $b) => strlen($b) - strlen($a));
        
        foreach ($codes as $code) {
            if (str_starts_with($phone, $code)) {
                return $code;
            }
        }
        return '+216';
    }

    /**
     * Extraire le numéro local (sans code pays)
     */
    public function getPhoneLocal(string $phone = null): string
    {
        if (empty($phone)) return '';
        $code = $this->getPhoneCode($phone);
        return ltrim(substr($phone, strlen($code)), '0');
    }

    public function render()
    {
        $camions = Camion::where('admin_id', auth()->id())->get();
        $depots = Depot::where('actif', true)->get();
        $etapes = $this->produit->etapes()->with(['user', 'depot'])->orderBy('created_at', 'desc')->get();

        return view('livewire.admin.produits.detail-produit', [
            'camions' => $camions,
            'depots' => $depots,
            'etapes' => $etapes,
        ]);
    }
}
