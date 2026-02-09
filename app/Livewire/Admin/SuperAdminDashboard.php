<?php

namespace App\Livewire\Admin;

use App\Models\Produit;
use App\Models\User;
use App\Models\Camion;
use App\Models\Depot;
use App\Models\HistoriqueLivreur;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Hash;

#[Layout('layouts.app')]
class SuperAdminDashboard extends Component
{
    use WithPagination;

    public $activeTab = 'overview';
    public $dateDebut;
    public $dateFin;
    public $searchProduit = '';
    public $searchLivreur = '';
    public $searchCamion = '';
    public $searchDepot = '';
    public $searchAdmin = '';
    public $filterStatut = '';
    public $filterAdmin = '';
    
    // Modal détail
    public $showDetailModal = false;
    public $selectedProduit = null;
    public $selectedLivreur = null;
    public $selectedCamion = null;
    public $selectedHistorique = null;
    
    // Modal création dépôt
    public $showDepotModal = false;
    public $editingDepotId = null;
    public $depotForm = [
        'code' => '',
        'nom' => '',
        'adresse' => '',
        'ville' => '',
        'phone' => '',
        'admin_id' => null,
        'actif' => true,
    ];
    
    // Modal création admin
    public $showAdminModal = false;
    public $editingAdminId = null;
    public $adminForm = [
        'nom' => '',
        'prenom' => '',
        'email' => '',
        'password' => '',
        'depot_id' => null,
    ];

    public function mount()
    {
        $this->dateDebut = now()->subDays(30)->format('Y-m-d');
        $this->dateFin = now()->format('Y-m-d');
    }

    public function updatingSearchProduit()
    {
        $this->resetPage();
    }

    public function updatingSearchLivreur()
    {
        $this->resetPage();
    }

    public function viewProduitDetail($id)
    {
        $this->selectedProduit = Produit::with([
            'admin', 
            'camion.livreur', 
            'camion.conducteur',
            'etapes',
            'depotSource',
            'depotDestination',
            'depotActuel'
        ])->find($id);
        $this->showDetailModal = true;
    }

    public function viewLivreurDetail($id)
    {
        $this->selectedLivreur = User::with([
            'camion.produits',
            'camionsOwned',
            'historiqueTournees.camion'
        ])->withCount([
            'produits as total_livraisons' => fn($q) => $q->where('statut', 'livre')
        ])->find($id);
        $this->showDetailModal = true;
        $this->selectedProduit = null;
        $this->selectedCamion = null;
    }

    public function viewCamionDetail($id)
    {
        $this->selectedCamion = Camion::with([
            'admin',
            'livreur',
            'conducteur',
            'produits',
            'historiques.livreur'
        ])->find($id);
        $this->showDetailModal = true;
        $this->selectedProduit = null;
        $this->selectedLivreur = null;
    }

    public function viewHistoriqueDetail($id)
    {
        $this->selectedHistorique = HistoriqueLivreur::with([
            'livreur',
            'camion'
        ])->find($id);
        $this->showDetailModal = true;
        $this->selectedProduit = null;
        $this->selectedLivreur = null;
        $this->selectedCamion = null;
    }

    public function closeModal()
    {
        $this->showDetailModal = false;
        $this->selectedProduit = null;
        $this->selectedLivreur = null;
        $this->selectedCamion = null;
        $this->selectedHistorique = null;
    }

    // =====================
    // GESTION DES DÉPÔTS
    // =====================
    
    public function openDepotModal($depotId = null)
    {
        $this->resetDepotForm();
        
        if ($depotId) {
            $depot = Depot::find($depotId);
            if ($depot) {
                $this->editingDepotId = $depotId;
                $this->depotForm = [
                    'code' => $depot->code,
                    'nom' => $depot->nom,
                    'adresse' => $depot->adresse,
                    'ville' => $depot->ville,
                    'phone' => $depot->phone ?? '',
                    'admin_id' => $depot->admin_id,
                    'actif' => $depot->actif,
                ];
            }
        }
        
        $this->showDepotModal = true;
    }
    
