<?php

namespace App\Livewire\Admin;

use App\Models\User;
use App\Models\HistoriqueLivreur;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;

#[Layout('layouts.app')]
class HistoireLivreur extends Component
{
    use WithPagination;

    public $livreurId;
    public $livreur;
    public $dateDebut;
    public $dateFin;
    public $filterStatut = '';
    public $showDetailModal = false;
    public $selectedHistorique = null;

    public function mount($livreurId)
    {
        $this->livreurId = $livreurId;
        $this->livreur = User::with(['camion'])->findOrFail($livreurId);
        
        if (!$this->livreur->isLivreur()) {
            abort(404, 'Utilisateur n\'est pas un livreur');
        }

        $this->dateDebut = now()->subDays(90)->format('Y-m-d');
        $this->dateFin = now()->format('Y-m-d');
    }

    public function viewDetail($historiqueId)
    {
        $this->selectedHistorique = HistoriqueLivreur::with(['camion', 'camion.admin'])
            ->findOrFail($historiqueId);
        $this->showDetailModal = true;
    }

    public function closeModal()
    {
        $this->showDetailModal = false;
        $this->selectedHistorique = null;
    }

    public function getHistoriquesProperty()
    {
        $query = HistoriqueLivreur::where('livreur_id', $this->livreurId)
            ->with(['camion', 'camion.admin'])
            ->whereBetween('heure_debut', [$this->dateDebut . ' 00:00:00', $this->dateFin . ' 23:59:59']);

        if ($this->filterStatut) {
            $query->where('statut', $this->filterStatut);
        }

        return $query->orderBy('heure_debut', 'desc')->paginate(20);
    }

    public function getStatsProperty()
    {
        return $this->livreur->getStatsLivreur();
    }

    public function render()
    {
        return view('livewire.admin.histoire-livreur', [
            'historiques' => $this->historiques,
            'stats' => $this->stats,
        ]);
    }
}
