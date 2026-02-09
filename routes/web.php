<?php

use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome');

// Route publique de tracking
Route::get('/tracking', \App\Livewire\TrackingPublic::class)->name('tracking');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

// Routes Super Admin
Route::middleware(['auth', 'role:super_admin'])->prefix('super-admin')->name('super-admin.')->group(function () {
    Route::get('dashboard', \App\Livewire\Admin\SuperAdminDashboard::class)->name('dashboard');
    Route::get('livreur/{livreurId}/history', \App\Livewire\Admin\HistoireLivreur::class)->name('livreur.history');
    Route::get('camion/{camionId}/history', \App\Livewire\Admin\HistoireCamion::class)->name('camion.history');
    Route::view('produits', 'super-admin.produits')->name('produits');
    Route::view('camions', 'super-admin.camions')->name('camions');
    Route::view('livreurs', 'super-admin.livreurs')->name('livreurs');
});

// Routes Admin
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::view('dashboard', 'admin.dashboard')->name('dashboard');
    Route::view('produits', 'admin.produits.index')->name('produits.index');
    Route::get('produits/gestion', \App\Livewire\Admin\Produits\GestionStock::class)->name('produits.gestion');
    Route::get('produits/{id}/detail', \App\Livewire\Admin\Produits\DetailProduit::class)->name('produits.detail');
    Route::view('produits/nouveau', 'admin.produits.create')->name('produits.create');
    Route::get('produits/qr/{id}', function (\Illuminate\Http\Request $request, $id) {
        $produit = \App\Models\Produit::findOrFail($id);
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('admin.produits.pdf-qr', compact('produit'));
        
        if ($request->has('dl')) {
            return $pdf->download("qr-{$produit->qr_code}.pdf");
        }
        
        return $pdf->stream("qr-{$produit->qr_code}.pdf");
    })->name('produits.qr');

    Route::get('produits/qr-image/{id}', function ($id) {
        $produit = \App\Models\Produit::findOrFail($id);
        $qr = \SimpleSoftwareIO\QrCode\Facades\QrCode::format('svg')->size(300)->generate($produit->qr_code);
        
        return response($qr)
            ->header('Content-Type', 'image/svg+xml')
            ->header('Content-Disposition', 'attachment; filename="qr-'.$produit->qr_code.'.svg"');
    })->name('produits.qr.image');
    Route::view('camions', 'admin.camions.index')->name('camions.index');
    Route::view('camions/{id}/produits', 'admin.camions.produits')->name('camions.produits');
    Route::view('livreurs', 'admin.livreurs.index')->name('livreurs.index');
});

// Routes Livreur
Route::middleware(['auth', 'role:livreur'])->prefix('livreur')->name('livreur.')->group(function () {
    Route::view('dashboard', 'livreur.dashboard')->name('dashboard');
    Route::get('livraisons', \App\Livewire\Livreur\LivraisonManagement::class)->name('livraisons');
    Route::get('scan', \App\Livewire\Livreur\Scan::class)->name('scan');
    Route::get('produit/{produit}', \App\Livewire\Livreur\ProduitDetails::class)->name('produit.details');
});

require __DIR__.'/auth.php';