    public function resetDepotForm()
    {
        $this->editingDepotId = null;
        $this->depotForm = [
            'code' => '',
            'nom' => '',
            'adresse' => '',
            'ville' => '',
            'phone' => '',
            'admin_id' => null,
            'actif' => true,
        ];
    }
    
    public function closeDepotModal()
    {
        $this->showDepotModal = false;
        $this->resetDepotForm();
    }
    
    public function saveDepot()
    {
        $this->validate([
            'depotForm.code' => 'required|string|max:10|unique:depots,code,' . $this->editingDepotId,
            'depotForm.nom' => 'required|string|max:255',
            'depotForm.adresse' => 'required|string|max:500',
            'depotForm.ville' => 'required|string|max:100',
            'depotForm.phone' => 'nullable|string|max:20',
            'depotForm.admin_id' => 'nullable|exists:users,id',
        ]);
        
        $data = [
            'code' => $this->depotForm['code'],
            'nom' => $this->depotForm['nom'],
            'adresse' => $this->depotForm['adresse'],
            'ville' => $this->depotForm['ville'],
            'phone' => $this->depotForm['phone'] ?: null,
            'admin_id' => $this->depotForm['admin_id'] ?: null,
            'actif' => $this->depotForm['actif'],
        ];
        
        if ($this->editingDepotId) {
            Depot::find($this->editingDepotId)->update($data);
            session()->flash('message', 'Dépôt modifié avec succès!');
        } else {
            Depot::create($data);
            session()->flash('message', 'Dépôt créé avec succès!');
        }
        
        $this->closeDepotModal();
    }
    
    public function deleteDepot($id)
    {
        $depot = Depot::find($id);
        if ($depot) {
            $depot->delete();
            session()->flash('message', 'Dépôt supprimé avec succès!');
        }
    }
    
    public function toggleDepotStatus($id)
    {
        $depot = Depot::find($id);
        if ($depot) {
            $depot->update(['actif' => !$depot->actif]);
        }
    }

    // =====================
    // GESTION DES ADMINS
    // =====================
    
    public function openAdminModal($adminId = null)
    {
        $this->resetAdminForm();
        
        if ($adminId) {
            $admin = User::where('role', 'admin')->find($adminId);
            if ($admin) {
                $this->editingAdminId = $adminId;
                // Trouver le dépôt assigné à cet admin
                $depot = Depot::where('admin_id', $adminId)->first();
                $this->adminForm = [
                    'nom' => $admin->nom,
                    'prenom' => $admin->prenom,
                    'email' => $admin->email,
                    'password' => '', // On laisse vide pour l'édition
                    'depot_id' => $depot?->id,
                ];
            }
        }
        
        $this->showAdminModal = true;
    }
    
    public function resetAdminForm()
    {
        $this->editingAdminId = null;
        $this->adminForm = [
            'nom' => '',
            'prenom' => '',
            'email' => '',
            'password' => '',
            'depot_id' => null,
        ];
    }
    
    public function closeAdminModal()
    {
        $this->showAdminModal = false;
        $this->resetAdminForm();
    }
    
