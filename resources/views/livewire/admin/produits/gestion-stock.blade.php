<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Messages Flash -->
        @if (session()->has('message'))
            <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg">
                ✅ {{ session('message') }}
            </div>
        @endif
        @if (session()->has('sms_sent'))
            <div class="mb-4 p-4 bg-blue-100 border border-blue-400 text-blue-700 rounded-lg flex items-center gap-2">
                📱 {{ session('sms_sent') }}
            </div>
        @endif
        @if (session()->has('error'))
            <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg">
                ❌ {{ session('error') }}
            </div>
        @endif

        <!-- Header avec actions -->
        <div class="mb-6 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                <h2 class="text-2xl font-bold text-gray-800">📦 Gestion des Colis & Affectation Flotte</h2>
                <p class="text-sm text-gray-600">Affectez les colis en stockage à vos camions</p>
            </div>
            <a href="{{ route('admin.produits.create') }}" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition">
                + Nouveau Colis
            </a>
        </div>

        <!-- Filtres et Recherche -->
        <div class="bg-white p-4 rounded-lg shadow-sm mb-6">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <!-- Recherche -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">🔍 Rechercher</label>
                    <input type="text" wire:model.live="search" placeholder="Nom, QR, destinataire, destination..." 
                        class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                </div>

                <!-- Filtre statut -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">📊 Statut</label>
                    <select wire:model.live="filterStatut" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="">Tous les statuts</option>
                        <option value="stockage">📦 En Stockage</option>
                        <option value="prepare">🔧 Préparé</option>
                        <option value="en_route">🚚 En Route</option>
                        <option value="livre">✅ Livré</option>
                    </select>
                </div>

                <!-- Compteur -->
                <div class="flex items-end">
                    <div class="bg-indigo-50 p-3 rounded-lg w-full text-center">
                        <span class="text-2xl font-bold text-indigo-600">{{ $produits->count() }}</span>
                        <span class="text-sm text-indigo-700 block">colis trouvés</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Affectation en masse -->
        @if(count($selectedProduits) > 0)
            <div class="bg-yellow-50 border border-yellow-300 p-4 rounded-lg mb-6 flex flex-col md:flex-row justify-between items-center gap-4">
                <div>
                    <span class="font-bold text-yellow-800">{{ count($selectedProduits) }} colis sélectionné(s)</span>
                </div>
                <div class="flex items-center gap-4">
                    <select wire:model="camion_id" class="border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="">-- Choisir un camion --</option>
                        @foreach($camions as $camion)
                            <option value="{{ $camion->id }}">
                                {{ $camion->immatriculation }} - {{ $camion->modele }}
                            </option>
                        @endforeach
                    </select>
                    <button wire:click="assignMultiple" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition">
                        🚚 Affecter à la flotte
                    </button>
                </div>
            </div>
        @endif

        <!-- Liste des colis -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left text-gray-500">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                            <tr>
                                <th scope="col" class="px-4 py-3">
                                    <input type="checkbox" class="rounded" 
                                        wire:click="$set('selectedProduits', $event.target.checked ? {{ $produits->where('statut', 'stockage')->pluck('id')->toJson() }} : [])">
                                </th>
                                <th scope="col" class="px-4 py-3">Colis</th>
                                <th scope="col" class="px-4 py-3">Destinataire</th>
                                <th scope="col" class="px-4 py-3">Poids</th>
                                <th scope="col" class="px-4 py-3">Dépôt</th>
                                <th scope="col" class="px-4 py-3">Statut</th>
                                <th scope="col" class="px-4 py-3">Camion</th>
                                <th scope="col" class="px-4 py-3">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($produits as $produit)
                                <tr class="bg-white border-b hover:bg-gray-50 {{ $produit->statut === 'stockage' ? 'bg-yellow-50' : '' }} {{ $produit->statut === 'en_attente_reception' ? 'bg-purple-50' : '' }}">
                                    <!-- Checkbox -->
                                    <td class="px-4 py-4">
                                        @if($produit->statut === 'stockage')
                                            <input type="checkbox" wire:model.live="selectedProduits" value="{{ $produit->id }}" class="rounded text-indigo-600">
                                        @endif
                                    </td>

                                    <!-- Colis info -->
                                    <td class="px-4 py-4">
                                        <div class="font-medium text-gray-900">{{ $produit->nom }}</div>
                                        <div class="text-xs text-gray-400 font-mono">{{ Str::limit($produit->qr_code, 15) }}</div>
                                        <div class="text-xs text-gray-500">→ {{ $produit->destination }}</div>
                                        @if($produit->statut === 'en_attente_reception')
                                            <span class="mt-1 inline-flex items-center px-2 py-0.5 rounded text-xs font-bold bg-purple-200 text-purple-800">
                                                📥 En attente de réception
                                            </span>
                                        @endif
                                    </td>

                                    <!-- Destinataire -->
                                    <td class="px-4 py-4">
                                        <div class="font-medium">{{ $produit->destinataire_nom }} {{ $produit->destinataire_prenom }}</div>
                                        @if($produit->destinataire_phone)
                                            <div class="text-xs text-gray-500">📞 {{ $produit->destinataire_phone }}</div>
                                        @endif
                                        @if($produit->destinataire_ville)
                                            <div class="text-xs text-gray-500">📍 {{ $produit->destinataire_ville }}</div>
                                        @endif
                                    </td>

                                    <!-- Poids -->
                                    <td class="px-4 py-4">
                                        @if($produit->poids_facturable)
                                            <span class="font-bold text-green-700">{{ number_format($produit->poids_facturable, 2) }} kg</span>
                                            <div class="text-xs text-gray-400">Facturable</div>
                                        @elseif($produit->poids)
                                            <span>{{ number_format($produit->poids, 2) }} kg</span>
                                        @else
                                            <span class="text-gray-400">-</span>
                                        @endif
                                    </td>

                                    <!-- Dépôt -->
                                    <td class="px-4 py-4">
                                        @if($produit->depotSource)
                                            <span class="px-2 py-1 bg-purple-100 text-purple-700 rounded text-xs">
                                                {{ $produit->depotSource->code }}
                                            </span>
                                        @else
                                            <span class="text-gray-400">-</span>
                                        @endif
                                    </td>

                                    <!-- Statut -->
                                    <td class="px-4 py-4">
                                        @php
                                            $statutColors = [
                                                'stockage' => 'bg-yellow-500',
                                                'valide' => 'bg-gray-500',
                                                'prepare' => 'bg-blue-500',
                                                'en_route' => 'bg-orange-500',
                                                'en_attente_reception' => 'bg-purple-500',
                                                'livre' => 'bg-green-500',
                                            ];
                                            $statutIcons = [
                                                'stockage' => '📦',
                                                'valide' => '✓',
                                                'prepare' => '🔧',
                                                'en_route' => '🚚',
                                                'livre' => '✅',
                                            ];
                                        @endphp
                                        <span class="px-2 py-1 rounded text-xs text-white {{ $statutColors[$produit->statut] ?? 'bg-gray-500' }}">
                                            {{ $statutIcons[$produit->statut] ?? '' }} {{ ucfirst(str_replace('_', ' ', $produit->statut)) }}
                                        </span>
                                    </td>

                                    <!-- Camion -->
                                    <td class="px-4 py-4">
                                        @if($produit->camion)
                                            <div class="text-sm font-medium text-indigo-700">
                                                🚛 {{ $produit->camion->immatriculation }}
                                            </div>
                                            <div class="text-xs text-gray-500">{{ $produit->camion->modele }}</div>
                                        @else
                                            <span class="text-gray-400 italic">Non affecté</span>
                                        @endif
                                    </td>

                                    <!-- Actions -->
                                    <td class="px-4 py-4">
                                        <div class="flex flex-col gap-1">
                                            <a href="{{ route('admin.produits.detail', $produit->id) }}" 
                                                class="text-xs text-center text-blue-600 hover:text-blue-900 border border-blue-600 rounded px-2 py-1 hover:bg-blue-50 font-medium">
                                                👁️ Voir / Modifier
                                            </a>
                                            
                                            @if($produit->statut === 'stockage')
                                                <button wire:click="openAssignModal({{ $produit->id }})" 
                                                    class="text-xs text-center text-green-600 hover:text-green-900 border border-green-600 rounded px-2 py-1 hover:bg-green-50">
                                                    🚚 Affecter
                                                </button>
                                            @elseif($produit->statut === 'prepare')
                                                <button wire:click="removeFromFleet({{ $produit->id }})" 
                                                    class="text-xs text-center text-orange-600 hover:text-orange-900 border border-orange-600 rounded px-2 py-1 hover:bg-orange-50">
                                                    ↩️ Retirer
                                                </button>
                                            @endif
                                            
                                            <a href="{{ route('admin.produits.qr', $produit->id) }}" target="_blank" 
                                                class="text-xs text-center text-indigo-600 hover:text-indigo-900 border border-indigo-600 rounded px-2 py-1 hover:bg-indigo-50">
                                                🏷️ QR PDF
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="px-6 py-12 text-center text-gray-500">
                                        <div class="text-4xl mb-2">📭</div>
                                        Aucun colis trouvé
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal d'affectation -->
    @if($showModal && $produitToAssign)
        <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-white rounded-lg shadow-xl max-w-md w-full mx-4">
                <div class="p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">🚚 Affecter à une flotte</h3>
                    
                    <div class="bg-gray-50 p-4 rounded-lg mb-4">
                        <div class="font-medium">{{ $produitToAssign->nom }}</div>
                        <div class="text-sm text-gray-600">→ {{ $produitToAssign->destination }}</div>
                        <div class="text-sm text-gray-600">📬 {{ $produitToAssign->destinataire_nom }} {{ $produitToAssign->destinataire_prenom }}</div>
                        @if($produitToAssign->poids_facturable)
                            <div class="text-sm text-green-600 font-medium mt-1">⚖️ {{ number_format($produitToAssign->poids_facturable, 2) }} kg</div>
                        @endif
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Choisir un camion:</label>
                        <select wire:model="camion_id" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="">-- Sélectionner --</option>
                            @foreach($camions as $camion)
                                <option value="{{ $camion->id }}">
                                    {{ $camion->immatriculation }} - {{ $camion->modele }} 
                                    ({{ $camion->statut == 'disponible' ? '✅ Disponible' : '🚛 En Service' }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="flex justify-end gap-3">
                        <button wire:click="closeModal" class="px-4 py-2 text-gray-600 hover:text-gray-900">
                            Annuler
                        </button>
                        <button wire:click="assignToCamion" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                            ✓ Affecter
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
