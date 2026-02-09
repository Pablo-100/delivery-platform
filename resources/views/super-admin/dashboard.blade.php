<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Super Admin Dashboard') }}
        </h2>
    </x-slot>

    @php
        $totalProduits = \App\Models\Produit::count();
        $produitsLivres = \App\Models\Produit::where('statut', 'livre')->count();
        $totalCamions = \App\Models\Camion::count();
        $totalLivreurs = \App\Models\User::where('role', 'livreur')->count();
        
        // Simulation financière : 15 TND par livraison
        $revenuEstime = $produitsLivres * 15; 
        
        // Données pour le graph
        $statuts = ['valide', 'prepare', 'en_route', 'livre'];
        $statsData = [];
        foreach($statuts as $s) {
            $statsData[] = \App\Models\Produit::where('statut', $s)->count();
        }
    @endphp

    <div class="py-6 space-y-6">
        
        <!-- KPIs Financial & Ops -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <!-- Revenue Card -->
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 flex flex-col justify-between h-32 relative overflow-hidden group hover:shadow-md transition-shadow">
                <div class="absolute right-0 top-0 h-full w-1 bg-gradient-to-b from-green-400 to-emerald-600"></div>
                <div>
                    <p class="text-sm font-medium text-gray-500 uppercase tracking-wider">Revenu Estimé (Retenue)</p>
                    <h3 class="text-3xl font-bold text-gray-800 mt-1">{{ number_format($revenuEstime, 2) }} <span class="text-sm text-gray-400">TND</span></h3>
                </div>
                <div class="flex items-center text-xs text-green-600 font-medium">
                     <svg class="w-3 h-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" /></svg>
                     +12% vs hier
                </div>
            </div>

            <!-- Colis Card -->
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 flex flex-col justify-between h-32 relative overflow-hidden hover:shadow-md transition-shadow">
                <div class="absolute right-0 top-0 h-full w-1 bg-gradient-to-b from-blue-400 to-indigo-600"></div>
                <div>
                    <p class="text-sm font-medium text-gray-500 uppercase tracking-wider">Total Colis</p>
                    <h3 class="text-3xl font-bold text-gray-800 mt-1">{{ $totalProduits }}</h3>
                </div>
                <div class="flex items-center text-xs text-gray-500">
                     {{ $produitsLivres }} livrés avec succès
                </div>
            </div>

            <!-- Fleet Card -->
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 flex flex-col justify-between h-32 relative overflow-hidden hover:shadow-md transition-shadow">
                <div class="absolute right-0 top-0 h-full w-1 bg-gradient-to-b from-orange-400 to-red-600"></div>
                <div>
                    <p class="text-sm font-medium text-gray-500 uppercase tracking-wider">Flotte Active</p>
                    <h3 class="text-3xl font-bold text-gray-800 mt-1">{{ $totalCamions }}</h3>
                </div>
                <div class="flex items-center text-xs text-gray-500">
                     Camions sur la route
                </div>
            </div>
            
            <!-- Staff Card -->
             <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 flex flex-col justify-between h-32 relative overflow-hidden hover:shadow-md transition-shadow">
                <div class="absolute right-0 top-0 h-full w-1 bg-gradient-to-b from-purple-400 to-pink-600"></div>
                <div>
                    <p class="text-sm font-medium text-gray-500 uppercase tracking-wider">Livreurs</p>
                    <h3 class="text-3xl font-bold text-gray-800 mt-1">{{ $totalLivreurs }}</h3>
                </div>
                <div class="flex items-center text-xs text-gray-500">
                     Connectés aujourd'hui
                </div>
            </div>
        </div>

        <!-- Charts & Activity -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Chart -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 lg:col-span-2">
                <h4 class="text-lg font-bold text-gray-800 mb-4">Statistiques des Livraisons</h4>
                <div class="h-64">
                    <canvas id="deliveryChart"></canvas>
                </div>
            </div>

            <!-- Recent Activity / Alerts -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <h4 class="text-lg font-bold text-gray-800 mb-4">Activité Récente</h4>
                <div class="space-y-4">
                    @foreach(\App\Models\Produit::latest()->take(5)->get() as $p)
                        <div class="flex items-center">
                            <div class="w-2 h-2 rounded-full mr-3 
                                {{ $p->statut == 'livre' ? 'bg-green-500' : ($p->statut == 'en_route' ? 'bg-orange-500' : 'bg-blue-500') }}">
                            </div>
                            <div class="flex-1">
                                <div class="text-sm font-medium text-gray-900">{{ $p->nom }}</div>
                                <div class="text-xs text-gray-500">{{ $p->destination }}</div>
                            </div>
                            <div class="text-xs font-bold 
                                {{ $p->statut == 'livre' ? 'text-green-600' : ($p->statut == 'en_route' ? 'text-orange-600' : 'text-blue-600') }}">
                                {{ ucfirst($p->statut) }}
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="mt-6 pt-4 border-t border-gray-100 text-center">
                    <a href="{{ route('super-admin.produits') }}" class="text-sm text-indigo-600 hover:text-indigo-800 font-medium">Voir tout l'historique &rarr;</a>
                </div>
            </div>
        </div>

        <!-- System Overview Table -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-6 border-b border-gray-100 flex justify-between items-center">
                 <h4 class="text-lg font-bold text-gray-800">Vue Système Globale</h4>
                 <span class="px-3 py-1 bg-gray-100 text-xs rounded-full text-gray-600">Live Updates</span>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left text-gray-500">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 font-bold">
                        <tr>
                            <th class="px-6 py-4">Produit</th>
                            <th class="px-6 py-4">Admin (Société)</th>
                            <th class="px-6 py-4">Livreur / Camion</th>
                            <th class="px-6 py-4">Statut</th>
                            <th class="px-6 py-4">Gain (Est.)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach(\App\Models\Produit::with(['admin', 'camion.livreur'])->latest()->take(10)->get() as $produit)
                        <tr class="border-b hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 font-medium text-gray-900">
                                {{ $produit->nom }}
                                <div class="text-xs text-gray-400 font-mono">{{ $produit->qr_code }}</div>
                            </td>
                            <td class="px-6 py-4">
                                {{ $produit->admin->company_info ?? 'Indépendant' }}
                            </td>
                            <td class="px-6 py-4">
                                @if($produit->camion)
                                    <div class="flex items-center">
                                        <svg class="w-4 h-4 mr-1 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0" /></svg>
                                        {{ $produit->camion->immatriculation }}
                                    </div>
                                    <div class="text-xs text-gray-400 ml-5">{{ $produit->camion->livreur->name ?? 'Sans chauffeur' }}</div>
                                @else
                                    <span class="text-gray-400 italic">En entrepôt</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-3 py-1 rounded-full text-xs font-bold
                                    {{ $produit->statut === 'livre' ? 'bg-green-100 text-green-700' : 
                                       ($produit->statut === 'en_route' ? 'bg-orange-100 text-orange-700' : 
                                       ($produit->statut === 'prepare' ? 'bg-blue-100 text-blue-700' : 'bg-gray-100 text-gray-700')) }}">
                                    {{ ucfirst(str_replace('_', ' ', $produit->statut)) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 font-bold text-gray-800">
                                {{ $produit->statut === 'livre' ? '15.00 TND' : '-' }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Chart Config -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const ctx = document.getElementById('deliveryChart').getContext('2d');
            new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: {!! json_encode(array_map('ucfirst', $statuts)) !!},
                    datasets: [{
                        data: {!! json_encode($statsData) !!},
                        backgroundColor: [
                            '#E5E7EB', // Valide (Gris)
                            '#60A5FA', // Preparé (Bleu)
                            '#F97316', // En Route (Orange)
                            '#10B981'  // Livré (Vert)
                        ],
                        borderWidth: 0
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { position: 'right' }
                    }
                }
            });
        });
    </script>
</x-app-layout>