    public function saveAdmin()
    {
        $rules = [
            'adminForm.nom' => 'required|string|max:255',
            'adminForm.prenom' => 'required|string|max:255',
            'adminForm.email' => 'required|email|unique:users,email,' . $this->editingAdminId,
            'adminForm.depot_id' => 'nullable|exists:depots,id',
        ];
        
        // Le mot de passe est requis seulement pour la création
        if (!$this->editingAdminId) {
            $rules['adminForm.password'] = 'required|string|min:8';
        } else {
            $rules['adminForm.password'] = 'nullable|string|min:8';
        }
        
        $this->validate($rules);
        
        $data = [
            'nom' => $this->adminForm['nom'],
            'prenom' => $this->adminForm['prenom'],
            'email' => $this->adminForm['email'],
            'role' => 'admin',
        ];
        
        if ($this->editingAdminId) {
            $admin = User::find($this->editingAdminId);
            
            // Mettre à jour le mot de passe seulement s'il est fourni
            if (!empty($this->adminForm['password'])) {
                $data['password'] = Hash::make($this->adminForm['password']);
            }
            
            $admin->update($data);
            
            // Mettre à jour l'assignation du dépôt
            // D'abord, retirer l'admin de tous les dépôts
            Depot::where('admin_id', $this->editingAdminId)->update(['admin_id' => null]);
            
            // Puis assigner au nouveau dépôt si sélectionné
            if ($this->adminForm['depot_id']) {
                Depot::find($this->adminForm['depot_id'])->update(['admin_id' => $this->editingAdminId]);
            }
            
            session()->flash('message', 'Admin modifié avec succès!');
        } else {
            $data['password'] = Hash::make($this->adminForm['password']);
            $admin = User::create($data);
            
            // Assigner au dépôt si sélectionné
            if ($this->adminForm['depot_id']) {
                Depot::find($this->adminForm['depot_id'])->update(['admin_id' => $admin->id]);
            }
            
            session()->flash('message', 'Admin créé avec succès!');
        }
        
        $this->closeAdminModal();
    }
    
    public function deleteAdmin($id)
    {
        $admin = User::where('role', 'admin')->find($id);
        if ($admin) {
            // Retirer l'admin des dépôts
            Depot::where('admin_id', $id)->update(['admin_id' => null]);
            $admin->delete();
            session()->flash('message', 'Admin supprimé avec succès!');
        }
    }
    
    public function assignAdminToDepot($adminId, $depotId)
    {
        // D'abord retirer l'admin de tous les dépôts
        Depot::where('admin_id', $adminId)->update(['admin_id' => null]);
        
        // Puis assigner au nouveau dépôt
        if ($depotId) {
            Depot::find($depotId)->update(['admin_id' => $adminId]);
        }
        
        session()->flash('message', 'Admin assigné au dépôt avec succès!');
    }

    public function getStatsProperty()
    {
        $dateRange = [$this->dateDebut . ' 00:00:00', $this->dateFin . ' 23:59:59'];
        
        return [
            'total_produits' => Produit::count(),
            'produits_periode' => Produit::whereBetween('created_at', $dateRange)->count(),
            'en_stockage' => Produit::where('statut', 'stockage')->count(),
            'valides' => Produit::where('statut', 'valide')->count(),
            'prepares' => Produit::where('statut', 'prepare')->count(),
            'en_route' => Produit::where('statut', 'en_route')->count(),
            'livres' => Produit::where('statut', 'livre')->count(),
            'livres_periode' => Produit::where('statut', 'livre')->whereBetween('updated_at', $dateRange)->count(),
            'total_camions' => Camion::count(),
            'camions_actifs' => Camion::whereHas('produits', fn($q) => $q->whereIn('statut', ['prepare', 'en_route']))->count(),
            'total_livreurs' => User::where('role', 'livreur')->count(),
            'livreurs_actifs' => User::where('role', 'livreur')->whereNotNull('camion_id')->count(),
            'total_admins' => User::where('role', 'admin')->count(),
            'total_depots' => Depot::count(),
            'depots_actifs' => Depot::where('actif', true)->count(),
            'revenu_estime' => Produit::where('statut', 'livre')->count() * 15,
            'revenu_periode' => Produit::where('statut', 'livre')->whereBetween('updated_at', $dateRange)->count() * 15,
        ];
    }

