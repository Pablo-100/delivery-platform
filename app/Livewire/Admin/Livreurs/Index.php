<?php

namespace App\Livewire\Admin\Livreurs;

use Livewire\Component;

class Index extends Component
{
    public $nom, $prenom, $email, $password, $camion_id;
    public $isModalOpen = false;

    protected $rules = [
        'nom' => 'required',
        'prenom' => 'required',
        'email' => 'required|email|unique:users,email',
        'password' => 'required|min:6',
        'camion_id' => 'nullable|exists:camions,id',
    ];

    public function render()
    {
        $livreurs = \App\Models\User::where('role', 'livreur')
            ->where(function($q) {
                $q->where('company_info', auth()->id())
                  ->orWhereIn('camion_id', auth()->user()->camionsOwned->pluck('id'));
            })
            ->get();
            
        // Logique stricte : On ne propose que les camions LIBRES ou celui déjà possédé par le user en cours d'édition
        // Un camion est libre si aucun user ne l'a (camion_id sur user)
        // OU on regarde camion->livreur_id si synchronisé.
        // On va se baser sur : un camion est dispo s'il n'est pas dans la liste des camions pris.
        
        $camionsPrisIds = \App\Models\User::whereNotNull('camion_id')->pluck('camion_id')->toArray();
        
        $camions = auth()->user()->camionsOwned->filter(function($camion) use ($camionsPrisIds) {
            // Règle 0 : Camion doit être EN SERVICE
            if ($camion->statut !== 'en_service') {
                return false;
            }

            // Si c'est le camion actuel du user qu'on édite, on le garde
            if ($this->user_id && $this->camion_id == $camion->id) {
                return true;
            }
            // Sinon, il ne doit pas être pris
            return !in_array($camion->id, $camionsPrisIds);
        });

        return view('livewire.admin.livreurs.index', compact('livreurs', 'camions'));
    }

    public $user_id;

    public function edit($id)
    {
        $user = \App\Models\User::findOrFail($id);
        $this->user_id = $id;
        $this->nom = $user->nom;
        $this->prenom = $user->prenom;
        $this->email = $user->email;
        $this->camion_id = $user->camion_id;
        
        $this->openModal();
    }

    public function create()
    {
        $this->resetInputFields();
        $this->openModal();
    }

    public function openModal()
    {
        $this->isModalOpen = true;
    }

    public function closeModal()
    {
        $this->isModalOpen = false;
    }

    public function store()
    {
        $this->validate([
            'nom' => 'required',
            'prenom' => 'required',
            'email' => 'required|email|unique:users,email,' . $this->user_id,
            'password' => $this->user_id ? 'nullable|min:6' : 'required|min:6',
            'camion_id' => 'nullable|exists:camions,id',
        ]);

        // Vérification Statut Camion (Backend Security)
        if ($this->camion_id) {
            $camionCheck = \App\Models\Camion::find($this->camion_id);
            if ($camionCheck && $camionCheck->statut !== 'en_service') {
                session()->flash('error', 'Impossible : Le camion ' . $camionCheck->immatriculation . ' est ' . $camionCheck->statut . '.');
                return;
            }
        }

        // 1. Gestion de l'ancien camion si changement
        if ($this->user_id) {
            $currentUser = \App\Models\User::find($this->user_id);
            if ($currentUser->camion_id && $currentUser->camion_id != $this->camion_id) {
                // Il quitte son ancien camion -> On libère le camion
                $ancienCamion = \App\Models\Camion::find($currentUser->camion_id);
                if ($ancienCamion) {
                    $ancienCamion->update(['livreur_id' => null]);
                }
            }
        }

        // 2. Gestion du nouveau camion (Vérif ultime s'il est déjà pris par un autre pdt ce temps)
        if ($this->camion_id) {
            $occupant = \App\Models\User::where('camion_id', $this->camion_id)
                                        ->where('id', '!=', $this->user_id)
                                        ->first();
            if ($occupant) {
                // Erreur : Camion déjà pris ! (Ne devrait pas arriver avec le filtre mais sécurité)
                // On peut soit bloquer, soit forcer le détachement de l'autre.
                // On va bloquer pour éviter la confusion.
                session()->flash('error', 'Ce camion est déjà assigné à ' . $occupant->nom);
                return;
            }
        }

        $data = [
            'nom' => $this->nom,
            'prenom' => $this->prenom,
            'email' => $this->email,
            'role' => 'livreur',
            'company_info' => auth()->id(),
            'camion_id' => $this->camion_id ?: null,
        ];

        if ($this->password) {
            $data['password'] = \Illuminate\Support\Facades\Hash::make($this->password);
        }

        $user = \App\Models\User::updateOrCreate(['id' => $this->user_id], $data);

        // 3. Sync Camion -> update livreur_id
        if ($this->camion_id) {
            \App\Models\Camion::where('id', $this->camion_id)->update(['livreur_id' => $user->id]);
        }
        // Si on a enlevé le camion (null), on a déjà géré le détachement plus haut ? non
        if (!$this->camion_id && $this->user_id) {
            // Assurer que l'ancien camion est libre (si on passe de Camion A à Null)
             // ... fait implicitement car on n'a pas mis à jour de nouveau camion, mais faut nettoyer l'ancien lien coté Camion table
             // C'est géré par le bloc 1 si on quitte un camion.
        }

        session()->flash('message', $this->user_id ? 'Livreur mis à jour.' : 'Livreur créé.');

        $this->closeModal();
        $this->resetInputFields();
    }

    private function resetInputFields()
    {
        $this->nom = '';
        $this->prenom = '';
        $this->email = '';
        $this->password = '';
        $this->camion_id = '';
        $this->user_id = null;
    }

    public function delete($id)
    {
        \App\Models\User::find($id)->delete();
        session()->flash('message', 'Livreur supprimé.');
    }
}
