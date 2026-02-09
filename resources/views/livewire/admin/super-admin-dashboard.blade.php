<div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <div class="bg-gradient-to-r from-slate-900 via-purple-900 to-slate-900 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                <div>
                    <h1 class="text-3xl font-bold flex items-center">
                        <span class="mr-3">👑</span>
                        Super Admin Dashboard
                    </h1>
                    <p class="mt-2 text-purple-200">Vue d'ensemble complète de la plateforme</p>
                </div>
                <div class="mt-4 md:mt-0 flex items-center space-x-4">
                    <div class="flex items-center bg-white/10 rounded-lg p-2">
                        <input type="date" wire:model.live="dateDebut" 
                            class="bg-transparent border-none text-white text-sm focus:ring-0">
                        <span class="text-purple-300 mx-2">→</span>
                        <input type="date" wire:model.live="dateFin" 
                            class="bg-transparent border-none text-white text-sm focus:ring-0">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <!-- KPIs Financiers & Opérationnels -->
        <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4 mb-8">
            <!-- Revenu Total -->
            <div class="col-span-2 bg-gradient-to-br from-emerald-500 to-green-600 rounded-2xl p-6 text-white shadow-lg">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-emerald-100 text-sm font-medium">💰 Revenu Total</p>
                        <p class="text-4xl font-bold mt-2">{{ number_format($stats['revenu_estime'], 2) }}</p>
                        <p class="text-emerald-200 text-sm">TND</p>
                    </div>
                    <div class="text-6xl opacity-30">💵</div>
                </div>
                <div class="mt-4 pt-4 border-t border-white/20">
                    <span class="text-emerald-100 text-sm">Période: {{ number_format($stats['revenu_periode'], 2) }} TND</span>
                </div>
            </div>

            <!-- Total Produits -->
            <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100 hover:shadow-md transition">
                <div class="flex items-center justify-between">
                    <span class="text-3xl">📦</span>
                    <span class="px-2 py-1 bg-blue-100 text-blue-700 text-xs font-bold rounded-full">Total</span>
                </div>
                <p class="text-3xl font-bold text-gray-900 mt-3">{{ $stats['total_produits'] }}</p>
                <p class="text-gray-500 text-sm">Produits</p>
                <p class="text-blue-600 text-xs mt-1">+{{ $stats['produits_periode'] }} cette période</p>
            </div>

            <!-- Livrés -->
            <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100 hover:shadow-md transition">
                <div class="flex items-center justify-between">
                    <span class="text-3xl">✅</span>
                    <span class="px-2 py-1 bg-green-100 text-green-700 text-xs font-bold rounded-full">Succès</span>
                </div>
                <p class="text-3xl font-bold text-green-600 mt-3">{{ $stats['livres'] }}</p>
                <p class="text-gray-500 text-sm">Livrés</p>
                <p class="text-green-600 text-xs mt-1">+{{ $stats['livres_periode'] }} cette période</p>
            </div>

            <!-- En Route -->
            <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100 hover:shadow-md transition">
                <div class="flex items-center justify-between">
                    <span class="text-3xl">🚚</span>
                    <span class="animate-pulse px-2 py-1 bg-orange-100 text-orange-700 text-xs font-bold rounded-full">Live</span>
                </div>
                <p class="text-3xl font-bold text-orange-600 mt-3">{{ $stats['en_route'] }}</p>
                <p class="text-gray-500 text-sm">En Route</p>
            </div>

            <!-- Préparés -->
            <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100 hover:shadow-md transition">
                <div class="flex items-center justify-between">
                    <span class="text-3xl">📋</span>
                </div>
                <p class="text-3xl font-bold text-blue-600 mt-3">{{ $stats['prepares'] }}</p>
                <p class="text-gray-500 text-sm">Préparés</p>
            </div>

            <!-- Stockage -->
            <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100 hover:shadow-md transition">
                <div class="flex items-center justify-between">
                    <span class="text-3xl">🏪</span>
                </div>
                <p class="text-3xl font-bold text-gray-600 mt-3">{{ $stats['en_stockage'] }}</p>
                <p class="text-gray-500 text-sm">En Stockage</p>
            </div>

            <!-- Camions -->
            <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100 hover:shadow-md transition">
                <div class="flex items-center justify-between">
                    <span class="text-3xl">🚛</span>
                </div>
                <p class="text-3xl font-bold text-indigo-600 mt-3">{{ $stats['camions_actifs'] }}/{{ $stats['total_camions'] }}</p>
                <p class="text-gray-500 text-sm">Camions Actifs</p>
            </div>

            <!-- Livreurs -->
            <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100 hover:shadow-md transition">
                <div class="flex items-center justify-between">
                    <span class="text-3xl">👷</span>
                </div>
                <p class="text-3xl font-bold text-purple-600 mt-3">{{ $stats['livreurs_actifs'] }}/{{ $stats['total_livreurs'] }}</p>
                <p class="text-gray-500 text-sm">Livreurs Actifs</p>
            </div>

            <!-- Admins -->
            <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100 hover:shadow-md transition">
                <div class="flex items-center justify-between">
                    <span class="text-3xl">👔</span>
                </div>
                <p class="text-3xl font-bold text-slate-600 mt-3">{{ $stats['total_admins'] }}</p>
                <p class="text-gray-500 text-sm">Admins</p>
            </div>
            
            <!-- Dépôts -->
            <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100 hover:shadow-md transition">
                <div class="flex items-center justify-between">
                    <span class="text-3xl">🏢</span>
                </div>
                <p class="text-3xl font-bold text-cyan-600 mt-3">{{ $stats['depots_actifs'] }}/{{ $stats['total_depots'] }}</p>
                <p class="text-gray-500 text-sm">Dépôts Actifs</p>
            </div>
        </div>

        <!-- Navigation Tabs -->
        <div class="bg-white rounded-2xl shadow-sm mb-6 overflow-hidden">
            <div class="flex overflow-x-auto">
                @foreach(['overview' => '📊 Vue Générale', 'produits' => '📦 Produits', 'livreurs' => '👷 Livreurs', 'camions' => '🚛 Flotte', 'depots' => '🏢 Dépôts', 'admins' => '👔 Admins'] as $tab => $label)
                    <button wire:click="$set('activeTab', '{{ $tab }}')" 
                        class="px-6 py-4 font-medium text-sm whitespace-nowrap border-b-2 transition-colors
                            {{ $activeTab === $tab ? 'border-purple-600 text-purple-600 bg-purple-50' : 'border-transparent text-gray-500 hover:text-gray-700 hover:bg-gray-50' }}">
                        {{ $label }}
                    </button>
                @endforeach
            </div>
        </div>

        <!-- Tab Content -->
        @if($activeTab === 'overview')
            <!-- Vue Générale -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Graphique Statuts -->
                <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm p-6">
                    <h3 class="text-lg font-bold text-gray-800 mb-4">📈 Répartition des Statuts</h3>
                    <div class="grid grid-cols-4 gap-4">
                        <div class="text-center p-4 bg-gray-50 rounded-xl">
                            <div class="w-16 h-16 mx-auto rounded-full bg-gray-200 flex items-center justify-center text-2xl font-bold text-gray-600">
                                {{ $stats['en_stockage'] }}
                            </div>
                            <p class="mt-2 text-sm text-gray-600">Stockage</p>
                        </div>
                        <div class="text-center p-4 bg-blue-50 rounded-xl">
                            <div class="w-16 h-16 mx-auto rounded-full bg-blue-200 flex items-center justify-center text-2xl font-bold text-blue-600">
                                {{ $stats['prepares'] }}
                            </div>
                            <p class="mt-2 text-sm text-blue-600">Préparés</p>
                        </div>
                        <div class="text-center p-4 bg-orange-50 rounded-xl">
                            <div class="w-16 h-16 mx-auto rounded-full bg-orange-200 flex items-center justify-center text-2xl font-bold text-orange-600">
                                {{ $stats['en_route'] }}
                            </div>
                            <p class="mt-2 text-sm text-orange-600">En Route</p>
                        </div>
                        <div class="text-center p-4 bg-green-50 rounded-xl">
                            <div class="w-16 h-16 mx-auto rounded-full bg-green-200 flex items-center justify-center text-2xl font-bold text-green-600">
                                {{ $stats['livres'] }}
                            </div>
                            <p class="mt-2 text-sm text-green-600">Livrés</p>
                        </div>
                    </div>
                    
                    <!-- Progress bar -->
                    <div class="mt-6">
                        <div class="flex h-4 rounded-full overflow-hidden bg-gray-100">
                            @php
                                $total = $stats['total_produits'] ?: 1;
                            @endphp
                            <div class="bg-gray-400" style="width: {{ ($stats['en_stockage'] / $total) * 100 }}%"></div>
                            <div class="bg-blue-500" style="width: {{ ($stats['prepares'] / $total) * 100 }}%"></div>
                            <div class="bg-orange-500" style="width: {{ ($stats['en_route'] / $total) * 100 }}%"></div>
                            <div class="bg-green-500" style="width: {{ ($stats['livres'] / $total) * 100 }}%"></div>
                        </div>
                        <div class="flex justify-between mt-2 text-xs text-gray-500">
                            <span>Taux de livraison: {{ $stats['total_produits'] > 0 ? round(($stats['livres'] / $stats['total_produits']) * 100, 1) : 0 }}%</span>
                            <span>{{ $stats['total_produits'] }} total</span>
                        </div>
                    </div>
                </div>

                <!-- Activité Récente -->
                <div class="bg-white rounded-2xl shadow-sm p-6">
                    <h3 class="text-lg font-bold text-gray-800 mb-4">⚡ Activité Récente</h3>
                    <div class="space-y-3 max-h-80 overflow-y-auto">
                        @foreach($recentActivity as $activity)
                            <div class="flex items-center p-3 bg-gray-50 rounded-xl hover:bg-gray-100 transition cursor-pointer"
                                 wire:click="viewProduitDetail({{ $activity->id }})">
                                <div class="w-2 h-2 rounded-full mr-3 flex-shrink-0
                                    {{ $activity->statut === 'livre' ? 'bg-green-500' : 
                                       ($activity->statut === 'en_route' ? 'bg-orange-500' : 
                                       ($activity->statut === 'prepare' ? 'bg-blue-500' : 'bg-gray-400')) }}">
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-gray-900 truncate">{{ $activity->nom }}</p>
                                    <p class="text-xs text-gray-500 truncate">{{ $activity->destination }}</p>
                                </div>
                                <div class="text-right">
                                    <span class="text-xs font-bold px-2 py-1 rounded-full
                                        {{ $activity->statut === 'livre' ? 'bg-green-100 text-green-700' : 
                                           ($activity->statut === 'en_route' ? 'bg-orange-100 text-orange-700' : 
                                           ($activity->statut === 'prepare' ? 'bg-blue-100 text-blue-700' : 'bg-gray-100 text-gray-700')) }}">
                                        {{ ucfirst(str_replace('_', ' ', $activity->statut)) }}
                                    </span>
                                    <p class="text-xs text-gray-400 mt-1">{{ $activity->updated_at->diffForHumans() }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Top Performers -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                <!-- Top Livreurs -->
                <div class="bg-white rounded-2xl shadow-sm p-6">
                    <h3 class="text-lg font-bold text-gray-800 mb-4">🏆 Top Livreurs</h3>
                    <div class="space-y-3">
                        @foreach($livreurs->take(5) as $index => $livreur)
                            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-xl">
                                <div class="flex items-center">
                                    <div class="w-8 h-8 rounded-full flex items-center justify-center text-white font-bold text-sm
                                        {{ $index === 0 ? 'bg-yellow-500' : ($index === 1 ? 'bg-gray-400' : ($index === 2 ? 'bg-amber-600' : 'bg-gray-300')) }}">
                                        {{ $index + 1 }}
                                    </div>
                                    <div class="ml-3">
                                        <p class="font-medium text-gray-900">{{ $livreur->nom }} {{ $livreur->prenom }}</p>
                                        <p class="text-xs text-gray-500">{{ $livreur->camion?->immatriculation ?? 'Sans camion' }}</p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p class="text-lg font-bold text-green-600">{{ $livreur->produits_livres ?? 0 }}</p>
                                    <p class="text-xs text-gray-500">livrés</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Top Admins -->
                <div class="bg-white rounded-2xl shadow-sm p-6">
                    <h3 class="text-lg font-bold text-gray-800 mb-4">👔 Performance Admins</h3>
                    <div class="space-y-3">
                        @foreach($admins->take(5) as $admin)
                            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-xl">
                                <div>
                                    <p class="font-medium text-gray-900">{{ $admin->nom }} {{ $admin->prenom }}</p>
                                    <p class="text-xs text-gray-500">{{ $admin->company_info ?? 'Indépendant' }}</p>
                                </div>
                                <div class="text-right">
                                    <p class="text-sm"><span class="font-bold text-blue-600">{{ $admin->produits_count }}</span> créés</p>
                                    <p class="text-sm"><span class="font-bold text-green-600">{{ $admin->produits_livres }}</span> livrés</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

        @elseif($activeTab === 'produits')
            <!-- Liste Produits -->
            <div class="bg-white rounded-2xl shadow-sm overflow-hidden">
                <div class="p-6 border-b border-gray-100">
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                        <h3 class="text-lg font-bold text-gray-800">📦 Tous les Produits</h3>
                        <div class="flex flex-wrap gap-3">
                            <input type="text" wire:model.live.debounce.300ms="searchProduit" 
                                placeholder="🔍 Rechercher..." 
                                class="border-gray-300 rounded-lg text-sm focus:ring-purple-500 focus:border-purple-500">
                            <select wire:model.live="filterStatut" class="border-gray-300 rounded-lg text-sm">
                                <option value="">Tous les statuts</option>
                                <option value="stockage">Stockage</option>
                                <option value="valide">Validé</option>
                                <option value="prepare">Préparé</option>
                                <option value="en_route">En Route</option>
                                <option value="livre">Livré</option>
                            </select>
                            <select wire:model.live="filterAdmin" class="border-gray-300 rounded-lg text-sm">
                                <option value="">Tous les admins</option>
                                @foreach($allAdmins as $admin)
                                    <option value="{{ $admin->id }}">{{ $admin->nom }} {{ $admin->prenom }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50 text-xs uppercase text-gray-500">
                            <tr>
                                <th class="px-6 py-4 text-left">Produit</th>
                                <th class="px-6 py-4 text-left">Destinataire</th>
                                <th class="px-6 py-4 text-left">Admin</th>
                                <th class="px-6 py-4 text-left">Livreur / Camion</th>
                                <th class="px-6 py-4 text-left">Statut</th>
                                <th class="px-6 py-4 text-left">Créé</th>
                                <th class="px-6 py-4 text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($produits as $produit)
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="px-6 py-4">
                                        <div class="font-medium text-gray-900">{{ $produit->nom }}</div>
                                        <div class="text-xs text-gray-400 font-mono">{{ $produit->qr_code }}</div>
                                        <div class="text-xs text-gray-500">{{ $produit->poids }}kg</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm text-gray-900">{{ $produit->destinataire_nom }} {{ $produit->destinataire_prenom }}</div>
                                        <div class="text-xs text-gray-500">{{ $produit->destinataire_phone }}</div>
                                        <div class="text-xs text-gray-400">{{ Str::limit($produit->destination, 30) }}</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        @if($produit->admin)
                                            <div class="text-sm text-gray-900">{{ $produit->admin->nom }}</div>
                                            <div class="text-xs text-gray-500">{{ $produit->admin->company_info ?? '-' }}</div>
                                        @else
                                            <span class="text-gray-400">-</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        @if($produit->camion)
                                            <div class="flex items-center">
                                                <span class="text-lg mr-2">🚛</span>
                                                <div>
                                                    <div class="text-sm text-gray-900">{{ $produit->camion->immatriculation }}</div>
                                                    <div class="text-xs text-gray-500">
                                                        {{ $produit->camion->conducteur?->nom ?? $produit->camion->livreur?->nom ?? 'Sans chauffeur' }}
                                                    </div>
                                                </div>
                                            </div>
                                        @else
                                            <span class="text-gray-400 italic">En entrepôt</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="px-3 py-1 rounded-full text-xs font-bold
                                            {{ $produit->statut === 'livre' ? 'bg-green-100 text-green-700' : 
                                               ($produit->statut === 'en_route' ? 'bg-orange-100 text-orange-700' : 
                                               ($produit->statut === 'prepare' ? 'bg-blue-100 text-blue-700' : 
                                               ($produit->statut === 'valide' ? 'bg-indigo-100 text-indigo-700' : 'bg-gray-100 text-gray-700'))) }}">
                                            {{ ucfirst(str_replace('_', ' ', $produit->statut)) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-500">
                                        {{ $produit->created_at->format('d/m/Y H:i') }}
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <button wire:click="viewProduitDetail({{ $produit->id }})" 
                                            class="text-purple-600 hover:text-purple-800 font-medium text-sm">
                                            👁️ Détails
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                                        Aucun produit trouvé
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                <div class="p-4 border-t border-gray-100">
                    {{ $produits->links() }}
                </div>
            </div>

        @elseif($activeTab === 'livreurs')
            <!-- Liste Livreurs -->
            <div class="bg-white rounded-2xl shadow-sm overflow-hidden">
                <div class="p-6 border-b border-gray-100">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-bold text-gray-800">👷 Tous les Livreurs</h3>
                        <input type="text" wire:model.live.debounce.300ms="searchLivreur" 
                            placeholder="🔍 Rechercher livreur..." 
                            class="border-gray-300 rounded-lg text-sm focus:ring-purple-500 focus:border-purple-500">
                    </div>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 p-6">
                    @forelse($livreurs as $livreur)
                        <div class="border border-gray-200 rounded-xl p-5 hover:shadow-md transition cursor-pointer"
                             wire:click="viewLivreurDetail({{ $livreur->id }})">
                            <div class="flex items-start justify-between">
                                <div class="flex items-center">
                                    <div class="w-12 h-12 rounded-full bg-purple-100 flex items-center justify-center text-purple-600 font-bold text-lg">
                                        {{ strtoupper(substr($livreur->nom, 0, 1)) }}{{ strtoupper(substr($livreur->prenom, 0, 1)) }}
                                    </div>
                                    <div class="ml-3">
                                        <p class="font-bold text-gray-900">{{ $livreur->nom }} {{ $livreur->prenom }}</p>
                                        <p class="text-sm text-gray-500">{{ $livreur->email }}</p>
                                    </div>
                                </div>
                                @if($livreur->camion)
                                    <span class="px-2 py-1 bg-green-100 text-green-700 text-xs font-bold rounded-full">Actif</span>
                                @else
                                    <span class="px-2 py-1 bg-gray-100 text-gray-500 text-xs font-bold rounded-full">Inactif</span>
                                @endif
                            </div>
                            
                            <div class="mt-4 pt-4 border-t border-gray-100">
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-500">Camion:</span>
                                    <span class="font-medium">{{ $livreur->camion?->immatriculation ?? 'Aucun' }}</span>
                                </div>
                                <div class="flex justify-between text-sm mt-1">
                                    <span class="text-gray-500">Livraisons:</span>
                                    <span class="font-bold text-green-600">{{ $livreur->produits_livres ?? 0 }}</span>
                                </div>
                                <div class="mt-3">
                                    <a href="{{ route('super-admin.livreur.history', $livreur->id) }}" 
                                        class="block w-full text-center px-3 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition text-sm font-medium">
                                        📋 Historique Complet
                                    </a>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-full text-center py-12 text-gray-500">
                            Aucun livreur trouvé
                        </div>
                    @endforelse
                </div>
                
                <div class="p-4 border-t border-gray-100">
                    {{ $livreurs->links() }}
                </div>
            </div>

        @elseif($activeTab === 'camions')
            <!-- Liste Camions -->
            <div class="bg-white rounded-2xl shadow-sm overflow-hidden">
                <div class="p-6 border-b border-gray-100">
                    <h3 class="text-lg font-bold text-gray-800">🚛 Flotte de Camions</h3>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50 text-xs uppercase text-gray-500">
                            <tr>
                                <th class="px-6 py-4 text-left">Immatriculation</th>
                                <th class="px-6 py-4 text-left">Modèle</th>
                                <th class="px-6 py-4 text-left">Capacité</th>
                                <th class="px-6 py-4 text-left">Propriétaire (Admin)</th>
                                <th class="px-6 py-4 text-left">Conducteur</th>
                                <th class="px-6 py-4 text-left">Produits</th>
                                <th class="px-6 py-4 text-left">Statut</th>
                                <th class="px-6 py-4 text-left">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($camions as $camion)
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center">
                                            <span class="text-2xl mr-3">🚛</span>
                                            <span class="font-bold text-gray-900">{{ $camion->immatriculation }}</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-gray-600">{{ $camion->modele ?? '-' }}</td>
                                    <td class="px-6 py-4 text-gray-600">{{ $camion->capacite ?? '-' }} kg</td>
                                    <td class="px-6 py-4">
                                        @if($camion->admin)
                                            <div class="text-sm text-gray-900">{{ $camion->admin->nom }} {{ $camion->admin->prenom }}</div>
                                            <div class="text-xs text-gray-500">{{ $camion->admin->email }}</div>
                                        @else
                                            <span class="text-gray-400">-</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        @php $conducteur = $camion->conducteur ?? $camion->livreur; @endphp
                                        @if($conducteur)
                                            <div class="flex items-center">
                                                <div class="w-8 h-8 rounded-full bg-purple-100 flex items-center justify-center text-purple-600 font-bold text-xs mr-2">
                                                    {{ strtoupper(substr($conducteur->nom, 0, 1)) }}
                                                </div>
                                                <div>
                                                    <div class="text-sm text-gray-900">{{ $conducteur->nom }} {{ $conducteur->prenom }}</div>
                                                </div>
                                            </div>
                                        @else
                                            <span class="text-gray-400 italic">Sans chauffeur</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center">
                                            <span class="text-blue-600 font-bold">{{ $camion->produits_count }}</span>
                                            <span class="text-gray-400 mx-1">/</span>
                                            <span class="text-orange-600 font-bold">{{ $camion->produits_en_cours }}</span>
                                            <span class="text-xs text-gray-400 ml-1">en cours</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        @if($camion->produits_en_cours > 0)
                                            <span class="px-3 py-1 bg-green-100 text-green-700 text-xs font-bold rounded-full animate-pulse">
                                                🟢 En mission
                                            </span>
                                        @else
                                            <span class="px-3 py-1 bg-gray-100 text-gray-600 text-xs font-bold rounded-full">
                                                En attente
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        <a href="{{ route('super-admin.camion.history', $camion->id) }}" 
                                            class="inline-flex items-center px-3 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition text-sm font-medium">
                                            📜 Historique
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="px-6 py-12 text-center text-gray-500">
                                        Aucun camion enregistré
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        @elseif($activeTab === 'depots')
            <!-- Historique des Livreurs -->
            <div class="bg-white rounded-2xl shadow-sm overflow-hidden">
                <div class="p-6 border-b border-gray-100">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-bold text-gray-800">📋 Historique Complet des Livreurs</h3>
                        <p class="text-sm text-gray-500">Tous les trajets effectués par chaque livreur</p>
                    </div>
                </div>
                
                <div class="divide-y divide-gray-100">
                    @forelse($livreurs as $livreur)
                        @php
                            $historiquesDuLivreur = \App\Models\HistoriqueLivreur::where('livreur_id', $livreur->id)
                                ->with('camion')
                                ->orderBy('heure_debut', 'desc')
                                ->get();
                        @endphp
                        <div class="p-6">
                            <!-- En-tête Livreur -->
                            <div class="flex items-center justify-between mb-4">
                                <div class="flex items-center">
                                    <div class="w-14 h-14 rounded-full bg-gradient-to-br from-purple-500 to-pink-600 flex items-center justify-center text-white text-xl font-bold mr-4">
                                        {{ strtoupper(substr($livreur->nom, 0, 1)) }}{{ strtoupper(substr($livreur->prenom, 0, 1)) }}
                                    </div>
                                    <div>
                                        <h4 class="text-xl font-bold text-gray-900">{{ $livreur->nom }} {{ $livreur->prenom }}</h4>
                                        <p class="text-sm text-gray-500">{{ $livreur->email }}</p>
                                        <p class="text-xs text-gray-400">Camion actuel: {{ $livreur->camion?->immatriculation ?? 'Aucun' }}</p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <div class="text-2xl font-bold text-green-600">{{ $livreur->produits_livres ?? 0 }}</div>
                                    <div class="text-xs text-gray-500">livraisons réussies</div>
                                    <span class="px-3 py-1 bg-purple-100 text-purple-700 text-sm font-bold rounded-full mt-1 inline-block">
                                        {{ $historiquesDuLivreur->count() }} tournées
                                    </span>
                                </div>
                            </div>
                            
                            <!-- Timeline des camions conduits -->
                            @if($historiquesDuLivreur->count() > 0)
                                <div class="ml-7 border-l-2 border-purple-200 pl-6 space-y-4">
                                    @foreach($historiquesDuLivreur->take(5) as $historique)
                                        <div class="relative">
                                            <div class="absolute -left-[1.85rem] w-4 h-4 rounded-full 
                                                {{ $historique->statut === 'termine' ? 'bg-green-500' : ($historique->statut === 'en_cours' ? 'bg-orange-500 animate-pulse' : 'bg-gray-400') }}">
                                            </div>
                                            <div class="bg-gray-50 rounded-xl p-4 hover:bg-gray-100 transition">
                                                <div class="flex items-start justify-between">
                                                    <div>
                                                        <div class="flex items-center mb-2">
                                                            <span class="text-lg mr-2">🚛</span>
                                                            <span class="font-bold text-gray-900">{{ $historique->camion?->immatriculation ?? 'Camion inconnu' }}</span>
                                                            @if($historique->camion)
                                                                <span class="ml-2 text-xs text-gray-400">({{ $historique->camion->modele ?? 'N/A' }})</span>
                                                            @endif
                                                        </div>
                                                        
                                                        <div class="grid grid-cols-2 md:grid-cols-4 gap-3 text-sm">
                                                            <div>
                                                                <span class="text-gray-400">🕐 De:</span>
                                                                <span class="font-medium ml-1">{{ $historique->heure_debut?->format('d/m/Y H:i') }}</span>
                                                            </div>
                                                            <div>
                                                                <span class="text-gray-400">🏁 À:</span>
                                                                <span class="font-medium ml-1">{{ $historique->heure_fin?->format('d/m/Y H:i') ?? 'En cours' }}</span>
                                                            </div>
                                                            <div>
                                                                <span class="text-gray-400">⏱️ Durée:</span>
                                                                <span class="font-medium ml-1">{{ $historique->getDureeFormatee() }}</span>
                                                            </div>
                                                            <div>
                                                                <span class="text-gray-400">📏 Distance:</span>
                                                                <span class="font-medium ml-1">{{ $historique->distance_km ?? '?' }} km</span>
                                                            </div>
                                                        </div>
                                                        
                                                        <div class="mt-2 flex items-center gap-4 text-sm">
                                                            <span>
                                                                <span class="text-gray-400">📦 Transportés:</span>
                                                                <span class="font-bold text-blue-600 ml-1">{{ $historique->nombre_colis }}</span>
                                                            </span>
                                                            <span>
                                                                <span class="text-gray-400">✅ Livrés:</span>
                                                                <span class="font-bold text-green-600 ml-1">{{ $historique->colis_livres }}</span>
                                                            </span>
                                                            <span>
                                                                <span class="text-gray-400">⏳ En cours:</span>
                                                                <span class="font-bold text-orange-600 ml-1">{{ $historique->colis_en_cours }}</span>
                                                            </span>
                                                        </div>
                                                        
                                                        @if($historique->depots_visites && count($historique->depots_visites) > 0)
                                                            <div class="mt-2">
                                                                <span class="text-gray-400 text-sm">📍 Lieux visités:</span>
                                                                <div class="flex flex-wrap gap-1 mt-1">
                                                                    @foreach($historique->depots_visites as $lieu)
                                                                        <span class="px-2 py-0.5 bg-green-100 text-green-700 text-xs rounded-full">{{ $lieu }}</span>
                                                                    @endforeach
                                                                </div>
                                                            </div>
                                                        @endif
                                                        
                                                        @php $produits = $historique->getProduits(); @endphp
                                                        @if($produits->count() > 0)
                                                            <div class="mt-2">
                                                                <span class="text-gray-400 text-sm">📦 Contenu:</span>
                                                                <div class="flex flex-wrap gap-1 mt-1">
                                                                    @foreach($produits->take(4) as $p)
                                                                        <span class="px-2 py-0.5 bg-amber-100 text-amber-700 text-xs rounded-full" title="{{ $p->destination }}">
                                                                            {{ $p->nom }} → {{ Str::limit($p->destination, 15) }}
                                                                        </span>
                                                                    @endforeach
                                                                    @if($produits->count() > 4)
                                                                        <span class="px-2 py-0.5 bg-gray-200 text-gray-600 text-xs rounded-full">+{{ $produits->count() - 4 }}</span>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        @endif
                                                        
                                                        @if($historique->notes)
                                                            <div class="mt-2 text-xs text-gray-500 italic">
                                                                💬 {{ $historique->notes }}
                                                            </div>
                                                        @endif
                                                    </div>
                                                    
                                                    <span class="px-2 py-1 text-xs font-bold rounded-full
                                                        {{ $historique->statut === 'termine' ? 'bg-green-100 text-green-700' : 
                                                           ($historique->statut === 'en_cours' ? 'bg-orange-100 text-orange-700' : 'bg-gray-100 text-gray-700') }}">
                                                        {{ ucfirst($historique->statut) }}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                    
                                    @if($historiquesDuLivreur->count() > 5)
                                        <div class="text-center py-2">
                                            <button wire:click="viewLivreurDetail({{ $livreur->id }})" class="text-purple-600 hover:text-purple-800 text-sm font-medium">
                                                Voir les {{ $historiquesDuLivreur->count() - 5 }} autres tournées →
                                            </button>
                                        </div>
                                    @endif
                                </div>
                            @else
                                <div class="ml-7 p-4 bg-gray-50 rounded-lg text-gray-500 text-center">
                                    Aucune tournée enregistrée pour ce livreur
                                </div>
                            @endif
                        </div>
                    @empty
                        <div class="p-12 text-center text-gray-500">
                            Aucun livreur enregistré
                        </div>
                    @endforelse
                </div>
                
                <div class="p-4 border-t border-gray-100">
                    {{ $livreurs->links() }}
                </div>
            </div>

        @elseif($activeTab === 'depots')
            <!-- Gestion Dépôts -->
            <div class="bg-white rounded-2xl shadow-sm overflow-hidden">
                <div class="p-6 border-b border-gray-100 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                    <div>
                        <h3 class="text-lg font-bold text-gray-800">🏢 Gestion des Dépôts</h3>
                        <p class="text-sm text-gray-500 mt-1">Créez et gérez vos dépôts de stockage</p>
                    </div>
                    <div class="flex items-center gap-4">
                        <div class="relative">
                            <input type="text" wire:model.live.debounce.300ms="searchDepot" 
                                placeholder="Rechercher un dépôt..."
                                class="pl-10 pr-4 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent text-sm w-64">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">🔍</span>
                        </div>
                        <button wire:click="openDepotModal()" 
                            class="px-4 py-2 bg-gradient-to-r from-cyan-500 to-blue-600 text-white rounded-xl hover:shadow-lg transition flex items-center">
                            <span class="text-lg mr-2">+</span> Nouveau Dépôt
                        </button>
                    </div>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50 text-xs uppercase text-gray-500">
                            <tr>
                                <th class="px-6 py-4 text-left">Code</th>
                                <th class="px-6 py-4 text-left">Nom</th>
                                <th class="px-6 py-4 text-left">Ville</th>
                                <th class="px-6 py-4 text-left">Admin Responsable</th>
                                <th class="px-6 py-4 text-left">Colis</th>
                                <th class="px-6 py-4 text-left">Statut</th>
                                <th class="px-6 py-4 text-left">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($depots as $depot)
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="px-6 py-4">
                                        <span class="px-3 py-1 bg-cyan-100 text-cyan-700 rounded-lg font-bold text-lg">{{ $depot->code }}</span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="font-medium text-gray-900">{{ $depot->nom }}</div>
                                        <div class="text-xs text-gray-500">{{ $depot->adresse }}</div>
                                    </td>
                                    <td class="px-6 py-4 text-gray-600">📍 {{ $depot->ville }}</td>
                                    <td class="px-6 py-4">
                                        @if($depot->admin)
                                            <div class="flex items-center">
                                                <div class="w-8 h-8 rounded-full bg-slate-100 flex items-center justify-center text-slate-600 font-bold text-xs">
                                                    {{ strtoupper(substr($depot->admin->nom, 0, 1)) }}{{ strtoupper(substr($depot->admin->prenom, 0, 1)) }}
                                                </div>
                                                <div class="ml-2">
                                                    <div class="text-sm font-medium text-gray-900">{{ $depot->admin->nom }} {{ $depot->admin->prenom }}</div>
                                                    <div class="text-xs text-gray-500">{{ $depot->admin->email }}</div>
                                                </div>
                                            </div>
                                        @else
                                            <span class="text-gray-400 italic">Non assigné</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-2">
                                            <span class="px-2 py-1 bg-blue-100 text-blue-700 text-xs rounded-full" title="Colis source">📤 {{ $depot->colis_source_count }}</span>
                                            <span class="px-2 py-1 bg-orange-100 text-orange-700 text-xs rounded-full" title="Colis en cours">📦 {{ $depot->colis_actuel_count }}</span>
                                            <span class="px-2 py-1 bg-green-100 text-green-700 text-xs rounded-full" title="Colis destination">📥 {{ $depot->colis_destination_count }}</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <button wire:click="toggleDepotStatus({{ $depot->id }})"
                                            class="px-3 py-1 rounded-full text-xs font-bold transition
                                                {{ $depot->actif ? 'bg-green-100 text-green-700 hover:bg-green-200' : 'bg-red-100 text-red-700 hover:bg-red-200' }}">
                                            {{ $depot->actif ? '✅ Actif' : '❌ Inactif' }}
                                        </button>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-2">
                                            <button wire:click="openDepotModal({{ $depot->id }})" 
                                                class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition" title="Modifier">
                                                ✏️
                                            </button>
                                            <button wire:click="deleteDepot({{ $depot->id }})" 
                                                wire:confirm="Êtes-vous sûr de vouloir supprimer ce dépôt ?"
                                                class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition" title="Supprimer">
                                                🗑️
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                                        <div class="text-4xl mb-2">🏢</div>
                                        Aucun dépôt enregistré
                                        <button wire:click="openDepotModal()" class="block mx-auto mt-4 text-cyan-600 hover:underline">
                                            + Créer le premier dépôt
                                        </button>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                <div class="p-4 border-t border-gray-100">
                    {{ $depots->links() }}
                </div>
            </div>

        @elseif($activeTab === 'admins')
            <!-- Liste Admins -->
            <div class="bg-white rounded-2xl shadow-sm overflow-hidden">
                <div class="p-6 border-b border-gray-100 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                    <div>
                        <h3 class="text-lg font-bold text-gray-800">👔 Gestion des Administrateurs</h3>
                        <p class="text-sm text-gray-500 mt-1">Créez des admins et assignez-les à des dépôts</p>
                    </div>
                    <div class="flex items-center gap-4">
                        <div class="relative">
                            <input type="text" wire:model.live.debounce.300ms="searchAdmin" 
                                placeholder="Rechercher un admin..."
                                class="pl-10 pr-4 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent text-sm w-64">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">🔍</span>
                        </div>
                        <button wire:click="openAdminModal()" 
                            class="px-4 py-2 bg-gradient-to-r from-slate-700 to-slate-900 text-white rounded-xl hover:shadow-lg transition flex items-center">
                            <span class="text-lg mr-2">+</span> Nouvel Admin
                        </button>
                    </div>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50 text-xs uppercase text-gray-500">
                            <tr>
                                <th class="px-6 py-4 text-left">Admin</th>
                                <th class="px-6 py-4 text-left">Dépôt Assigné</th>
                                <th class="px-6 py-4 text-left">Produits Créés</th>
                                <th class="px-6 py-4 text-left">Produits Livrés</th>
                                <th class="px-6 py-4 text-left">Camions</th>
                                <th class="px-6 py-4 text-left">Taux Réussite</th>
                                <th class="px-6 py-4 text-left">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($admins as $admin)
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center">
                                            <div class="w-10 h-10 rounded-full bg-slate-100 flex items-center justify-center text-slate-600 font-bold">
                                                {{ strtoupper(substr($admin->nom, 0, 1)) }}{{ strtoupper(substr($admin->prenom, 0, 1)) }}
                                            </div>
                                            <div class="ml-3">
                                                <div class="font-medium text-gray-900">{{ $admin->nom }} {{ $admin->prenom }}</div>
                                                <div class="text-xs text-gray-500">{{ $admin->email }}</div>
                                                <div class="text-xs text-gray-400">Inscrit le {{ $admin->created_at->format('d/m/Y') }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        @if($admin->depotsGeres && $admin->depotsGeres->count() > 0)
                                            <div class="flex flex-wrap gap-1">
                                                @foreach($admin->depotsGeres as $depot)
                                                    <span class="px-2 py-1 bg-cyan-100 text-cyan-700 rounded-lg text-xs font-bold">
                                                        {{ $depot->code }} - {{ $depot->nom }}
                                                    </span>
                                                @endforeach
                                            </div>
                                        @else
                                            <span class="text-gray-400 italic text-sm">Aucun dépôt</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="text-xl font-bold text-blue-600">{{ $admin->produits_count }}</span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="text-xl font-bold text-green-600">{{ $admin->produits_livres }}</span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="text-xl font-bold text-indigo-600">{{ $admin->camions_owned_count }}</span>
                                    </td>
                                    <td class="px-6 py-4">
                                        @php
                                            $taux = $admin->produits_count > 0 ? round(($admin->produits_livres / $admin->produits_count) * 100, 1) : 0;
                                        @endphp
                                        <div class="flex items-center">
                                            <div class="w-24 bg-gray-200 rounded-full h-2 mr-2">
                                                <div class="h-2 rounded-full {{ $taux >= 70 ? 'bg-green-500' : ($taux >= 40 ? 'bg-yellow-500' : 'bg-red-500') }}" 
                                                    style="width: {{ $taux }}%"></div>
                                            </div>
                                            <span class="text-sm font-medium">{{ $taux }}%</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-2">
                                            <button wire:click="openAdminModal({{ $admin->id }})" 
                                                class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition" title="Modifier">
                                                ✏️
                                            </button>
                                            <button wire:click="deleteAdmin({{ $admin->id }})" 
                                                wire:confirm="Êtes-vous sûr de vouloir supprimer cet admin ? Tous ses dépôts seront désassignés."
                                                class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition" title="Supprimer">
                                                🗑️
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                                        <div class="text-4xl mb-2">👔</div>
                                        Aucun administrateur enregistré
                                        <button wire:click="openAdminModal()" class="block mx-auto mt-4 text-slate-600 hover:underline">
                                            + Créer le premier admin
                                        </button>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                <div class="p-4 border-t border-gray-100">
                    {{ $admins->links() }}
                </div>
            </div>
        @endif
    </div>

    <!-- Modal Création/Édition Dépôt -->
    @if($showDepotModal)
        <div class="fixed inset-0 z-50 overflow-y-auto" aria-modal="true">
            <div class="flex items-center justify-center min-h-screen px-4">
                <div class="fixed inset-0 bg-black bg-opacity-50 transition-opacity" wire:click="closeDepotModal"></div>
                
                <div class="relative bg-white rounded-2xl shadow-2xl max-w-lg w-full">
                    <div class="bg-gradient-to-r from-cyan-500 to-blue-600 text-white p-6 rounded-t-2xl">
                        <div class="flex justify-between items-center">
                            <h3 class="text-xl font-bold">
                                🏢 {{ $editingDepotId ? 'Modifier le Dépôt' : 'Nouveau Dépôt' }}
                            </h3>
                            <button wire:click="closeDepotModal" class="text-white/80 hover:text-white text-2xl">&times;</button>
                        </div>
                    </div>
                    
                    <form wire:submit="saveDepot" class="p-6 space-y-4">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Code *</label>
                                <input type="text" wire:model="depotForm.code" 
                                    class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-cyan-500 focus:border-transparent"
                                    placeholder="Ex: A, B, C...">
                                @error('depotForm.code') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Nom *</label>
                                <input type="text" wire:model="depotForm.nom" 
                                    class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-cyan-500 focus:border-transparent"
                                    placeholder="Dépôt Principal">
                                @error('depotForm.nom') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Adresse *</label>
                            <input type="text" wire:model="depotForm.adresse" 
                                class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-cyan-500 focus:border-transparent"
                                placeholder="123 Rue Example">
                            @error('depotForm.adresse') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                        
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Ville *</label>
                                <input type="text" wire:model="depotForm.ville" 
                                    class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-cyan-500 focus:border-transparent"
                                    placeholder="Tunis">
                                @error('depotForm.ville') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Téléphone</label>
                                <input type="text" wire:model="depotForm.phone" 
                                    class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-cyan-500 focus:border-transparent"
                                    placeholder="+216 XX XXX XXX">
                                @error('depotForm.phone') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Admin Responsable</label>
                            <select wire:model="depotForm.admin_id" 
                                class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-cyan-500 focus:border-transparent">
                                <option value="">-- Sélectionner un admin --</option>
                                @foreach($allAdmins as $admin)
                                    <option value="{{ $admin->id }}">{{ $admin->nom }} {{ $admin->prenom }} ({{ $admin->email }})</option>
                                @endforeach
                            </select>
                            @error('depotForm.admin_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                        
                        <div class="flex items-center">
                            <input type="checkbox" wire:model="depotForm.actif" id="depot_actif"
                                class="w-4 h-4 text-cyan-600 border-gray-300 rounded focus:ring-cyan-500">
                            <label for="depot_actif" class="ml-2 text-sm text-gray-700">Dépôt actif</label>
                        </div>
                        
                        <div class="flex justify-end gap-3 pt-4 border-t">
                            <button type="button" wire:click="closeDepotModal" 
                                class="px-4 py-2 text-gray-600 hover:bg-gray-100 rounded-xl transition">
                                Annuler
                            </button>
                            <button type="submit" 
                                class="px-6 py-2 bg-gradient-to-r from-cyan-500 to-blue-600 text-white rounded-xl hover:shadow-lg transition">
                                {{ $editingDepotId ? 'Modifier' : 'Créer' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif

    <!-- Modal Création/Édition Admin -->
    @if($showAdminModal)
        <div class="fixed inset-0 z-50 overflow-y-auto" aria-modal="true">
            <div class="flex items-center justify-center min-h-screen px-4">
                <div class="fixed inset-0 bg-black bg-opacity-50 transition-opacity" wire:click="closeAdminModal"></div>
                
                <div class="relative bg-white rounded-2xl shadow-2xl max-w-lg w-full">
                    <div class="bg-gradient-to-r from-slate-700 to-slate-900 text-white p-6 rounded-t-2xl">
                        <div class="flex justify-between items-center">
                            <h3 class="text-xl font-bold">
                                👔 {{ $editingAdminId ? 'Modifier l\'Admin' : 'Nouvel Administrateur' }}
                            </h3>
                            <button wire:click="closeAdminModal" class="text-white/80 hover:text-white text-2xl">&times;</button>
                        </div>
                    </div>
                    
                    <form wire:submit="saveAdmin" class="p-6 space-y-4">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Nom *</label>
                                <input type="text" wire:model="adminForm.nom" 
                                    class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-slate-500 focus:border-transparent"
                                    placeholder="Nom">
                                @error('adminForm.nom') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Prénom *</label>
                                <input type="text" wire:model="adminForm.prenom" 
                                    class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-slate-500 focus:border-transparent"
                                    placeholder="Prénom">
                                @error('adminForm.prenom') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Email *</label>
                            <input type="email" wire:model="adminForm.email" 
                                class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-slate-500 focus:border-transparent"
                                placeholder="admin@example.com">
                            @error('adminForm.email') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Mot de passe {{ $editingAdminId ? '(laisser vide pour ne pas changer)' : '*' }}
                            </label>
                            <input type="password" wire:model="adminForm.password" 
                                class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-slate-500 focus:border-transparent"
                                placeholder="••••••••">
                            @error('adminForm.password') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Assigner à un Dépôt</label>
                            <select wire:model="adminForm.depot_id" 
                                class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-slate-500 focus:border-transparent">
                                <option value="">-- Aucun dépôt --</option>
                                @foreach($allDepots as $depot)
                                    <option value="{{ $depot->id }}">{{ $depot->code }} - {{ $depot->nom }} ({{ $depot->ville }})</option>
                                @endforeach
                            </select>
                            @error('adminForm.depot_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            <p class="text-xs text-gray-400 mt-1">L'admin sera responsable de ce dépôt</p>
                        </div>
                        
                        <div class="flex justify-end gap-3 pt-4 border-t">
                            <button type="button" wire:click="closeAdminModal" 
                                class="px-4 py-2 text-gray-600 hover:bg-gray-100 rounded-xl transition">
                                Annuler
                            </button>
                            <button type="submit" 
                                class="px-6 py-2 bg-gradient-to-r from-slate-700 to-slate-900 text-white rounded-xl hover:shadow-lg transition">
                                {{ $editingAdminId ? 'Modifier' : 'Créer' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif

    <!-- Modal Détail Produit -->
    @if($showDetailModal && $selectedProduit)
        <div class="fixed inset-0 z-50 overflow-y-auto" aria-modal="true">
            <div class="flex items-center justify-center min-h-screen px-4">
                <div class="fixed inset-0 bg-black bg-opacity-50 transition-opacity" wire:click="closeModal"></div>
                
                <div class="relative bg-white rounded-2xl shadow-2xl max-w-4xl w-full max-h-[90vh] overflow-y-auto">
                    <div class="sticky top-0 bg-gradient-to-r from-purple-600 to-indigo-600 text-white p-6 rounded-t-2xl">
                        <div class="flex justify-between items-start">
                            <div>
                                <h3 class="text-2xl font-bold">📦 {{ $selectedProduit->nom }}</h3>
                                <p class="text-purple-200 font-mono mt-1">{{ $selectedProduit->qr_code }}</p>
                            </div>
                            <button wire:click="closeModal" class="text-white/80 hover:text-white text-2xl">&times;</button>
                        </div>
                    </div>
                    
                    <div class="p-6">
                        <!-- Statut -->
                        <div class="mb-6 text-center">
                            <span class="px-6 py-3 rounded-full text-lg font-bold
                                {{ $selectedProduit->statut === 'livre' ? 'bg-green-100 text-green-700' : 
                                   ($selectedProduit->statut === 'en_route' ? 'bg-orange-100 text-orange-700' : 
                                   ($selectedProduit->statut === 'prepare' ? 'bg-blue-100 text-blue-700' : 'bg-gray-100 text-gray-700')) }}">
                                {{ ucfirst(str_replace('_', ' ', $selectedProduit->statut)) }}
                            </span>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Informations Produit -->
                            <div class="bg-gray-50 rounded-xl p-5">
                                <h4 class="font-bold text-gray-800 mb-4">📋 Informations Produit</h4>
                                <div class="space-y-3 text-sm">
                                    <div class="flex justify-between"><span class="text-gray-500">Poids:</span><span class="font-medium">{{ $selectedProduit->poids }} kg</span></div>
                                    @if($selectedProduit->poids_volumetrique)
                                        <div class="flex justify-between"><span class="text-gray-500">Poids Vol.:</span><span class="font-medium">{{ $selectedProduit->poids_volumetrique }} kg</span></div>
                                    @endif
                                    @if($selectedProduit->longueur)
                                        <div class="flex justify-between"><span class="text-gray-500">Dimensions:</span><span class="font-medium">{{ $selectedProduit->longueur }}x{{ $selectedProduit->largeur }}x{{ $selectedProduit->hauteur }} cm</span></div>
                                    @endif
                                    <div class="flex justify-between"><span class="text-gray-500">Source:</span><span class="font-medium">{{ $selectedProduit->source }}</span></div>
                                    <div class="flex justify-between"><span class="text-gray-500">Destination:</span><span class="font-medium">{{ $selectedProduit->destination }}</span></div>
                                    <div class="flex justify-between"><span class="text-gray-500">Créé le:</span><span class="font-medium">{{ $selectedProduit->created_at->format('d/m/Y H:i') }}</span></div>
                                </div>
                            </div>
                            
                            <!-- Expéditeur -->
                            <div class="bg-blue-50 rounded-xl p-5">
                                <h4 class="font-bold text-gray-800 mb-4">📤 Expéditeur</h4>
                                <div class="space-y-3 text-sm">
                                    <div class="flex justify-between"><span class="text-gray-500">Nom:</span><span class="font-medium">{{ $selectedProduit->expediteur_nom ?? '-' }}</span></div>
                                    <div class="flex justify-between"><span class="text-gray-500">Société:</span><span class="font-medium">{{ $selectedProduit->expediteur_societe ?? '-' }}</span></div>
                                    <div class="flex justify-between"><span class="text-gray-500">Téléphone:</span><span class="font-medium">{{ $selectedProduit->expediteur_phone ?? '-' }}</span></div>
                                    <div class="flex justify-between"><span class="text-gray-500">Email:</span><span class="font-medium">{{ $selectedProduit->expediteur_email ?? '-' }}</span></div>
                                </div>
                            </div>
                            
                            <!-- Destinataire -->
                            <div class="bg-green-50 rounded-xl p-5">
                                <h4 class="font-bold text-gray-800 mb-4">📥 Destinataire</h4>
                                <div class="space-y-3 text-sm">
                                    <div class="flex justify-between"><span class="text-gray-500">Nom:</span><span class="font-medium">{{ $selectedProduit->destinataire_nom }} {{ $selectedProduit->destinataire_prenom }}</span></div>
                                    <div class="flex justify-between"><span class="text-gray-500">Téléphone:</span><span class="font-medium">{{ $selectedProduit->destinataire_phone }}</span></div>
                                    <div class="flex justify-between"><span class="text-gray-500">Adresse:</span><span class="font-medium">{{ $selectedProduit->destinataire_adresse }}</span></div>
                                    <div class="flex justify-between"><span class="text-gray-500">Ville:</span><span class="font-medium">{{ $selectedProduit->destinataire_ville }}</span></div>
                                </div>
                            </div>
                            
                            <!-- Admin & Livreur -->
                            <div class="bg-purple-50 rounded-xl p-5">
                                <h4 class="font-bold text-gray-800 mb-4">👥 Assignation</h4>
                                <div class="space-y-3 text-sm">
                                    <div class="flex justify-between">
                                        <span class="text-gray-500">Admin:</span>
                                        <span class="font-medium">{{ $selectedProduit->admin?->nom }} {{ $selectedProduit->admin?->prenom ?? '-' }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-500">Camion:</span>
                                        <span class="font-medium">{{ $selectedProduit->camion?->immatriculation ?? 'Non assigné' }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-500">Livreur:</span>
                                        <span class="font-medium">
                                            {{ $selectedProduit->camion?->conducteur?->nom ?? $selectedProduit->camion?->livreur?->nom ?? 'Non assigné' }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Photos de livraison -->
                        @if($selectedProduit->photos_livraison && count($selectedProduit->photos_livraison) > 0)
                            <div class="mt-6 bg-amber-50 rounded-xl p-5">
                                <h4 class="font-bold text-gray-800 mb-4">📸 Photos de Livraison</h4>
                                <div class="grid grid-cols-3 gap-3">
                                    @foreach($selectedProduit->photos_livraison as $photo)
                                        <img src="{{ asset('storage/' . $photo) }}" alt="Photo livraison" class="rounded-lg w-full h-32 object-cover">
                                    @endforeach
                                </div>
                            </div>
                        @endif
                        
                        <!-- Historique des étapes -->
                        @if($selectedProduit->etapes && count($selectedProduit->etapes) > 0)
                            <div class="mt-6 bg-gray-50 rounded-xl p-5">
                                <h4 class="font-bold text-gray-800 mb-4">📜 Historique des Étapes</h4>
                                <div class="space-y-3">
                                    @foreach($selectedProduit->etapes as $etape)
                                        <div class="flex items-start border-l-2 border-purple-300 pl-4 pb-3">
                                            <div class="w-3 h-3 bg-purple-500 rounded-full -ml-[1.4rem] mr-3 mt-1"></div>
                                            <div class="flex-1">
                                                <p class="font-medium text-gray-800">{{ ucfirst(str_replace('_', ' ', $etape->statut)) }}</p>
                                                <p class="text-sm text-gray-600">{{ $etape->description }}</p>
                                                <p class="text-xs text-gray-400 mt-1">{{ $etape->created_at->format('d/m/Y H:i') }}</p>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Modal Détail Livreur avec Historique Complet -->
    @if($showDetailModal && $selectedLivreur)
        <div class="fixed inset-0 z-50 overflow-y-auto" aria-modal="true">
            <div class="flex items-center justify-center min-h-screen px-4">
                <div class="fixed inset-0 bg-black bg-opacity-50 transition-opacity" wire:click="closeModal"></div>
                
                <div class="relative bg-white rounded-2xl shadow-2xl max-w-4xl w-full max-h-[90vh] overflow-y-auto">
                    <div class="sticky top-0 bg-gradient-to-r from-purple-600 to-pink-600 text-white p-6 rounded-t-2xl z-10">
                        <div class="flex justify-between items-center">
                            <div class="flex items-center">
                                <div class="w-16 h-16 rounded-full bg-white/20 flex items-center justify-center text-2xl font-bold">
                                    {{ strtoupper(substr($selectedLivreur->nom, 0, 1)) }}{{ strtoupper(substr($selectedLivreur->prenom, 0, 1)) }}
                                </div>
                                <div class="ml-4">
                                    <h3 class="text-2xl font-bold">{{ $selectedLivreur->nom }} {{ $selectedLivreur->prenom }}</h3>
                                    <p class="text-purple-200">{{ $selectedLivreur->email }}</p>
                                </div>
                            </div>
                            <button wire:click="closeModal" class="text-white/80 hover:text-white text-2xl">&times;</button>
                        </div>
                    </div>
                    
                    <div class="p-6">
                        <div class="grid grid-cols-3 gap-4 mb-6">
                            <div class="bg-green-50 rounded-xl p-4 text-center">
                                <p class="text-3xl font-bold text-green-600">{{ $selectedLivreur->total_livraisons ?? 0 }}</p>
                                <p class="text-sm text-gray-500">Livraisons réussies</p>
                            </div>
                            <div class="bg-blue-50 rounded-xl p-4 text-center">
                                <p class="text-3xl font-bold text-blue-600">{{ $selectedLivreur->camion?->immatriculation ?? '-' }}</p>
                                <p class="text-sm text-gray-500">Camion actuel</p>
                            </div>
                            <div class="bg-purple-50 rounded-xl p-4 text-center">
                                @php
                                    $camionsConduits = $selectedLivreur->historiqueTournees?->pluck('camion_id')->unique()->count() ?? 0;
                                @endphp
                                <p class="text-3xl font-bold text-purple-600">{{ $camionsConduits }}</p>
                                <p class="text-sm text-gray-500">Camions conduits</p>
                            </div>
                        </div>
                        
                        <!-- Historique par CAMION -->
                        <h4 class="font-bold text-gray-800 mb-3 flex items-center">
                            <span class="text-xl mr-2">🚛</span>
                            Historique des Camions Conduits
                        </h4>
                        
                        @if($selectedLivreur->historiqueTournees && $selectedLivreur->historiqueTournees->count() > 0)
                            @php
                                // Grouper par camion
                                $parCamion = $selectedLivreur->historiqueTournees->groupBy('camion_id');
                            @endphp
                            
                            <div class="space-y-4 max-h-[500px] overflow-y-auto">
                                @foreach($parCamion as $camionId => $tournees)
                                    @php
                                        $camion = $tournees->first()->camion;
                                        $premiereDate = $tournees->min('heure_debut');
                                        $derniereDate = $tournees->max('heure_fin') ?? $tournees->max('heure_debut');
                                        $totalColis = $tournees->sum('nombre_colis');
                                        $colisLivres = $tournees->sum('colis_livres');
                                        $totalDistance = $tournees->sum('distance_km');
                                        $tousDepots = $tournees->pluck('depots_visites')->flatten()->unique()->filter()->values();
                                    @endphp
                                    
                                    <div class="border-2 border-indigo-200 rounded-2xl overflow-hidden bg-white shadow-sm">
                                        <!-- En-tête du camion -->
                                        <div class="bg-gradient-to-r from-indigo-500 to-purple-600 text-white p-4">
                                            <div class="flex items-center justify-between">
                                                <div class="flex items-center">
                                                    <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center text-2xl">
                                                        🚛
                                                    </div>
                                                    <div class="ml-3">
                                                        <h5 class="text-xl font-bold">{{ $camion?->immatriculation ?? 'Camion #'.$camionId }}</h5>
                                                        <p class="text-indigo-200 text-sm">{{ $camion?->modele ?? '' }} {{ $camion?->capacite ? '• '.$camion->capacite.' kg' : '' }}</p>
                                                    </div>
                                                </div>
                                                <div class="text-right">
                                                    <span class="px-3 py-1 bg-white/20 rounded-full text-sm font-bold">
                                                        {{ $tournees->count() }} tournée(s)
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <!-- Stats du camion -->
                                        <div class="grid grid-cols-2 md:grid-cols-4 gap-3 p-4 bg-indigo-50">
                                            <div class="text-center">
                                                <p class="text-xs text-gray-500 uppercase">Première conduite</p>
                                                <p class="font-bold text-indigo-700">{{ $premiereDate?->format('d/m/Y') }}</p>
                                                <p class="text-xs text-gray-400">{{ $premiereDate?->format('H:i') }}</p>
                                            </div>
                                            <div class="text-center">
                                                <p class="text-xs text-gray-500 uppercase">Dernière conduite</p>
                                                <p class="font-bold text-indigo-700">{{ $derniereDate?->format('d/m/Y') }}</p>
                                                <p class="text-xs text-gray-400">{{ $derniereDate?->format('H:i') }}</p>
                                            </div>
                                            <div class="text-center">
                                                <p class="text-xs text-gray-500 uppercase">Colis transportés</p>
                                                <p class="font-bold text-green-600">{{ $colisLivres }}/{{ $totalColis }}</p>
                                                <p class="text-xs text-gray-400">livrés</p>
                                            </div>
                                            <div class="text-center">
                                                <p class="text-xs text-gray-500 uppercase">Distance totale</p>
                                                <p class="font-bold text-blue-600">{{ $totalDistance ?? '?' }} km</p>
                                            </div>
                                        </div>
                                        
                                        <!-- Destinations visitées -->
                                        @if($tousDepots->count() > 0)
                                            <div class="px-4 py-3 border-t border-indigo-100">
                                                <p class="text-xs text-gray-500 uppercase mb-2">📍 Destinations visitées</p>
                                                <div class="flex flex-wrap gap-1">
                                                    @foreach($tousDepots as $lieu)
                                                        <span class="px-2 py-1 bg-green-100 text-green-700 text-xs rounded-full">{{ $lieu }}</span>
                                                    @endforeach
                                                </div>
                                            </div>
                                        @endif
                                        
                                        <!-- Liste des tournées détaillées -->
                                        <div class="border-t border-indigo-100">
                                            <details class="group">
                                                <summary class="px-4 py-3 cursor-pointer hover:bg-gray-50 flex items-center justify-between text-sm font-medium text-gray-700">
                                                    <span>📋 Voir le détail des {{ $tournees->count() }} tournée(s)</span>
                                                    <span class="transform group-open:rotate-180 transition-transform">▼</span>
                                                </summary>
                                                <div class="px-4 pb-4 space-y-2">
                                                    @foreach($tournees->sortByDesc('heure_debut') as $t)
                                                        <div class="p-3 bg-gray-50 rounded-lg text-sm">
                                                            <div class="flex items-center justify-between mb-2">
                                                                <span class="font-mono text-xs text-gray-400">{{ $t->tournee_code }}</span>
                                                                <span class="px-2 py-0.5 text-xs rounded-full font-bold
                                                                    {{ $t->statut === 'termine' ? 'bg-green-100 text-green-700' : 
                                                                       ($t->statut === 'en_cours' ? 'bg-orange-100 text-orange-700' : 'bg-gray-100 text-gray-700') }}">
                                                                    {{ ucfirst($t->statut) }}
                                                                </span>
                                                            </div>
                                                            <div class="grid grid-cols-2 md:grid-cols-4 gap-2 text-xs">
                                                                <div>
                                                                    <span class="text-gray-400">🕐 Début:</span>
                                                                    <span class="font-medium">{{ $t->heure_debut?->format('d/m/Y H:i') }}</span>
                                                                </div>
                                                                <div>
                                                                    <span class="text-gray-400">🏁 Fin:</span>
                                                                    <span class="font-medium">{{ $t->heure_fin?->format('d/m/Y H:i') ?? 'En cours...' }}</span>
                                                                </div>
                                                                <div>
                                                                    <span class="text-gray-400">📦 Colis:</span>
                                                                    <span class="font-bold text-blue-600">{{ $t->nombre_colis }}</span>
                                                                    <span class="text-green-600">({{ $t->colis_livres }} ✓)</span>
                                                                </div>
                                                                <div>
                                                                    <span class="text-gray-400">🛣️</span>
                                                                    <span class="font-medium">{{ $t->distance_km ?? '?' }} km</span>
                                                                </div>
                                                            </div>
                                                            @if($t->depots_visites && count($t->depots_visites) > 0)
                                                                <div class="mt-2 flex flex-wrap gap-1">
                                                                    @foreach($t->depots_visites as $d)
                                                                        <span class="px-2 py-0.5 bg-blue-100 text-blue-600 text-xs rounded">{{ $d }}</span>
                                                                    @endforeach
                                                                </div>
                                                            @endif
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </details>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="p-8 bg-gray-50 rounded-xl text-center text-gray-500">
                                <div class="text-4xl mb-2">🚛</div>
                                Aucun historique de conduite enregistré
                            </div>
                        @endif
                        
                        <!-- Produits en cours -->
                        @if($selectedLivreur->camion && $selectedLivreur->camion->produits)
                            <h4 class="font-bold text-gray-800 mb-3 mt-6 flex items-center">
                                <span class="text-xl mr-2">📦</span>
                                Produits en cours actuellement
                            </h4>
                            <div class="space-y-2">
                                @forelse($selectedLivreur->camion->produits->where('statut', '!=', 'livre')->take(5) as $produit)
                                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                        <div>
                                            <p class="font-medium">{{ $produit->nom }}</p>
                                            <p class="text-xs text-gray-500">{{ $produit->destination }}</p>
                                        </div>
                                        <span class="px-2 py-1 text-xs rounded-full font-bold
                                            {{ $produit->statut === 'en_route' ? 'bg-orange-100 text-orange-700' : 'bg-blue-100 text-blue-700' }}">
                                            {{ ucfirst($produit->statut) }}
                                        </span>
                                    </div>
                                @empty
                                    <p class="text-gray-500 text-center py-4">Aucun produit en cours</p>
                                @endforelse
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Modal Détail Camion avec Historique Complet -->
    @if($showDetailModal && $selectedCamion)
        <div class="fixed inset-0 z-50 overflow-y-auto" aria-modal="true">
            <div class="flex items-center justify-center min-h-screen px-4">
                <div class="fixed inset-0 bg-black bg-opacity-50 transition-opacity" wire:click="closeModal"></div>
                
                <div class="relative bg-white rounded-2xl shadow-2xl max-w-4xl w-full max-h-[90vh] overflow-y-auto">
                    <div class="sticky top-0 bg-gradient-to-r from-indigo-600 to-blue-600 text-white p-6 rounded-t-2xl z-10">
                        <div class="flex justify-between items-center">
                            <div class="flex items-center">
                                <div class="w-16 h-16 rounded-xl bg-white/20 flex items-center justify-center text-3xl">
                                    🚛
                                </div>
                                <div class="ml-4">
                                    <h3 class="text-2xl font-bold">{{ $selectedCamion->immatriculation }}</h3>
                                    <p class="text-blue-200">{{ $selectedCamion->modele ?? 'Modèle non spécifié' }} • {{ $selectedCamion->capacite ?? 'N/A' }} kg</p>
                                </div>
                            </div>
                            <button wire:click="closeModal" class="text-white/80 hover:text-white text-2xl">&times;</button>
                        </div>
                    </div>
                    
                    <div class="p-6">
                        <!-- Stats -->
                        <div class="grid grid-cols-3 gap-4 mb-6">
                            <div class="bg-blue-50 rounded-xl p-4 text-center">
                                <p class="text-3xl font-bold text-blue-600">{{ $selectedCamion->produits?->count() ?? 0 }}</p>
                                <p class="text-sm text-gray-500">Produits assignés</p>
                            </div>
                            <div class="bg-purple-50 rounded-xl p-4 text-center">
                                <p class="text-3xl font-bold text-purple-600">{{ $selectedCamion->historiques?->count() ?? 0 }}</p>
                                <p class="text-sm text-gray-500">Tournées effectuées</p>
                            </div>
                            <div class="bg-green-50 rounded-xl p-4 text-center">
                                @php
                                    $conducteur = $selectedCamion->conducteur ?? $selectedCamion->livreur;
                                @endphp
                                <p class="text-lg font-bold text-green-600">{{ $conducteur?->nom ?? 'Aucun' }}</p>
                                <p class="text-sm text-gray-500">Conducteur actuel</p>
                            </div>
                        </div>
                        
                        <!-- Propriétaire -->
                        <div class="bg-gray-50 rounded-xl p-4 mb-6">
                            <div class="flex items-center justify-between">
                                <div>
                                    <span class="text-gray-500 text-sm">Propriétaire (Admin):</span>
                                    <p class="font-bold text-gray-900">{{ $selectedCamion->admin?->nom }} {{ $selectedCamion->admin?->prenom ?? 'Non assigné' }}</p>
                                    <p class="text-sm text-gray-500">{{ $selectedCamion->admin?->company_info ?? '' }}</p>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Historique complet -->
                        <h4 class="font-bold text-gray-800 mb-3 flex items-center">
                            <span class="text-xl mr-2">📜</span>
                            Historique Complet - Qui a conduit ce camion
                        </h4>
                        
                        @if($selectedCamion->historiques && $selectedCamion->historiques->count() > 0)
                            <div class="space-y-3 max-h-96 overflow-y-auto">
                                @foreach($selectedCamion->historiques as $hist)
                                    <div class="border border-gray-200 rounded-xl p-4 hover:bg-gray-50 transition">
                                        <div class="flex justify-between items-start mb-2">
                                            <div class="flex items-center">
                                                <div class="w-10 h-10 rounded-full bg-purple-100 flex items-center justify-center text-purple-600 font-bold text-sm mr-3">
                                                    {{ strtoupper(substr($hist->livreur?->nom ?? 'X', 0, 1)) }}{{ strtoupper(substr($hist->livreur?->prenom ?? 'X', 0, 1)) }}
                                                </div>
                                                <div>
                                                    <span class="font-bold text-gray-900">{{ $hist->livreur?->nom ?? 'Inconnu' }} {{ $hist->livreur?->prenom ?? '' }}</span>
                                                    <p class="text-xs text-gray-400 font-mono">{{ $hist->tournee_code }}</p>
                                                </div>
                                            </div>
                                            <span class="px-2 py-1 text-xs rounded-full font-bold
                                                {{ $hist->statut === 'termine' ? 'bg-green-100 text-green-700' : 
                                                   ($hist->statut === 'en_cours' ? 'bg-orange-100 text-orange-700' : 'bg-gray-100 text-gray-700') }}">
                                                {{ ucfirst($hist->statut) }}
                                            </span>
                                        </div>
                                        
                                        <div class="grid grid-cols-2 md:grid-cols-4 gap-2 text-sm mb-2">
                                            <div>
                                                <span class="text-gray-400">🕐 Début:</span>
                                                <span class="font-medium ml-1">{{ $hist->heure_debut?->format('d/m/Y H:i') }}</span>
                                            </div>
                                            <div>
                                                <span class="text-gray-400">🏁 Fin:</span>
                                                <span class="font-medium ml-1">{{ $hist->heure_fin?->format('d/m/Y H:i') ?? 'En cours' }}</span>
                                            </div>
                                            <div>
                                                <span class="text-gray-400">⏱️ Durée:</span>
                                                <span class="font-medium ml-1">{{ $hist->getDureeFormatee() }}</span>
                                            </div>
                                            <div>
                                                <span class="text-gray-400">📦 Colis:</span>
                                                <span class="font-bold text-blue-600 ml-1">{{ $hist->nombre_colis }}</span>
                                                <span class="text-green-600">({{ $hist->colis_livres }} livrés)</span>
                                            </div>
                                        </div>
                                        
                                        @if($hist->depots_visites && count($hist->depots_visites) > 0)
                                            <div class="flex flex-wrap gap-1 mb-2">
                                                <span class="text-gray-400 text-sm mr-1">📍</span>
                                                @foreach($hist->depots_visites as $lieu)
                                                    <span class="px-2 py-0.5 bg-green-100 text-green-700 text-xs rounded-full">{{ $lieu }}</span>
                                                @endforeach
                                            </div>
                                        @endif
                                        
                                        @php $produits = $hist->getProduits(); @endphp
                                        @if($produits->count() > 0)
                                            <div class="flex flex-wrap gap-1">
                                                <span class="text-gray-400 text-sm mr-1">📦</span>
                                                @foreach($produits->take(5) as $p)
                                                    <span class="px-2 py-0.5 bg-amber-100 text-amber-700 text-xs rounded-full">{{ $p->nom }}</span>
                                                @endforeach
                                                @if($produits->count() > 5)
                                                    <span class="px-2 py-0.5 bg-gray-200 text-gray-600 text-xs rounded-full">+{{ $produits->count() - 5 }}</span>
                                                @endif
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="p-8 bg-gray-50 rounded-xl text-center text-gray-500">
                                Aucune tournée enregistrée pour ce camion
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
