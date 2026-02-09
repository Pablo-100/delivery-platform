<?php

namespace App\Livewire\Admin;

use App\Models\Colis;
use App\Models\Depot;
use App\Models\Camion;
use App\Models\Etape;
use App\Models\User;
use App\Services\SmsService;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

#[Layout('layouts.app')]
class ColisManagement extends Component
{
    use WithFileUploads;

    public $showModal = false;
    public $editMode = false;
    public $colisId = null;

    // Form fields
    public $code_colis;
    public $nom;
    public $description;
    public $poids;
    public $longueur;
    public $largeur;
    public $hauteur;
    public $poids_volumetrique;
    
    public $expediteur_nom;
    public $expediteur_email;
    public $expediteur_phone;
    public $expediteur_phone_code = '+216';
    public $expediteur_societe;
    public $expediteur_matricule_fiscale;
    
    public $destinataire_nom;
    public $destinataire_prenom;
    public $destinataire_phone;
    public $destinataire_phone_code = '+216';
    public $destinataire_adresse;
    public $destinataire_ville;
    public $destinataire_cin_3_derniers_chiffres;
    
    public $depot_source_id;
    public $depot_actuel_id;
    public $depot_destination_id;
    
    public $camion_id;
    public $statut = 'en_attente';
    
    // Photo pour réception
    public $photo_reception;
    public $commentaire_reception;

    public $search = '';

    protected function rules()
    {
        return [
            'nom' => 'required|string|max:255',
            'description' => 'nullable|string',
            'poids' => 'required|numeric|min:0',
            'longueur' => 'nullable|numeric|min:0',
            'largeur' => 'nullable|numeric|min:0',
            'hauteur' => 'nullable|numeric|min:0',
            
            'expediteur_nom' => 'required|string|max:255',
            'expediteur_email' => 'required|email',
            'expediteur_phone' => 'required|string',
            'expediteur_societe' => 'nullable|string',
            'expediteur_matricule_fiscale' => 'nullable|string',
            
            'destinataire_nom' => 'required|string|max:255',
            'destinataire_prenom' => 'required|string|max:255',
            'destinataire_phone' => 'required|string',
            'destinataire_adresse' => 'required|string',
            'destinataire_ville' => 'required|string',
            'destinataire_cin_3_derniers_chiffres' => 'nullable|string|size:3',
            
            'depot_source_id' => 'nullable|exists:depots,id',
            'depot_actuel_id' => 'nullable|exists:depots,id',
            'depot_destination_id' => 'nullable|exists:depots,id',
        ];
    }

    public function updated($propertyName)
    {
        // Calculer le poids volumétrique en temps réel
        if (in_array($propertyName, ['longueur', 'largeur', 'hauteur'])) {
            if ($this->longueur && $this->largeur && $this->hauteur) {
                $this->poids_volumetrique = ($this->longueur * $this->largeur * $this->hauteur) / 5000;
            }
        }
    }

    public function create()
    {
        $this->resetForm();
        $this->showModal = true;
        $this->editMode = false;
        $this->generateCodeColis();
    }

    public function generateCodeColis()
    {
        $year = date('Y');
        $lastColis = Colis::whereYear('created_at', $year)->orderBy('id', 'desc')->first();
        $number = $lastColis ? intval(substr($lastColis->code_colis, -4)) + 1 : 1;
        $this->code_colis = 'COL-' . $year . '-' . str_pad($number, 4, '0', STR_PAD_LEFT);
    }

