<?php

namespace App\Livewire\Admin;

use App\Models\Camion;
use App\Models\HistoriqueLivreur;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;

#[Layout('layouts.app')]
class HistoireCamion extends Component
{
    use WithPagination;

    public $camionId;
    public $camion;
    public $dateDebut;
    public $dateFin;
    public $filterStatut = '';
    public $showDetailModal = false;
    public $selectedHistorique = null;

    public function mount($camionId)
    {
        $this->camionId = $camionId;
        $this->camion = Camion::with(['admin', 'livreur'])->findOrFail($camionId);
        $this->dateDebut = now()->subDays(90)->format('Y-m-d');
        $this->dateFin = now()->format('Y-m-d');
    }

    public function viewDetail($historiqueId)
    {
        $this->selectedHistorique = HistoriqueLivreur::with(['livreur', 'camion'])
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
        $query = HistoriqueLivreur::where('camion_id', $this->camionId)
            ->with(['livreur'])
            ->whereBetween('heure_debut', [$this->dateDebut . ' 00:00:00', $this->dateFin . ' 23:59:59']);

        if ($this->filterStatut) {
            $query->where('statut', $this->filterStatut);
        }

        return $query->orderBy('heure_debut', 'desc')->paginate(20);
    }

    public function getStatsProperty()
    {
        return $this->camion->getStatsCamion();
    }

    public function render()
    {
        return view('livewire.admin.histoire-camion', [
            'historiques' => $this->historiques,
            'stats' => $this->stats,
        ]);
    }
}
