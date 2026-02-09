<?php

namespace App\Livewire\Admin\Camions;

use Livewire\Component;
use App\Models\Camion;

class Index extends Component
{
    public $camions, $immatriculation, $modele, $capacite, $statut, $camion_id;
    public $isModalOpen = false;

    protected $rules = [
        'immatriculation' => 'required',
        'modele' => 'required',
        'capacite' => 'required|numeric',
        'statut' => 'required',
    ];

    public function render()
    {
        $this->camions = Camion::where('admin_id', auth()->id())->get();
        return view('livewire.admin.camions.index');
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

    private function resetInputFields()
    {
        $this->immatriculation = '';
        $this->modele = '';
        $this->capacite = '';
        $this->statut = 'en_service';
        $this->camion_id = null;
    }

    public function store()
    {
        $this->validate();
    
        Camion::updateOrCreate(['id' => $this->camion_id], [
            'immatriculation' => $this->immatriculation,
            'modele' => $this->modele,
            'capacite' => $this->capacite,
            'statut' => $this->statut,
            'admin_id' => auth()->id(),
        ]);

        session()->flash('message', $this->camion_id ? 'Camion mis à jour.' : 'Camion créé avec succès.');
        
        $this->closeModal();
        $this->resetInputFields();
    }

    public function edit($id)
    {
        $camion = Camion::findOrFail($id);
        
        // Vérifier apparteance
        if($camion->admin_id !== auth()->id()) {
            abort(403);
        }

        $this->camion_id = $id;
        $this->immatriculation = $camion->immatriculation;
        $this->modele = $camion->modele;
        $this->capacite = $camion->capacite;
        $this->statut = $camion->statut;
    
        $this->openModal();
    }

    public function delete($id)
    {
        $camion = Camion::find($id);
        if($camion->admin_id === auth()->id()) {
            $camion->delete();
            session()->flash('message', 'Camion supprimé.');
        }
    }
}