    public function store()
    {
        $this->validate();

        $colis = Colis::create([
            'code_colis' => $this->code_colis,
            'nom' => $this->nom,
            'description' => $this->description,
            'poids' => $this->poids,
            'longueur' => $this->longueur,
            'largeur' => $this->largeur,
            'hauteur' => $this->hauteur,
            'expediteur_nom' => $this->expediteur_nom,
            'expediteur_email' => $this->expediteur_email,
            'expediteur_phone' => $this->expediteur_phone_code . $this->expediteur_phone,
            'expediteur_societe' => $this->expediteur_societe,
            'expediteur_matricule_fiscale' => $this->expediteur_matricule_fiscale,
            'destinataire_nom' => $this->destinataire_nom,
            'destinataire_prenom' => $this->destinataire_prenom,
            'destinataire_phone' => $this->destinataire_phone_code . $this->destinataire_phone,
            'destinataire_adresse' => $this->destinataire_adresse,
            'destinataire_ville' => $this->destinataire_ville,
            'destinataire_cin_3_derniers_chiffres' => $this->destinataire_cin_3_derniers_chiffres,
            'depot_source_id' => $this->depot_source_id,
            'depot_actuel_id' => $this->depot_actuel_id ?: $this->depot_source_id,
            'depot_destination_id' => $this->depot_destination_id,
            'admin_id' => auth()->id(),
            'statut' => 'en_attente',
        ]);

        // Créer l'étape de création
        Etape::create([
            'colis_id' => $colis->id,
            'type' => 'creation',
            'user_id' => auth()->id(),
            'depot_depart_id' => $this->depot_source_id,
            'date_etape' => now(),
            'commentaire' => 'Colis créé et enregistré dans le système',
        ]);

        // Envoyer les SMS de tracking
        // $this->sendTrackingSms($colis); // SMS désactivé temporairement

        session()->flash('message', 'Colis créé avec succès! Code: ' . $colis->code_colis);
        $this->showModal = false;
        $this->resetForm();
    }

    public function affecterFlotte($colisId, $camionId)
    {
        $colis = Colis::findOrFail($colisId);
        $camion = Camion::findOrFail($camionId);

        $colis->update([
            'camion_id' => $camionId,
            'livreur_id' => $camion->livreur_id,
            'statut' => 'affecte',
        ]);

        // Créer l'étape d'affectation
        Etape::create([
            'colis_id' => $colis->id,
            'type' => 'affectation_flotte',
            'user_id' => auth()->id(),
            'livreur_id' => $camion->livreur_id,
            'camion_id' => $camionId,
            'depot_depart_id' => $colis->depot_actuel_id,
            'date_etape' => now(),
            'commentaire' => "Colis affecté au camion {$camion->immatriculation}",
        ]);

        session()->flash('message', 'Colis affecté à la flotte avec succès!');
    }

    public function recevoirColis($colisId)
    {
        $this->colisId = $colisId;
        $this->showModal = true;
        $this->editMode = false;
    }

    public function saveReception()
    {
        $this->validate([
            'photo_reception' => 'required|image|max:5120',
            'commentaire_reception' => 'nullable|string',
        ]);

        $colis = Colis::findOrFail($this->colisId);
        
        // Sauvegarder la photo
        $photoPath = $this->photo_reception->store('etapes/photos', 'public');

        // Créer l'étape de réception
        Etape::create([
            'colis_id' => $colis->id,
            'type' => 'reception_depot',
            'user_id' => auth()->id(),
            'depot_arrivee_id' => $colis->depot_actuel_id,
            'photos' => [$photoPath],
            'commentaire' => $this->commentaire_reception,
            'date_etape' => now(),
        ]);

        $colis->update(['statut' => 'en_depot']);

        session()->flash('message', 'Réception enregistrée avec succès!');
        $this->showModal = false;
        $this->resetForm();
    }

    public function resetForm()
    {
        $this->reset([
            'colisId', 'nom', 'description', 'poids', 'longueur', 'largeur', 'hauteur',
            'expediteur_nom', 'expediteur_email', 'expediteur_phone', 'expediteur_societe',
            'expediteur_matricule_fiscale', 'destinataire_nom', 'destinataire_prenom',
            'destinataire_phone', 'destinataire_adresse', 'destinataire_ville',
            'destinataire_cin_3_derniers_chiffres', 'depot_source_id', 'depot_actuel_id',
            'depot_destination_id', 'camion_id', 'photo_reception', 'commentaire_reception'
        ]);
        $this->expediteur_phone_code = '+216';
        $this->destinataire_phone_code = '+216';
    }

