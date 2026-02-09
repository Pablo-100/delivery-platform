<?php

namespace App\Livewire\Admin\Produits;

use Livewire\Component;
use App\Models\Depot;
use App\Services\SmsService;

class CreateProduit extends Component
{
    // Propriétés du formulaire
    public $nom;
    public $description;
    public $poids;
    public $volume;
    public $source;
    public $destination;
    
    // NOUVELLES PROPRIÉTÉS - Dimensions pour poids volumétrique
    public $longueur;
    public $largeur;
    public $hauteur;
    public $poids_volumetrique;
    
    // Choix du poids facturable
    public $poids_choisi; // 'reel' ou 'volumetrique'
    public $poids_facturable;
    
    // NOUVELLES PROPRIÉTÉS - Dépôts
    public $depot_source_id;
    public $depot_destination_id;
    
    // Expéditeur
    public $expediteur_nom;
    public $expediteur_email;
    public $expediteur_phone;
    public $expediteur_phone_code = '+216';
    public $expediteur_societe;
    public $expediteur_matricule_fiscale;
    
    // Destinataire
    public $destinataire_nom;
    public $destinataire_prenom;
    public $destinataire_phone;
    public $destinataire_phone_code = '+216';
    public $destinataire_adresse;
    public $destinataire_ville;
    public $destinataire_cin_3_derniers_chiffres;
    
    // Assignation
    public $camion_id;

    public function mount()
    {
        // Initialiser avec les données de l'admin connecté
        $user = auth()->user();
        $this->expediteur_email = $user->email;
        $this->expediteur_nom = $user->nom . ' ' . $user->prenom;
        $this->expediteur_societe = $user->company_info;
        $this->source = 'Tunis'; // Valeur par défaut
        
        // Définir le dépôt source par défaut (le dépôt de l'admin)
        $adminDepot = Depot::where('admin_id', $user->id)->first();
        if ($adminDepot) {
            $this->depot_source_id = $adminDepot->id;
        }
    }

    // Calculer le poids volumétrique en temps réel et mettre à jour le poids facturable
    public function updated($propertyName)
    {
        if (in_array($propertyName, ['longueur', 'largeur', 'hauteur'])) {
            $this->calculatePoidsVolumetrique();
        }
        
        // Mettre à jour le poids facturable quand l'admin fait son choix
        if ($propertyName === 'poids_choisi') {
            $this->updatePoidsFacturable();
        }
        
        // Recalculer si le poids réel change
        if ($propertyName === 'poids' && $this->poids_choisi === 'reel') {
            $this->updatePoidsFacturable();
        }
    }
    
    public function updatePoidsFacturable()
    {
        if ($this->poids_choisi === 'reel') {
            $this->poids_facturable = $this->poids;
        } elseif ($this->poids_choisi === 'volumetrique') {
            $this->poids_facturable = $this->poids_volumetrique;
        }
    }

    public function calculatePoidsVolumetrique()
    {
        if ($this->longueur && $this->largeur && $this->hauteur) {
            $this->poids_volumetrique = round(($this->longueur * $this->largeur * $this->hauteur) / 5000, 2);
        } else {
            $this->poids_volumetrique = null;
        }
    }

    protected $rules = [
        'nom' => 'required|string|max:255',
        'description' => 'nullable|string',
        'poids' => 'nullable|numeric|min:0',
        'volume' => 'nullable|numeric|min:0',
        'longueur' => 'nullable|numeric|min:0',
        'largeur' => 'nullable|numeric|min:0',
        'hauteur' => 'nullable|numeric|min:0',
        'source' => 'required|string|max:255',
        'destination' => 'required|string|max:255',
        'depot_source_id' => 'nullable|exists:depots,id',
        'depot_destination_id' => 'nullable|exists:depots,id',
        'poids_choisi' => 'nullable|in:reel,volumetrique',
        
        'expediteur_nom' => 'nullable|string',
        'expediteur_email' => 'required|email',
        'expediteur_phone' => 'required|string|min:8',
        'expediteur_societe' => 'nullable|string',
        'expediteur_matricule_fiscale' => 'nullable|string',
        
        'destinataire_nom' => 'required|string',
        'destinataire_prenom' => 'required|string',
        'destinataire_phone' => 'required|string|min:8',
        'destinataire_adresse' => 'nullable|string',
        'destinataire_ville' => 'nullable|string',
        'destinataire_cin_3_derniers_chiffres' => 'required|string|size:3',
        
        'camion_id' => 'nullable|exists:camions,id',
    ];

