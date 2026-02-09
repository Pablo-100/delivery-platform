<?php

namespace App\Livewire\Livreur;

use Livewire\Component;
use Livewire\Attributes\Layout;

class Scan extends Component
{
    public $error = null;

    #[Layout('layouts.app')]
    public function render()
    {
        return view('livewire.livreur.scan');
    }

    public function handleScan($qrCode)
    {
        // Chercher le produit par son code QR
        $produit = \App\Models\Produit::where('qr_code', $qrCode)->first();

        if ($produit) {
            // Vérifier si le produit appartient au camion du livreur
            $userCamionId = auth()->user()->camion_id;
            
            if ($produit->camion_id === $userCamionId) {
                return redirect()->route('livreur.produit.details', $produit);
            } else {
                // Produit existe mais n'est pas dans le camion
                $this->error = "Ce colis n'appartient pas à votre camion ! (Camion assigné : " . ($produit->camion ? $produit->camion->immatriculation : 'Aucun') . ")";
                
                // Optionnel : dispatch event pour son d'erreur
                $this->dispatch('scan-error');
            }
        } else {
            $this->error = "Code QR invalide ou colis non trouvé.";
            $this->dispatch('scan-error');
        }
    }
}
