<div class="min-h-screen bg-gradient-to-br from-slate-900 via-blue-900 to-slate-900">
    <!-- Animated Background Pattern -->
    <div class="absolute inset-0 overflow-hidden pointer-events-none">
        <div class="absolute -top-40 -right-40 w-80 h-80 bg-blue-500 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-pulse"></div>
        <div class="absolute -bottom-40 -left-40 w-80 h-80 bg-purple-500 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-pulse" style="animation-delay: 2s;"></div>
        <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-96 h-96 bg-indigo-500 rounded-full mix-blend-multiply filter blur-3xl opacity-10 animate-pulse" style="animation-delay: 4s;"></div>
    </div>

    <div class="relative z-10 px-4 py-8 sm:px-6 sm:py-12 lg:px-8 lg:py-16">
        <div class="max-w-4xl mx-auto">
            
            <!-- Header Section -->
            <div class="text-center mb-8 sm:mb-12">
                <!-- Logo/Icon -->
                <div class="inline-flex items-center justify-center w-16 h-16 sm:w-20 sm:h-20 rounded-2xl bg-gradient-to-br from-blue-500 to-indigo-600 shadow-2xl shadow-blue-500/30 mb-4 sm:mb-6">
                    <svg class="w-8 h-8 sm:w-10 sm:h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                    </svg>
                </div>
                
                <h1 class="text-3xl sm:text-4xl lg:text-5xl font-bold text-white mb-3 sm:mb-4 tracking-tight">
                    Suivi de <span class="bg-gradient-to-r from-blue-400 to-cyan-400 bg-clip-text text-transparent">Livraison</span>
                </h1>
                <p class="text-base sm:text-lg text-slate-300 max-w-md mx-auto">
                    Suivez votre colis en temps réel avec votre code de suivi
                </p>
            </div>

            <!-- Search Card -->
            <div class="bg-white/10 backdrop-blur-xl rounded-2xl sm:rounded-3xl border border-white/20 shadow-2xl p-4 sm:p-6 lg:p-8 mb-6 sm:mb-8">
                <form wire:submit.prevent="rechercher" class="space-y-4" role="search" aria-label="Recherche de colis">
                    <div class="relative">
                        <label for="code_colis" class="sr-only">Code de suivi</label>
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 sm:h-6 sm:w-6 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </div>
                        <input 
                            type="text" 
                            id="code_colis"
                            wire:model="code_colis" 
                            placeholder="Entrez votre code de suivi..."
                            class="w-full pl-12 sm:pl-14 pr-4 py-4 sm:py-5 bg-white/10 border border-white/20 rounded-xl sm:rounded-2xl text-white placeholder-slate-400 focus:ring-2 focus:ring-blue-500 focus:border-transparent focus:bg-white/20 transition-all duration-300 text-base sm:text-lg touch-manipulation"
                            autocomplete="off"
                            inputmode="text"
                            aria-describedby="search-help"
                        >
                        <p id="search-help" class="sr-only">Entrez votre code de suivi pour suivre votre colis</p>
                    </div>
                    
                    <button 
                        type="submit"
                        wire:loading.attr="disabled"
                        aria-label="Rechercher le colis"
                        class="w-full py-4 sm:py-5 px-6 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-500 hover:to-indigo-500 text-white font-semibold rounded-xl sm:rounded-2xl shadow-lg shadow-blue-500/30 hover:shadow-blue-500/50 transition-all duration-300 transform hover:scale-[1.02] active:scale-[0.98] disabled:opacity-70 disabled:cursor-not-allowed flex items-center justify-center gap-3 text-base sm:text-lg touch-manipulation"
                    >
                        <span wire:loading.remove aria-hidden="true">
                            <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </span>
                        <span wire:loading aria-hidden="true">
                            <svg class="animate-spin w-5 h-5 sm:w-6 sm:h-6" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                        </span>
                        <span wire:loading.remove>Rechercher mon colis</span>
                        <span wire:loading role="status">Recherche en cours...</span>
                    </button>
                </form>

                @if($error)
                    <div class="mt-4 sm:mt-6 p-4 bg-red-500/20 backdrop-blur border border-red-500/30 rounded-xl sm:rounded-2xl flex items-start gap-3" role="alert" aria-live="polite">
                        <svg class="w-5 h-5 sm:w-6 sm:h-6 text-red-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <p class="text-red-300 text-sm sm:text-base">{{ $error }}</p>
                    </div>
                @endif
            </div>

            <!-- Results for Produit -->
            @if($produit)
                <div class="space-y-4 sm:space-y-6 animate-fade-in" role="region" aria-label="Résultats de recherche">
                    
                    <!-- Status Banner -->
                    <div class="relative overflow-hidden bg-gradient-to-r 
                        @if($produit->statut === 'livre') from-emerald-500/20 to-green-500/20 border-emerald-500/30
                        @elseif($produit->statut === 'en_transit' || $produit->statut === 'en_route') from-blue-500/20 to-cyan-500/20 border-blue-500/30
                        @elseif($produit->statut === 'en_attente') from-amber-500/20 to-yellow-500/20 border-amber-500/30
                        @elseif($produit->statut === 'en_attente_reception') from-orange-500/20 to-amber-500/20 border-orange-500/30
                        @else from-slate-500/20 to-gray-500/20 border-slate-500/30
                        @endif
                        backdrop-blur-xl rounded-2xl sm:rounded-3xl border p-4 sm:p-6 lg:p-8" role="status" aria-live="polite">
                        
                        <div class="flex flex-col sm:flex-row items-center gap-4 sm:gap-6">
                            <!-- Status Icon -->
                            <div class="flex-shrink-0 w-16 h-16 sm:w-20 sm:h-20 rounded-2xl flex items-center justify-center
                                @if($produit->statut === 'livre') bg-emerald-500
                                @elseif($produit->statut === 'en_transit' || $produit->statut === 'en_route') bg-blue-500
                                @elseif($produit->statut === 'en_attente') bg-amber-500
                                @elseif($produit->statut === 'en_attente_reception') bg-orange-500
                                @else bg-slate-500
                                @endif shadow-lg">
                                @if($produit->statut === 'livre')
                                    <svg class="w-8 h-8 sm:w-10 sm:h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                @elseif($produit->statut === 'en_transit' || $produit->statut === 'en_route')
                                    <svg class="w-8 h-8 sm:w-10 sm:h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6"/>
                                    </svg>
                                @else
                                    <svg class="w-8 h-8 sm:w-10 sm:h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                @endif
                            </div>
                            
                            <div class="text-center sm:text-left flex-1">
                                <p class="text-slate-400 text-sm sm:text-base mb-1">Statut actuel</p>
                                <h2 class="text-xl sm:text-2xl lg:text-3xl font-bold
                                    @if($produit->statut === 'livre') text-emerald-400
                                    @elseif($produit->statut === 'en_transit' || $produit->statut === 'en_route') text-blue-400
                                    @elseif($produit->statut === 'en_attente') text-amber-400
                                    @elseif($produit->statut === 'en_attente_reception') text-orange-400
                                    @else text-slate-300
                                    @endif">
                                    @php
                                        $statusLabels = [
                                            'en_attente' => 'En attente',
                                            'en_transit' => 'En transit',
                                            'en_route' => 'En route',
                                            'livre' => 'Livré',
                                            'en_attente_reception' => 'En attente de réception',
                                            'retourne' => 'Retourné',
                                            'annule' => 'Annulé'
                                        ];
                                    @endphp
                                    {{ $statusLabels[$produit->statut] ?? ucfirst(str_replace('_', ' ', $produit->statut)) }}
                                </h2>
                            </div>
                            
                            <!-- QR Code Display -->
                            <div class="hidden lg:block bg-white/10 rounded-xl p-3">
                                <p class="text-xs text-slate-400 text-center mb-1">Code de suivi</p>
                                <p class="font-mono text-sm text-white bg-slate-800/50 px-3 py-2 rounded-lg truncate max-w-[180px]">
                                    {{ substr($produit->qr_code, 0, 8) }}...
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Product Details Card -->
                    <div class="bg-white/10 backdrop-blur-xl rounded-2xl sm:rounded-3xl border border-white/20 shadow-2xl overflow-hidden">
                        <div class="p-4 sm:p-6 lg:p-8 border-b border-white/10">
                            <h3 class="text-lg sm:text-xl font-bold text-white flex items-center gap-3">
                                <svg class="w-5 h-5 sm:w-6 sm:h-6 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                </svg>
                                Détails du colis
                            </h3>
                        </div>
                        
                        <div class="p-4 sm:p-6 lg:p-8">
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                <!-- Product Name -->
                                <div class="bg-white/5 rounded-xl p-4 border border-white/5 hover:bg-white/10 transition-colors">
                                    <div class="flex items-center gap-3 mb-2">
                                        <div class="w-8 h-8 rounded-lg bg-indigo-500/20 flex items-center justify-center">
                                            <svg class="w-4 h-4 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                                            </svg>
                                        </div>
                                        <p class="text-sm text-slate-400">Produit</p>
                                    </div>
                                    <p class="text-white font-semibold text-base sm:text-lg break-words">{{ $produit->nom }}</p>
                                    @if($produit->description)
                                        <p class="text-slate-400 text-sm mt-1 line-clamp-2">{{ $produit->description }}</p>
                                    @endif
                                </div>

                                <!-- Dimensions -->
                                <div class="bg-white/5 rounded-xl p-4 border border-white/5 hover:bg-white/10 transition-colors">
                                    <div class="flex items-center gap-3 mb-2">
                                        <div class="w-8 h-8 rounded-lg bg-cyan-500/20 flex items-center justify-center">
                                            <svg class="w-4 h-4 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z"/>
                                            </svg>
                                        </div>
                                        <p class="text-sm text-slate-400">Dimensions & Poids</p>
                                    </div>
                                    <div class="space-y-1">
                                        <p class="text-white font-semibold">{{ $produit->longueur ?? 0 }} × {{ $produit->largeur ?? 0 }} × {{ $produit->hauteur ?? 0 }} cm</p>
                                        <p class="text-slate-400 text-sm">{{ number_format($produit->poids_facturable ?? $produit->poids ?? 0, 2) }} kg</p>
                                    </div>
                                </div>

                                <!-- QR Code (mobile visible) -->
                                <div class="bg-white/5 rounded-xl p-4 border border-white/5 hover:bg-white/10 transition-colors lg:hidden">
                                    <div class="flex items-center gap-3 mb-2">
                                        <div class="w-8 h-8 rounded-lg bg-purple-500/20 flex items-center justify-center">
                                            <svg class="w-4 h-4 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/>
                                            </svg>
                                        </div>
                                        <p class="text-sm text-slate-400">Code de suivi</p>
                                    </div>
                                    <p class="text-white font-mono text-sm break-all">{{ $produit->qr_code }}</p>
                                </div>

                                @if($produit->depotSource)
                                <div class="bg-white/5 rounded-xl p-4 border border-white/5 hover:bg-white/10 transition-colors">
                                    <div class="flex items-center gap-3 mb-2">
                                        <div class="w-8 h-8 rounded-lg bg-green-500/20 flex items-center justify-center">
                                            <svg class="w-4 h-4 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                            </svg>
                                        </div>
                                        <p class="text-sm text-slate-400">Dépôt source</p>
                                    </div>
                                    <p class="text-white font-semibold">{{ $produit->depotSource->nom }}</p>
                                </div>
                                @endif

                                @if($produit->depotActuel)
                                <div class="bg-white/5 rounded-xl p-4 border border-white/5 hover:bg-white/10 transition-colors">
                                    <div class="flex items-center gap-3 mb-2">
                                        <div class="w-8 h-8 rounded-lg bg-blue-500/20 flex items-center justify-center">
                                            <svg class="w-4 h-4 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                            </svg>
                                        </div>
                                        <p class="text-sm text-slate-400">Dépôt actuel</p>
                                    </div>
                                    <p class="text-white font-semibold">{{ $produit->depotActuel->nom }}</p>
                                </div>
                                @endif

                                @if($produit->depotDestination)
                                <div class="bg-white/5 rounded-xl p-4 border border-white/5 hover:bg-white/10 transition-colors">
                                    <div class="flex items-center gap-3 mb-2">
                                        <div class="w-8 h-8 rounded-lg bg-amber-500/20 flex items-center justify-center">
                                            <svg class="w-4 h-4 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            </svg>
                                        </div>
                                        <p class="text-sm text-slate-400">Destination</p>
                                    </div>
                                    <p class="text-white font-semibold">{{ $produit->depotDestination->nom }}</p>
                                </div>
                                @endif

                                @if($produit->destinataire_nom)
                                <div class="bg-white/5 rounded-xl p-4 border border-white/5 hover:bg-white/10 transition-colors sm:col-span-2 lg:col-span-1">
                                    <div class="flex items-center gap-3 mb-2">
                                        <div class="w-8 h-8 rounded-lg bg-rose-500/20 flex items-center justify-center">
                                            <svg class="w-4 h-4 text-rose-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                            </svg>
                                        </div>
                                        <p class="text-sm text-slate-400">Destinataire</p>
                                    </div>
                                    <p class="text-white font-semibold">{{ $produit->destinataire_nom }}</p>
                                    @if($produit->destinataire_adresse)
                                        <p class="text-slate-400 text-sm mt-1">{{ $produit->destinataire_adresse }}</p>
                                    @endif
                                    @if($produit->destinataire_telephone)
                                        <p class="text-slate-400 text-sm">{{ $produit->destinataire_telephone }}</p>
                                    @endif
                                </div>
                                @endif

                                @if($produit->camion)
                                <div class="bg-white/5 rounded-xl p-4 border border-white/5 hover:bg-white/10 transition-colors">
                                    <div class="flex items-center gap-3 mb-2">
                                        <div class="w-8 h-8 rounded-lg bg-slate-500/20 flex items-center justify-center">
                                            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                                            </svg>
                                        </div>
                                        <p class="text-sm text-slate-400">Véhicule</p>
                                    </div>
                                    <p class="text-white font-semibold">{{ $produit->camion->immatriculation }}</p>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Timeline -->
                    @if($etapes && count($etapes) > 0)
                    <div class="bg-white/10 backdrop-blur-xl rounded-2xl sm:rounded-3xl border border-white/20 shadow-2xl overflow-hidden">
                        <div class="p-4 sm:p-6 lg:p-8 border-b border-white/10">
                            <h3 class="text-lg sm:text-xl font-bold text-white flex items-center gap-3">
                                <svg class="w-5 h-5 sm:w-6 sm:h-6 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                Historique de suivi
                            </h3>
                        </div>
                        
                        <div class="p-4 sm:p-6 lg:p-8">
                            <div class="relative">
                                <div class="absolute left-4 sm:left-6 top-0 bottom-0 w-0.5 bg-gradient-to-b from-blue-500 via-indigo-500 to-purple-500"></div>
                                
                                <div class="space-y-4 sm:space-y-6">
                                    @foreach($etapes as $index => $etape)
                                        <div class="relative flex gap-4 sm:gap-6">
                                            <div class="relative z-10 flex-shrink-0">
                                                <div class="w-8 h-8 sm:w-12 sm:h-12 rounded-full bg-gradient-to-br from-blue-500 to-indigo-600 border-4 border-slate-800 flex items-center justify-center shadow-lg shadow-blue-500/30">
                                                    <svg class="w-4 h-4 sm:w-5 sm:h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                                    </svg>
                                                </div>
                                            </div>
                                            
                                            <div class="flex-1 bg-white/5 rounded-xl sm:rounded-2xl p-4 sm:p-5 hover:bg-white/10 transition-colors">
                                                <div class="flex flex-col sm:flex-row sm:justify-between sm:items-start gap-2 mb-2">
                                                    <h4 class="font-semibold text-white text-base sm:text-lg">{{ $etape->type ?? $etape->action ?? 'Étape' }}</h4>
                                                    <span class="text-sm text-slate-400 bg-slate-700/50 px-3 py-1 rounded-full whitespace-nowrap">
                                                        {{ $etape->created_at->format('d/m/Y H:i') }}
                                                    </span>
                                                </div>
                                                
                                                @if($etape->depot)
                                                    <p class="text-slate-300 text-sm flex items-center gap-2">
                                                        <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                                        </svg>
                                                        {{ $etape->depot->nom }}
                                                    </p>
                                                @endif
                                                
                                                @if($etape->user)
                                                    <p class="text-slate-400 text-sm flex items-center gap-2 mt-1">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                                        </svg>
                                                        {{ $etape->user->name }}
                                                    </p>
                                                @endif
                                                
                                                @if($etape->commentaire)
                                                    <p class="text-slate-300 text-sm mt-3 pl-3 border-l-2 border-slate-600">{{ $etape->commentaire }}</p>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                    @else
                    <div class="bg-white/10 backdrop-blur-xl rounded-2xl sm:rounded-3xl border border-white/20 p-6 sm:p-8 text-center">
                        <svg class="w-12 h-12 sm:w-16 sm:h-16 text-slate-500 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <h3 class="text-lg sm:text-xl font-bold text-white mb-2">Aucun historique</h3>
                        <p class="text-slate-400">L'historique de suivi sera affiché ici une fois disponible.</p>
                    </div>
                    @endif
                </div>

            <!-- Results for Colis (legacy) -->
            @elseif($colis)
                <div class="space-y-4 sm:space-y-6 animate-fade-in">
                    
                    <!-- Status Banner -->
                    <div class="relative overflow-hidden bg-gradient-to-r 
                        @if($colis->statut === 'livre') from-emerald-500/20 to-green-500/20 border-emerald-500/30
                        @elseif($colis->statut === 'en_transit') from-blue-500/20 to-cyan-500/20 border-blue-500/30
                        @elseif($colis->statut === 'en_attente') from-amber-500/20 to-yellow-500/20 border-amber-500/30
                        @else from-slate-500/20 to-gray-500/20 border-slate-500/30
                        @endif
                        backdrop-blur-xl rounded-2xl sm:rounded-3xl border p-4 sm:p-6 lg:p-8">
                        
                        <div class="flex flex-col sm:flex-row items-center gap-4 sm:gap-6">
                            <div class="flex-shrink-0 w-16 h-16 sm:w-20 sm:h-20 rounded-2xl flex items-center justify-center
                                @if($colis->statut === 'livre') bg-emerald-500
                                @elseif($colis->statut === 'en_transit') bg-blue-500
                                @elseif($colis->statut === 'en_attente') bg-amber-500
                                @else bg-slate-500
                                @endif shadow-lg">
                                @if($colis->statut === 'livre')
                                    <svg class="w-8 h-8 sm:w-10 sm:h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                @elseif($colis->statut === 'en_transit')
                                    <svg class="w-8 h-8 sm:w-10 sm:h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6"/>
                                    </svg>
                                @else
                                    <svg class="w-8 h-8 sm:w-10 sm:h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                @endif
                            </div>
                            
                            <div class="text-center sm:text-left flex-1">
                                <p class="text-slate-400 text-sm sm:text-base mb-1">Statut actuel</p>
                                <h2 class="text-xl sm:text-2xl lg:text-3xl font-bold
                                    @if($colis->statut === 'livre') text-emerald-400
                                    @elseif($colis->statut === 'en_transit') text-blue-400
                                    @elseif($colis->statut === 'en_attente') text-amber-400
                                    @else text-slate-300
                                    @endif">
                                    {{ ucfirst(str_replace('_', ' ', $colis->statut)) }}
                                </h2>
                            </div>
                            
                            <div class="hidden lg:block bg-white/10 rounded-xl p-3">
                                <p class="text-xs text-slate-400 text-center mb-1">Code colis</p>
                                <p class="font-mono text-sm text-white bg-slate-800/50 px-3 py-2 rounded-lg">
                                    {{ $colis->code_colis }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Colis Details Card -->
                    <div class="bg-white/10 backdrop-blur-xl rounded-2xl sm:rounded-3xl border border-white/20 shadow-2xl overflow-hidden">
                        <div class="p-4 sm:p-6 lg:p-8 border-b border-white/10">
                            <h3 class="text-lg sm:text-xl font-bold text-white flex items-center gap-3">
                                <svg class="w-5 h-5 sm:w-6 sm:h-6 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                </svg>
                                Informations du Colis
                            </h3>
                        </div>
                        
                        <div class="p-4 sm:p-6 lg:p-8">
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6">
                                <div class="bg-white/5 rounded-xl p-4 hover:bg-white/10 transition-colors lg:hidden">
                                    <div class="flex items-center gap-3 mb-2">
                                        <div class="w-8 h-8 rounded-lg bg-purple-500/20 flex items-center justify-center">
                                            <svg class="w-4 h-4 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"/>
                                            </svg>
                                        </div>
                                        <p class="text-sm text-slate-400">Code Colis</p>
                                    </div>
                                    <p class="text-white font-mono font-semibold">{{ $colis->code_colis }}</p>
                                </div>

                                <div class="bg-white/5 rounded-xl p-4 hover:bg-white/10 transition-colors">
                                    <div class="flex items-center gap-3 mb-2">
                                        <div class="w-8 h-8 rounded-lg bg-green-500/20 flex items-center justify-center">
                                            <svg class="w-4 h-4 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                            </svg>
                                        </div>
                                        <p class="text-sm text-slate-400">Expéditeur</p>
                                    </div>
                                    <p class="text-white font-semibold">{{ $colis->expediteur_nom }}</p>
                                    <p class="text-slate-400 text-sm mt-1">{{ $colis->expediteur_phone }}</p>
                                </div>

                                <div class="bg-white/5 rounded-xl p-4 hover:bg-white/10 transition-colors">
                                    <div class="flex items-center gap-3 mb-2">
                                        <div class="w-8 h-8 rounded-lg bg-rose-500/20 flex items-center justify-center">
                                            <svg class="w-4 h-4 text-rose-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                            </svg>
                                        </div>
                                        <p class="text-sm text-slate-400">Destinataire</p>
                                    </div>
                                    <p class="text-white font-semibold">{{ $colis->destinataire_nom }} {{ $colis->destinataire_prenom }}</p>
                                    <p class="text-slate-400 text-sm mt-1">{{ $colis->destinataire_ville }}</p>
                                </div>

                                @if($colis->depotActuel)
                                <div class="bg-white/5 rounded-xl p-4 hover:bg-white/10 transition-colors">
                                    <div class="flex items-center gap-3 mb-2">
                                        <div class="w-8 h-8 rounded-lg bg-blue-500/20 flex items-center justify-center">
                                            <svg class="w-4 h-4 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                            </svg>
                                        </div>
                                        <p class="text-sm text-slate-400">Dépôt Actuel</p>
                                    </div>
                                    <p class="text-white font-semibold">{{ $colis->depotActuel->nom }}</p>
                                    <p class="text-slate-400 text-sm mt-1">Code: {{ $colis->depotActuel->code }}</p>
                                </div>
                                @endif

                                @if($colis->livreur)
                                <div class="bg-white/5 rounded-xl p-4 hover:bg-white/10 transition-colors">
                                    <div class="flex items-center gap-3 mb-2">
                                        <div class="w-8 h-8 rounded-lg bg-amber-500/20 flex items-center justify-center">
                                            <svg class="w-4 h-4 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                                            </svg>
                                        </div>
                                        <p class="text-sm text-slate-400">Livreur</p>
                                    </div>
                                    <p class="text-white font-semibold">{{ $colis->livreur->nom }} {{ $colis->livreur->prenom }}</p>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Timeline for Colis -->
                    @if($etapes && count($etapes) > 0)
                    <div class="bg-white/10 backdrop-blur-xl rounded-2xl sm:rounded-3xl border border-white/20 shadow-2xl overflow-hidden">
                        <div class="p-4 sm:p-6 lg:p-8 border-b border-white/10">
                            <h3 class="text-lg sm:text-xl font-bold text-white flex items-center gap-3">
                                <svg class="w-5 h-5 sm:w-6 sm:h-6 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                Historique de Livraison
                            </h3>
                        </div>
                        
                        <div class="p-4 sm:p-6 lg:p-8">
                            <div class="relative">
                                <div class="absolute left-4 sm:left-6 top-0 bottom-0 w-0.5 bg-gradient-to-b from-blue-500 via-indigo-500 to-purple-500"></div>
                                
                                <div class="space-y-4 sm:space-y-6">
                                    @foreach($etapes as $etape)
                                        <div class="relative flex gap-4 sm:gap-6">
                                            <div class="relative z-10 flex-shrink-0">
                                                <div class="w-8 h-8 sm:w-12 sm:h-12 rounded-full bg-gradient-to-br from-blue-500 to-indigo-600 border-4 border-slate-800 flex items-center justify-center shadow-lg shadow-blue-500/30">
                                                    <svg class="w-4 h-4 sm:w-5 sm:h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                                    </svg>
                                                </div>
                                            </div>
                                            
                                            <div class="flex-1 bg-white/5 rounded-xl sm:rounded-2xl p-4 sm:p-5 hover:bg-white/10 transition-colors">
                                                <div class="flex flex-col sm:flex-row sm:justify-between sm:items-start gap-2 mb-2">
                                                    <h4 class="font-semibold text-white text-base sm:text-lg">{{ $etape->getTypeLabel() }}</h4>
                                                    <span class="text-sm text-slate-400 bg-slate-700/50 px-3 py-1 rounded-full whitespace-nowrap">
                                                        {{ $etape->date_etape->format('d/m/Y H:i') }}
                                                    </span>
                                                </div>
                                                
                                                @if($etape->depotDepart || $etape->depotArrivee)
                                                    <p class="text-slate-300 text-sm flex items-center gap-2 flex-wrap">
                                                        @if($etape->depotDepart)
                                                            <span class="flex items-center gap-1">
                                                                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                                                </svg>
                                                                {{ $etape->depotDepart->nom }}
                                                            </span>
                                                        @endif
                                                        @if($etape->depotArrivee)
                                                            <span class="text-slate-500">→</span>
                                                            <span class="flex items-center gap-1">
                                                                <svg class="w-4 h-4 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                                                </svg>
                                                                {{ $etape->depotArrivee->nom }}
                                                            </span>
                                                        @endif
                                                    </p>
                                                @endif
                                                
                                                @if($etape->livreur)
                                                    <p class="text-slate-400 text-sm flex items-center gap-2 mt-1">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                                        </svg>
                                                        {{ $etape->livreur->nom }} {{ $etape->livreur->prenom }}
                                                    </p>
                                                @endif
                                                
                                                @if($etape->commentaire)
                                                    <p class="text-slate-300 text-sm mt-3 pl-3 border-l-2 border-slate-600">{{ $etape->commentaire }}</p>
                                                @endif
                                                
                                                @if($etape->photos && count($etape->photos) > 0)
                                                    <div class="mt-4">
                                                        <p class="text-sm font-semibold text-slate-300 mb-2">Photos:</p>
                                                        <div class="flex gap-2 flex-wrap">
                                                            @foreach($etape->photos as $photo)
                                                                <img 
                                                                    src="{{ Storage::url($photo) }}" 
                                                                    alt="Photo étape" 
                                                                    class="w-16 h-16 sm:w-20 sm:h-20 object-cover rounded-lg cursor-pointer hover:opacity-75 hover:scale-105 transition-all border-2 border-white/20"
                                                                    onclick="window.open('{{ Storage::url($photo) }}', '_blank')"
                                                                >
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            @endif

            <!-- Footer -->
            <div class="mt-8 sm:mt-12 text-center">
                <p class="text-slate-500 text-sm">
                    © {{ date('Y') }} Delivery Platform. Tous droits réservés.
                </p>
            </div>
        </div>
    </div>

    <style>
        /* Animations */
        @keyframes fade-in {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-in {
            animation: fade-in 0.5s ease-out forwards;
        }
        
        /* Responsive Typography */
        @media (max-width: 640px) {
            .text-responsive-xl {
                font-size: 1.25rem;
            }
        }
        
        /* Touch-friendly targets - minimum 44px */
        @media (hover: none) and (pointer: coarse) {
            button, .touch-target {
                min-height: 48px;
            }
            input {
                font-size: 16px; /* Prevents zoom on iOS */
            }
        }
        
        /* Smooth scrolling */
        html {
            scroll-behavior: smooth;
        }
        
        /* Focus states for accessibility */
        *:focus-visible {
            outline: 2px solid #3b82f6;
            outline-offset: 2px;
        }
        
        /* Reduce motion for users who prefer it */
        @media (prefers-reduced-motion: reduce) {
            *, *::before, *::after {
                animation-duration: 0.01ms !important;
                animation-iteration-count: 1 !important;
                transition-duration: 0.01ms !important;
            }
        }
        
        /* Better text rendering */
        body {
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }
        
        /* Prevent horizontal scroll on mobile */
        .min-h-screen {
            overflow-x: hidden;
        }
        
        /* Card hover effects only on devices with hover capability */
        @media (hover: hover) {
            .hover-lift:hover {
                transform: translateY(-2px);
            }
        }
    </style>
</div>