    public function getProduitsProperty()
    {
        return Produit::with(['admin', 'camion.livreur', 'camion.conducteur'])
            ->when($this->searchProduit, function($q) {
                $q->where(function($query) {
                    $query->where('qr_code', 'like', '%' . $this->searchProduit . '%')
                        ->orWhere('nom', 'like', '%' . $this->searchProduit . '%')
                        ->orWhere('destinataire_nom', 'like', '%' . $this->searchProduit . '%')
                        ->orWhere('destinataire_phone', 'like', '%' . $this->searchProduit . '%')
                        ->orWhere('destination', 'like', '%' . $this->searchProduit . '%');
                });
            })
            ->when($this->filterStatut, fn($q) => $q->where('statut', $this->filterStatut))
            ->when($this->filterAdmin, fn($q) => $q->where('admin_id', $this->filterAdmin))
            ->whereBetween('created_at', [$this->dateDebut . ' 00:00:00', $this->dateFin . ' 23:59:59'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);
    }

    public function getLivreursProperty()
    {
        return User::where('role', 'livreur')
            ->with(['camion'])
            ->withCount([
                'produits as produits_livres' => fn($q) => $q->where('statut', 'livre')
            ])
            ->when($this->searchLivreur, function($q) {
                $q->where(function($query) {
                    $query->where('nom', 'like', '%' . $this->searchLivreur . '%')
                        ->orWhere('prenom', 'like', '%' . $this->searchLivreur . '%')
                        ->orWhere('email', 'like', '%' . $this->searchLivreur . '%');
                });
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);
    }

    public function getCamionsProperty()
    {
        return Camion::with(['admin', 'livreur', 'conducteur', 'historiques'])
            ->withCount(['produits', 'produits as produits_en_cours' => fn($q) => $q->whereIn('statut', ['prepare', 'en_route'])])
            ->withCount('historiques as total_tournees')
            ->when($this->searchCamion, function($q) {
                $q->where('immatriculation', 'like', '%' . $this->searchCamion . '%');
            })
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function getAdminsProperty()
    {
        return User::where('role', 'admin')
            ->with(['depotsGeres'])
            ->withCount(['produits', 'produits as produits_livres' => fn($q) => $q->where('statut', 'livre')])
            ->withCount('camionsOwned')
            ->when($this->searchAdmin, function($q) {
                $q->where(function($query) {
                    $query->where('nom', 'like', '%' . $this->searchAdmin . '%')
                        ->orWhere('prenom', 'like', '%' . $this->searchAdmin . '%')
                        ->orWhere('email', 'like', '%' . $this->searchAdmin . '%');
                });
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10, ['*'], 'adminsPage');
    }
    
    public function getDepotsProperty()
    {
        return Depot::with(['admin'])
            ->withCount(['colisSource', 'colisActuel', 'colisDestination'])
            ->when($this->searchDepot, function($q) {
                $q->where(function($query) {
                    $query->where('code', 'like', '%' . $this->searchDepot . '%')
                        ->orWhere('nom', 'like', '%' . $this->searchDepot . '%')
                        ->orWhere('ville', 'like', '%' . $this->searchDepot . '%');
                });
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10, ['*'], 'depotsPage');
    }
    
    public function getAvailableAdminsProperty()
    {
        // Admins sans dépôt assigné
        $assignedAdminIds = Depot::whereNotNull('admin_id')->pluck('admin_id')->toArray();
        
        return User::where('role', 'admin')
            ->whereNotIn('id', $assignedAdminIds)
            ->orderBy('nom')
            ->get();
    }
    
    public function getAllDepotsProperty()
    {
        return Depot::where('actif', true)->orderBy('nom')->get();
    }

    public function getHistoriquesProperty()
    {
        return HistoriqueLivreur::with(['livreur', 'camion'])
            ->whereBetween('heure_debut', [$this->dateDebut . ' 00:00:00', $this->dateFin . ' 23:59:59'])
            ->orderBy('heure_debut', 'desc')
            ->paginate(15, ['*'], 'historiquePage');
    }

    public function getRecentActivityProperty()
    {
        return Produit::with(['admin', 'camion.conducteur'])
            ->orderBy('updated_at', 'desc')
            ->take(10)
            ->get();
    }

    public function render()
    {
        return view('livewire.admin.super-admin-dashboard', [
            'stats' => $this->stats,
            'produits' => $this->produits,
            'livreurs' => $this->livreurs,
            'camions' => $this->camions,
            'admins' => $this->admins,
            'depots' => $this->depots,
            'historiques' => $this->historiques,
            'recentActivity' => $this->recentActivity,
            'allAdmins' => User::where('role', 'admin')->get(),
            'availableAdmins' => $this->availableAdmins,
            'allDepots' => $this->allDepots,
        ]);
    }
}
