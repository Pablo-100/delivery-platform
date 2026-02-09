<div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <div class="bg-gradient-to-r from-indigo-900 via-blue-900 to-indigo-900 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="flex items-center justify-between">
                <div>
                    <div class="flex items-center mb-2">
                        <a href="{{ route('super-admin.dashboard') }}" class="text-indigo-200 hover:text-white mr-3">
                            ← Retour
                        </a>
                    </div>
                    <h1 class="text-3xl font-bold flex items-center">
                        <span class="mr-3">🚛</span>
                        Historique: {{ $camion->immatriculation }}
                    </h1>
                    <p class="mt-2 text-indigo-200">{{ $camion->modele }} | Propriétaire: {{ $camion->admin?->nom }} {{ $camion->admin?->prenom }}</p>
                </div>
                <div class="flex items-center space-x-4">
                    <div class="flex items-center bg-white/10 rounded-lg p-2">
                        <input type="date" wire:model.live="dateDebut" 
                            class="bg-transparent border-none text-white text-sm focus:ring-0">
                        <span class="text-indigo-300 mx-2">→</span>
                        <input type="date" wire:model.live="dateFin" 
                            class="bg-transparent border-none text-white text-sm focus:ring-0">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <!-- Statistiques -->
        @if($stats)
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-7 gap-4 mb-8">
            <div class="bg-white rounded-xl p-5 shadow-sm">
                <p class="text-gray-500 text-sm">Total Tournées</p>
                <p class="text-3xl font-bold text-indigo-600">{{ $stats['total_tournees'] }}</p>
            </div>
            <div class="bg-white rounded-xl p-5 shadow-sm">
                <p class="text-gray-500 text-sm">Colis Transportés</p>
                <p class="text-3xl font-bold text-blue-600">{{ $stats['total_colis_transportes'] }}</p>
            </div>
            <div class="bg-white rounded-xl p-5 shadow-sm">
                <p class="text-gray-500 text-sm">Colis Livrés</p>
                <p class="text-3xl font-bold text-green-600">{{ $stats['total_colis_livres'] }}</p>
            </div>
            <div class="bg-white rounded-xl p-5 shadow-sm">
                <p class="text-gray-500 text-sm">Distance Totale</p>
                <p class="text-3xl font-bold text-orange-600">{{ number_format($stats['total_distance_km'], 0) }}</p>
                <p class="text-xs text-gray-400">km</p>
            </div>
            <div class="bg-white rounded-xl p-5 shadow-sm">
                <p class="text-gray-500 text-sm">Livreurs Différents</p>
                <p class="text-3xl font-bold text-purple-600">{{ $stats['livreurs_differents'] }}</p>
            </div>
            <div class="bg-white rounded-xl p-5 shadow-sm">
                <p class="text-gray-500 text-sm">Taux Réussite</p>
                <p class="text-3xl font-bold text-emerald-600">{{ $stats['taux_reussite'] }}%</p>
            </div>
            <div class="bg-white rounded-xl p-5 shadow-sm">
                <p class="text-gray-500 text-sm">Dernière Tournée</p>
                <p class="text-sm font-bold text-gray-600">{{ $stats['derniere_tournee']?->format('d/m/Y') ?? '-' }}</p>
            </div>
        </div>
        @endif

        <!-- Filtres -->
        <div class="bg-white rounded-xl shadow-sm p-4 mb-6">
            <div class="flex items-center gap-4">
                <label class="text-sm text-gray-600">Statut:</label>
                <select wire:model.live="filterStatut" class="border-gray-200 rounded-lg text-sm">
                    <option value="">Tous</option>
                    <option value="en_cours">En cours</option>
                    <option value="termine">Terminé</option>
                    <option value="annule">Annulé</option>
                </select>
            </div>
        </div>

        <!-- Timeline des tournées -->
        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
            <div class="p-6 border-b border-gray-100">
                <h3 class="text-lg font-bold text-gray-800">📋 Historique Complet des Tournées</h3>
                <p class="text-sm text-gray-500 mt-1">Tous les livreurs qui ont conduit ce camion</p>
            </div>

            <div class="p-6">
                @forelse($historiques as $historique)
                    <div class="mb-6 pb-6 border-b border-gray-100 last:border-0">
                        <!-- En-tête tournée -->
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex items-center">
                                <div class="w-3 h-3 rounded-full mr-3
                                    {{ $historique->statut === 'termine' ? 'bg-green-500' : ($historique->statut === 'en_cours' ? 'bg-orange-500 animate-pulse' : 'bg-gray-400') }}">
                                </div>
                                <div>
                                    <h4 class="text-lg font-bold text-gray-900">
                                        👷 {{ $historique->livreur?->nom ?? 'Inconnu' }} {{ $historique->livreur?->prenom ?? '' }}
                                    </h4>
                                    <p class="text-xs text-gray-500">
                                        Tournée: <span class="font-mono">{{ $historique->tournee_code ?? 'N/A' }}</span>
                                        @if($historique->livreur)
                                            | {{ $historique->livreur->email }}
                                        @endif
                                    </p>
                                </div>
                            </div>
                            <div>
                                <button wire:click="viewDetail({{ $historique->id }})" 
                                    class="px-4 py-2 bg-indigo-100 text-indigo-700 rounded-lg hover:bg-indigo-200 transition text-sm font-medium">
                                    📊 Détails Complets
                                </button>
                            </div>
                        </div>

                        <!-- Informations principales -->
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-4 bg-gray-50 p-4 rounded-lg">
                            <div>
                                <p class="text-xs text-gray-500">🕐 Début</p>
                                <p class="font-bold text-gray-900">{{ $historique->heure_debut?->format('d/m/Y') }}</p>
                                <p class="text-sm text-gray-600">{{ $historique->heure_debut?->format('H:i') }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500">🏁 Fin</p>
                                @if($historique->heure_fin)
                                    <p class="font-bold text-gray-900">{{ $historique->heure_fin->format('d/m/Y') }}</p>
                                    <p class="text-sm text-gray-600">{{ $historique->heure_fin->format('H:i') }}</p>
                                @else
                                    <p class="text-orange-600 font-bold">En cours...</p>
                                @endif
                            </div>
                            <div>
                                <p class="text-xs text-gray-500">⏱️ Durée</p>
                                <p class="font-bold text-gray-900">{{ $historique->getDureeFormatee() }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500">📏 Distance</p>
                                <p class="font-bold text-gray-900">{{ $historique->distance_km ?? '?' }} km</p>
                            </div>
                        </div>

                        <!-- Statistiques colis -->
                        <div class="grid grid-cols-3 gap-3 mb-4">
                            <div class="bg-blue-50 p-3 rounded-lg">
                                <p class="text-xs text-blue-600">📦 Transportés</p>
                                <p class="text-2xl font-bold text-blue-700">{{ $historique->nombre_colis }}</p>
                            </div>
                            <div class="bg-green-50 p-3 rounded-lg">
                                <p class="text-xs text-green-600">✅ Livrés</p>
                                <p class="text-2xl font-bold text-green-700">{{ $historique->colis_livres }}</p>
                            </div>
                            <div class="bg-orange-50 p-3 rounded-lg">
                                <p class="text-xs text-orange-600">⏳ En cours</p>
                                <p class="text-2xl font-bold text-orange-700">{{ $historique->colis_en_cours }}</p>
                            </div>
                        </div>

                        <!-- Destinations visitées -->
                        @if($historique->depots_visites && count($historique->depots_visites) > 0)
                            <div class="mb-4">
                                <p class="text-sm font-medium text-gray-700 mb-2">📍 Lieux parcourus:</p>
                                <div class="flex flex-wrap gap-2">
                                    @foreach($historique->depots_visites as $depot)
                                        <span class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-sm font-medium">{{ $depot }}</span>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <!-- Aperçu des produits -->
                        @php $produits = $historique->getProduits(); @endphp
                        @if($produits->count() > 0)
                            <div>
                                <p class="text-sm font-medium text-gray-700 mb-2">📦 Chargement ({{ $produits->count() }} produits):</p>
                                <div class="flex flex-wrap gap-2">
                                    @foreach($produits->take(6) as $p)
                                        <span class="px-3 py-1 bg-amber-100 text-amber-700 rounded-full text-xs" 
                                            title="{{ $p->destination }}">
                                            {{ $p->nom }} → {{ Str::limit($p->destination, 20) }}
                                        </span>
                                    @endforeach
                                    @if($produits->count() > 6)
                                        <span class="px-3 py-1 bg-gray-200 text-gray-600 rounded-full text-xs font-bold">
                                            +{{ $produits->count() - 6 }} autres
                                        </span>
                                    @endif
                                </div>
                            </div>
                        @endif
                    </div>
                @empty
                    <div class="text-center py-12 text-gray-500">
                        <div class="text-6xl mb-4">📭</div>
                        <p class="text-lg">Aucune tournée enregistrée pour cette période</p>
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            <div class="p-4 border-t border-gray-100">
                {{ $historiques->links() }}
            </div>
        </div>
    </div>

    <!-- Modal détail complet -->
    @if($showDetailModal && $selectedHistorique)
        <div class="fixed inset-0 bg-black/50 flex items-center justify-center z-50 p-4" wire:click="closeModal">
            <div class="bg-white rounded-2xl max-w-4xl w-full max-h-[90vh] overflow-y-auto" wire:click.stop>
                <div class="sticky top-0 bg-gradient-to-r from-indigo-600 to-blue-600 text-white p-6 rounded-t-2xl">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-2xl font-bold">📊 Détails Complets de la Tournée</h3>
                            <p class="text-indigo-100 text-sm mt-1">{{ $selectedHistorique->tournee_code ?? 'N/A' }}</p>
                        </div>
                        <button wire:click="closeModal" class="text-white hover:bg-white/20 rounded-lg p-2 transition">
                            ✕
                        </button>
                    </div>
                </div>

                <div class="p-6">
                    @php
                        $detailProduits = $selectedHistorique->getProduitsWithDetails();
                        $destinations = $selectedHistorique->getDestinationsWithCount();
                    @endphp

                    <!-- Informations complètes -->
                    <div class="grid grid-cols-2 gap-4 mb-6">
                        <div class="bg-indigo-50 p-4 rounded-lg">
                            <p class="text-xs text-indigo-600 mb-1">🚛 Camion</p>
                            <p class="font-bold text-gray-900">{{ $camion->immatriculation }}</p>
                            <p class="text-sm text-gray-600">{{ $camion->modele }}</p>
                        </div>
                        <div class="bg-purple-50 p-4 rounded-lg">
                            <p class="text-xs text-purple-600 mb-1">👷 Conducteur</p>
                            <p class="font-bold text-gray-900">{{ $selectedHistorique->livreur?->nom }} {{ $selectedHistorique->livreur?->prenom }}</p>
                            <p class="text-sm text-gray-600">{{ $selectedHistorique->livreur?->email }}</p>
                        </div>
                    </div>

                    <!-- Période détaillée -->
                    <div class="mb-6">
                        <h4 class="font-bold text-gray-900 mb-3">⏰ Période</h4>
                        <div class="grid grid-cols-3 gap-3">
                            <div class="bg-gray-50 p-3 rounded-lg">
                                <p class="text-xs text-gray-500">Début</p>
                                <p class="font-bold">{{ $selectedHistorique->heure_debut?->format('d/m/Y H:i:s') }}</p>
                            </div>
                            <div class="bg-gray-50 p-3 rounded-lg">
                                <p class="text-xs text-gray-500">Fin</p>
                                <p class="font-bold">{{ $selectedHistorique->heure_fin?->format('d/m/Y H:i:s') ?? 'En cours' }}</p>
                            </div>
                            <div class="bg-gray-50 p-3 rounded-lg">
                                <p class="text-xs text-gray-500">Durée totale</p>
                                <p class="font-bold">{{ $selectedHistorique->getDureeFormatee() }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Destinations avec statistiques -->
                    @if($destinations->count() > 0)
                        <div class="mb-6">
                            <h4 class="font-bold text-gray-900 mb-3">📍 Itinéraire avec détails</h4>
                            <div class="space-y-2">
                                @foreach($destinations as $dest)
                                    <div class="flex items-center justify-between bg-green-50 p-3 rounded-lg">
                                        <div>
                                            <p class="font-bold text-gray-900">{{ $dest['nom'] }}</p>
                                        </div>
                                        <div class="flex gap-3 text-sm">
                                            <span class="px-2 py-1 bg-blue-100 text-blue-700 rounded">Total: {{ $dest['total'] }}</span>
                                            <span class="px-2 py-1 bg-green-100 text-green-700 rounded">Livrés: {{ $dest['livres'] }}</span>
                                            <span class="px-2 py-1 bg-orange-100 text-orange-700 rounded">En cours: {{ $dest['en_cours'] }}</span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- Liste complète des produits -->
                    @if($detailProduits->count() > 0)
                        <div class="mb-6">
                            <h4 class="font-bold text-gray-900 mb-3">📦 Tous les produits transportés ({{ $detailProduits->count() }})</h4>
                            <div class="overflow-x-auto">
                                <table class="w-full text-sm">
                                    <thead class="bg-gray-100">
                                        <tr>
                                            <th class="px-3 py-2 text-left">Produit</th>
                                            <th class="px-3 py-2 text-left">Destinataire</th>
                                            <th class="px-3 py-2 text-left">Destination</th>
                                            <th class="px-3 py-2 text-left">Poids</th>
                                            <th class="px-3 py-2 text-left">Statut</th>
                                            <th class="px-3 py-2 text-left">Date Livraison</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-100">
                                        @foreach($detailProduits as $prod)
                                            <tr>
                                                <td class="px-3 py-2 font-medium">{{ $prod['nom'] }}</td>
                                                <td class="px-3 py-2">{{ $prod['destinataire'] }}</td>
                                                <td class="px-3 py-2">{{ $prod['ville'] }}</td>
                                                <td class="px-3 py-2">{{ $prod['poids'] }} kg</td>
                                                <td class="px-3 py-2">
                                                    <span class="px-2 py-1 rounded text-xs
                                                        {{ $prod['statut'] === 'livre' ? 'bg-green-100 text-green-700' : 
                                                           ($prod['statut'] === 'en_route' ? 'bg-orange-100 text-orange-700' : 'bg-gray-100 text-gray-700') }}">
                                                        {{ ucfirst($prod['statut']) }}
                                                    </span>
                                                </td>
                                                <td class="px-3 py-2 text-xs">
                                                    {{ $prod['date_livraison'] ? $prod['date_livraison']->format('d/m/Y H:i') : '-' }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @endif

                    <!-- Notes -->
                    @if($selectedHistorique->notes)
                        <div class="bg-yellow-50 p-4 rounded-lg">
                            <p class="text-sm font-medium text-gray-700 mb-1">💬 Notes</p>
                            <p class="text-gray-600">{{ $selectedHistorique->notes }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    @endif
</div>