    /**
     * Liste des codes pays pour le formulaire
     */
    public function getCountryCodesProperty(): array
    {
        return [
            // Afrique du Nord
            '+216' => '🇹🇳 Tunisie (+216)',
            '+213' => '🇩🇿 Algérie (+213)',
            '+212' => '🇲🇦 Maroc (+212)',
            '+218' => '🇱🇾 Libye (+218)',
            '+20'  => '🇪🇬 Égypte (+20)',
            
            // Moyen-Orient
            '+966' => '🇸🇦 Arabie Saoudite (+966)',
            '+971' => '🇦🇪 Émirats (+971)',
            '+974' => '🇶🇦 Qatar (+974)',
            '+965' => '🇰🇼 Koweït (+965)',
            '+973' => '🇧🇭 Bahreïn (+973)',
            '+968' => '🇴🇲 Oman (+968)',
            '+962' => '🇯🇴 Jordanie (+962)',
            '+961' => '🇱🇧 Liban (+961)',
            '+970' => '🇵🇸 Palestine (+970)',
            
            // Europe
            '+33'  => '🇫🇷 France (+33)',
            '+49'  => '🇩🇪 Allemagne (+49)',
            '+39'  => '🇮🇹 Italie (+39)',
            '+34'  => '🇪🇸 Espagne (+34)',
            '+44'  => '🇬🇧 UK (+44)',
            '+32'  => '🇧🇪 Belgique (+32)',
            '+41'  => '🇨🇭 Suisse (+41)',
            '+31'  => '🇳🇱 Pays-Bas (+31)',
            '+90'  => '🇹🇷 Turquie (+90)',
            
            // Amérique
            '+1'   => '🇺🇸 USA/Canada (+1)',
            
            // Afrique
            '+221' => '🇸🇳 Sénégal (+221)',
            '+225' => '🇨🇮 Côte d\'Ivoire (+225)',
            '+237' => '🇨🇲 Cameroun (+237)',
        ];
    }

    public function render()
    {
        $colis = Colis::with(['admin', 'depotActuel', 'camion', 'livreur'])
            ->when($this->search, function ($query) {
                $query->where('code_colis', 'like', '%' . $this->search . '%')
                    ->orWhere('destinataire_nom', 'like', '%' . $this->search . '%')
                    ->orWhere('expediteur_nom', 'like', '%' . $this->search . '%');
            })
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        $depots = Depot::where('actif', true)->get();
        $camions = Camion::with('livreur')->where('statut', 'disponible')->get();

        return view('livewire.admin.colis-management', [
            'colis' => $colis,
            'depots' => $depots,
            'camions' => $camions,
        ]);
    }

    /**
     * Envoyer les SMS de tracking après création du colis
     */
    protected function sendTrackingSms($colis)
    {
        try {
            $smsService = new SmsService();
            $smsResults = ['expediteur' => false, 'destinataire' => false];

            // SMS à l'expéditeur
            if (!empty($colis->expediteur_phone)) {
                $smsResults['expediteur'] = $smsService->sendToExpediteur(
                    $colis->expediteur_phone,
                    $colis->code_colis,
                    $colis->nom
                );
                Log::info('SMS Expéditeur envoyé', [
                    'phone' => $colis->expediteur_phone,
                    'result' => $smsResults['expediteur']
                ]);
            }

            // SMS au destinataire
            if (!empty($colis->destinataire_phone)) {
                $smsResults['destinataire'] = $smsService->sendToDestinataire(
                    $colis->destinataire_phone,
                    $colis->code_colis,
                    $colis->nom,
                    $colis->expediteur_nom
                );
                Log::info('SMS Destinataire envoyé', [
                    'phone' => $colis->destinataire_phone,
                    'result' => $smsResults['destinataire']
                ]);
            }

            // Message flash selon les résultats
            $smsMsg = [];
            if ($smsResults['expediteur']) $smsMsg[] = 'Expéditeur ✅';
            if ($smsResults['destinataire']) $smsMsg[] = 'Destinataire ✅';

            if (!empty($smsMsg)) {
                session()->flash('sms_status', '📱 SMS envoyé à: ' . implode(', ', $smsMsg));
            } else {
                session()->flash('sms_error', '⚠️ Les SMS n\'ont pas pu être envoyés');
            }
        } catch (\Exception $e) {
            Log::error('Erreur envoi SMS Colis: ' . $e->getMessage());
            session()->flash('sms_error', '⚠️ Erreur envoi SMS: ' . $e->getMessage());
        }
    }
}