    public function save()
    {
        // Préparer les numéros avec le code pays
        $this->expediteur_phone = $this->expediteur_phone_code . preg_replace('/[^0-9]/', '', $this->expediteur_phone);
        $this->destinataire_phone = $this->destinataire_phone_code . preg_replace('/[^0-9]/', '', $this->destinataire_phone);

        $validatedData = $this->validate();

        // Calculer le poids volumétrique si dimensions fournies
        if ($this->longueur && $this->largeur && $this->hauteur) {
            $validatedData['poids_volumetrique'] = ($this->longueur * $this->largeur * $this->hauteur) / 5000;
        }
        
        // Enregistrer le poids facturable selon le choix de l'admin
        if ($this->poids_choisi === 'reel') {
            $validatedData['poids_facturable'] = $this->poids;
        } elseif ($this->poids_choisi === 'volumetrique') {
            $validatedData['poids_facturable'] = $this->poids_volumetrique;
        }

        // Ajout des champs automatiques
        $validatedData['admin_id'] = auth()->id();
        $validatedData['qr_code'] = \App\Models\Produit::generateUniqueQRCode();
        
        // MODIFIÉ: Le colis reste en "stockage" par défaut
        $validatedData['statut'] = 'stockage';
        
        // Définir le dépôt actuel automatiquement (le dépôt de l'admin créateur)
        $adminDepot = Depot::where('admin_id', auth()->id())->first();
        if ($adminDepot && empty($validatedData['depot_actuel_id'])) {
            $validatedData['depot_actuel_id'] = $adminDepot->id;
        }
        // Si depot_source_id n'est pas défini, le mettre aussi
        if ($adminDepot && empty($validatedData['depot_source_id'])) {
            $validatedData['depot_source_id'] = $adminDepot->id;
        }

        $produit = \App\Models\Produit::create($validatedData);

        // Envoyer les SMS de notification
        // $this->sendTrackingSms($produit); // SMS désactivé temporairement

        // MODIFIÉ: Affectation manuelle - seulement si l'admin choisit un camion
        if ($this->camion_id) {
            $produit->statut = 'prepare';
            $produit->camion_id = $this->camion_id;
            $produit->save();
            
            // Ajouter à l'historique
            $produit->camions()->attach($this->camion_id);
            
            session()->flash('message', 'Colis créé et affecté à la flotte ! QR Code généré.');
        } else {
            // Le colis reste au dépôt sans affectation
            session()->flash('message', 'Colis créé et stocké au dépôt ! QR Code généré. Vous pouvez l\'affecter à une flotte plus tard.');
        }

        return redirect()->route('admin.dashboard');
    }

    /**
     * Envoyer les SMS de tracking à l'expéditeur et au destinataire
     */
    protected function sendTrackingSms($produit)
    {
        $smsService = new SmsService();
        $smsResults = ['expediteur' => false, 'destinataire' => false];

        // SMS à l'expéditeur
        if (!empty($this->expediteur_phone)) {
            $smsResults['expediteur'] = $smsService->sendToExpediteur(
                $this->expediteur_phone,
                $produit->qr_code,
                $produit->nom
            );
        }

        // SMS au destinataire
        if (!empty($this->destinataire_phone)) {
            $smsResults['destinataire'] = $smsService->sendToDestinataire(
                $this->destinataire_phone,
                $produit->qr_code,
                $produit->nom,
                $this->expediteur_nom
            );
        }

        // Log des résultats
        if ($smsResults['expediteur'] || $smsResults['destinataire']) {
            $sentTo = [];
            if ($smsResults['expediteur']) $sentTo[] = 'expéditeur';
            if ($smsResults['destinataire']) $sentTo[] = 'destinataire';
            
            session()->flash('sms_sent', 'SMS envoyé à: ' . implode(', ', $sentTo));
        }
    }

    public function render()
    {
        // Récupérer les camions de cet admin
        $camions = \App\Models\Camion::where('admin_id', auth()->id())->get();
        
        // Récupérer les dépôts actifs
        $depots = Depot::where('actif', true)->get();
        
        return view('livewire.admin.produits.create-produit', compact('camions', 'depots'));
    }
}
