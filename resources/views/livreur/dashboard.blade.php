<x-app-layout>
    <div class="space-y-6 pb-20">
        <!-- Header Mobile Friendly -->
        <div class="flex justify-between items-end">
            <div>
                <p class="text-sm text-gray-500">Bon retour,</p>
                <h2 class="text-2xl font-bold text-gray-800">{{ auth()->user()->prenom }}</h2>
            </div>
            <div class="text-right">
                <p class="text-xs text-gray-400">{{ now()->format('d M Y') }}</p>
            </div>
        </div>

        <!-- Camion Card -->
        @if(auth()->user()->camion)
        <div class="bg-gradient-to-r from-indigo-600 to-blue-600 rounded-2xl p-6 text-white shadow-lg relative overflow-hidden">
            <div class="absolute right-0 top-0 opacity-10 transform translate-x-10 -translate-y-10">
                <svg class="w-40 h-40" fill="currentColor" viewBox="0 0 20 20"><path d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0" /></svg>
            </div>
            <div class="relative z-10">
                <span class="inline-block px-2 py-1 bg-white/20 rounded text-xs backdrop-blur-sm mb-2">En Service</span>
                <h3 class="text-2xl font-bold tracking-wider">{{ auth()->user()->camion->immatriculation }}</h3>
                <p class="text-blue-100 text-sm mt-1">{{ auth()->user()->camion->modele }}</p>
                
                <div class="mt-4 flex justify-between items-end">
                    <div>
                         <span class="text-3xl font-bold">{{ \App\Models\Produit::where('camion_id', auth()->user()->camion_id)->where('statut', '!=', 'livre')->count() }}</span>
                         <span class="text-sm text-blue-200 ml-1">colis restants</span>
                    </div>
                </div>
            </div>
        </div>
        @else
        <div class="bg-red-50 rounded-2xl p-6 border border-red-200 text-center">
            <p class="text-red-600 font-bold">Aucun camion assigné.</p>
            <p class="text-sm text-red-400 mt-1">Contactez votre administrateur.</p>
        </div>
        @endif

        <!-- Action Rapide : SCAN -->
        <a href="{{ route('livreur.scan') }}" class="block w-full bg-gray-900 text-white rounded-2xl p-4 shadow-xl flex items-center justify-center transform transition active:scale-95 group">
            <div class="bg-gray-800 p-2 rounded-full mr-4 group-hover:bg-gray-700 transition-colors">
                <svg class="w-6 h-6 text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                     <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z" />
                </svg>
            </div>
            <span class="font-bold text-lg">SCANNER UN COLIS</span>
        </a>

        <!-- Liste des Tâches -->
        <div>
            <h3 class="font-bold text-gray-800 mb-4 text-lg">Aujourd'hui</h3>
            
            @if(auth()->user()->camion)
                <div class="space-y-4">
                    @forelse(\App\Models\Produit::where('camion_id', auth()->user()->camion_id)->where('statut', '!=', 'livre')->get() as $produit)
                        <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100 flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="w-10 h-10 rounded-full bg-indigo-50 flex items-center justify-center text-indigo-600 font-bold mr-4">
                                    {{ substr($produit->destinataire_nom, 0, 1) }}
                                </div>
                                <div>
                                    <h4 class="font-bold text-gray-900">{{ $produit->destination }}</h4>
                                    <p class="text-xs text-gray-500">{{ $produit->destinataire_prenom }} {{ $produit->destinataire_nom }}</p>
                                </div>
                            </div>
                            <a href="{{ route('livreur.produit.details', $produit->id) }}" class="p-2 text-gray-400 hover:text-indigo-600">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                </svg>
                            </a>
                        </div>
                    @empty
                        <div class="text-center py-10 text-gray-400">
                            <p>Aucun colis en attente.</p>
                            <p class="text-sm">Bon travail !</p>
                        </div>
                    @endforelse
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
