<div class="max-w-2xl mx-auto py-6 px-4 pb-20">
    <div class="mb-6 flex justify-between items-center">
        <h2 class="text-2xl font-bold text-gray-800">Détails de la Livraison</h2>
        <a href="{{ route('livreur.dashboard') }}" class="text-sm text-gray-600 hover:text-gray-900">
            &larr; Retour
        </a>
    </div>

    @if (session()->has('message'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline">{{ session('message') }}</span>
        </div>
    @endif

    @if (session()->has('photo_message'))
        <div class="bg-amber-100 border border-amber-400 text-amber-700 px-4 py-3 rounded-lg relative mb-4 flex items-center animate-pulse" role="alert">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
            <span class="block sm:inline font-medium">{{ session('photo_message') }}</span>
        </div>
    @endif

    <!-- Statut Actuel -->
    <div class="bg-white rounded-xl shadow-sm overflow-hidden mb-6 border-l-8 
        {{ $produit->statut === 'livre' ? 'border-green-500' : ($produit->statut === 'en_route' ? 'border-orange-500' : 'border-blue-500') }}">
        <div class="p-6">
            <div class="text-xs font-bold text-gray-500 uppercase tracking-wide">Statut Actuel</div>
            <div class="text-3xl font-bold mt-1 flex items-center">
                {{ ucfirst(str_replace('_', ' ', $produit->statut)) }}
                @if($produit->statut === 'livre')
                    <svg class="h-8 w-8 text-green-500 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                @endif
            </div>
            <div class="text-sm text-gray-400 mt-2 font-mono">{{ $produit->qr_code }}</div>
        </div>
    </div>

    <!-- Info Destinataire -->
    <div class="bg-white rounded-xl shadow-sm overflow-hidden mb-6">
        <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
            <h3 class="font-bold text-gray-800">Destinataire</h3>
        </div>
        <div class="p-6 space-y-4">
            <div>
                <div class="text-sm text-gray-500">Nom & Prénom</div>
                <div class="text-lg font-medium">{{ $produit->destinataire_nom }} {{ $produit->destinataire_prenom }}</div>
            </div>
            <div>
                <div class="text-sm text-gray-500">Destination (Instruction)</div>
                @if($produit->depotDestination)
                    <div class="mt-2 p-4 bg-purple-50 rounded-lg border border-purple-200">
                        <div class="flex items-start">
                            <span class="text-2xl mr-2">🏪</span>
                            <div>
                                <p class="font-bold text-purple-900">Dépôt : {{ $produit->depotDestination->nom }}</p>
                                <p class="text-sm text-gray-700">{{ $produit->depotDestination->adresse ?? $produit->depotDestination->ville }}</p>
                                <a href="https://www.google.com/maps/search/?api=1&query={{ urlencode(($produit->depotDestination->adresse ?? $produit->depotDestination->nom) . ' ' . $produit->depotDestination->ville) }}" target="_blank" class="mt-2 inline-flex items-center text-purple-700 font-bold hover:underline">
                                    <svg class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                    Naviguer vers le dépôt
                                </a>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="mt-2 p-4 bg-blue-50 rounded-lg border border-blue-200">
                        <div class="text-xs font-bold text-blue-800 uppercase mb-1">Livraison Client Final</div>
                        <div class="text-lg font-medium text-indigo-600">
                            <a href="https://www.google.com/maps/search/?api=1&query={{ urlencode($produit->destination) }}" target="_blank" class="flex items-center hover:underline">
                                <svg class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                {{ $produit->destination }}
                            </a>
                        </div>
                    </div>
                @endif
            </div>
            <div>
                <div class="text-sm text-gray-500">Vérification CIN (3 derniers chiffres)</div>
                <div class="text-xl font-bold tracking-widest bg-gray-100 inline-block px-3 py-1 rounded">{{ $produit->destinataire_cin_3_derniers_chiffres }}</div>
            </div>
        </div>
    </div>

    <!-- Info Expéditeur -->
    <div class="bg-white rounded-xl shadow-sm overflow-hidden mb-6">
        <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
            <h3 class="font-bold text-gray-800">Détails Colis & Expéditeur</h3>
        </div>
        <div class="p-6 grid grid-cols-2 gap-4">
            <div>
                <div class="text-sm text-gray-500">Contenu</div>
                <div class="font-medium">{{ $produit->nom }}</div>
            </div>
            <div>
                <div class="text-sm text-gray-500">Poids à livrer</div>
                <div class="font-medium text-lg">{{ $produit->poids_facturable ?? $produit->poids }} kg</div>
            </div>
            <div class="col-span-2">
                <div class="text-sm text-gray-500">Expéditeur</div>
                <div class="font-medium">{{ $produit->expediteur_societe ?? 'N/A' }}</div>
                <div class="text-sm text-gray-600">{{ $produit->expediteur_phone }}</div>
            </div>
        </div>
    </div>

    <!-- Photos de Livraison -->
    <div class="bg-white rounded-xl shadow-sm overflow-hidden mb-6">
        <div class="px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-amber-50 to-orange-50">
            <div class="flex items-center">
                <div class="p-2 bg-amber-100 rounded-lg mr-3">
                    <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="font-bold text-gray-800">📸 Photos de Livraison</h3>
                    <p class="text-sm text-gray-500">Prenez des photos comme preuve de livraison</p>
                </div>
            </div>
        </div>
        <div class="p-6">
            <!-- Zone d'upload -->
            <div class="mb-4">
                <label for="photo-upload" class="block mb-3">
                    <div class="relative border-2 border-dashed border-gray-300 rounded-xl p-6 text-center hover:border-amber-400 hover:bg-amber-50 transition-all cursor-pointer group">
                        <input type="file" 
                               id="photo-upload"
                               wire:model="photos" 
                               accept="image/*" 
                               capture="environment"
                               multiple
                               class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                        
                        <div wire:loading.remove wire:target="photos">
                            <div class="flex flex-col items-center">
                                <div class="p-4 bg-amber-100 rounded-full mb-3 group-hover:bg-amber-200 transition">
                                    <svg class="w-8 h-8 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                </div>
                                <span class="text-amber-600 font-semibold text-lg">Prendre une photo</span>
                                <span class="text-gray-400 text-sm mt-1">ou choisir depuis la galerie</span>
                            </div>
                        </div>
                        
                        <!-- Loading indicator -->
                        <div wire:loading wire:target="photos" class="flex flex-col items-center">
                            <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-amber-600 mb-3"></div>
                            <span class="text-amber-600 font-medium">Chargement en cours...</span>
                        </div>
                    </div>
                </label>
                
                @error('photos.*')
                    <p class="text-red-500 text-sm mt-2 flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                        {{ $message }}
                    </p>
                @enderror
            </div>

            <!-- Photos uploadées -->
            @if(count($uploadedPhotos) > 0)
                <div class="mt-4">
                    <div class="flex items-center justify-between mb-3">
                        <h4 class="font-medium text-gray-700 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Photos enregistrées ({{ count($uploadedPhotos) }})
                        </h4>
                    </div>
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                        @foreach($uploadedPhotos as $index => $photo)
                            <div class="relative group rounded-xl overflow-hidden shadow-md aspect-square">
                                <img src="{{ asset('storage/' . $photo) }}" 
                                     alt="Photo livraison {{ $index + 1 }}" 
                                     class="w-full h-full object-cover">
                                <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-40 transition-all duration-200"></div>
                                <button wire:click="removePhoto({{ $index }})" 
                                        wire:loading.attr="disabled"
                                        class="absolute top-2 right-2 bg-red-500 text-white rounded-full p-2 opacity-0 group-hover:opacity-100 transition-all duration-200 hover:bg-red-600 shadow-lg transform hover:scale-110">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                </button>
                                <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/60 to-transparent p-2">
                                    <span class="text-white text-xs font-medium">Photo {{ $index + 1 }}</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Message d'information -->
            <div class="mt-4 p-3 bg-blue-50 rounded-lg border border-blue-100">
                <div class="flex items-start">
                    <svg class="w-5 h-5 text-blue-500 mt-0.5 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <div class="text-sm text-blue-700">
                        <p class="font-medium">Conseils pour les photos :</p>
                        <ul class="mt-1 list-disc list-inside text-blue-600 space-y-0.5">
                            <li>Photographiez le colis remis au client</li>
                            <li>Prenez une photo de l'adresse si visible</li>
                            <li>En cas d'absence, photographiez le point de dépôt</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Actions Statut -->
    @if(count($this->availableStatusActions) > 0)
        <div class="fixed bottom-0 left-0 right-0 p-4 bg-white border-t shadow-lg md:relative md:bg-transparent md:border-t-0 md:shadow-none md:p-0 mb-4 md:mb-0">
            @foreach($this->availableStatusActions as $statusKey => $label)
                @php
                    $btnColor = $statusKey === 'arrive_depot' ? 'bg-purple-600 hover:bg-purple-700' : 'bg-indigo-600 hover:bg-indigo-700';
                @endphp
                <button wire:click="initiateStatusChange('{{ $statusKey }}')" 
                    class="w-full {{ $btnColor }} text-white rounded-xl py-4 font-bold text-lg shadow-lg transform transition active:scale-95 flex items-center justify-center mb-2">
                    <span>{{ $label }}</span>
                    <svg class="h-6 w-6 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                    </svg>
                </button>
            @endforeach
        </div>
    @elseif($produit->statut === 'livre')
         <div class="p-4 bg-green-50 text-green-700 rounded-lg text-center font-bold">
            ✅ Ce colis a été livré au client avec succès.
         </div>
    @elseif($produit->statut === 'stockage' && is_null($produit->camion_id))
         <div class="p-4 bg-purple-50 text-purple-700 rounded-lg text-center font-bold">
            🏪 Colis remis au dépôt. Fin de votre mission !
         </div>
    @endif

    <!-- Modal de confirmation -->
    @if($confirmingStatusChange)
        <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-indigo-100 sm:mx-0 sm:h-10 sm:w-10">
                                <svg class="h-6 w-6 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                </svg>
                            </div>
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                                <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">Confirmer le changement de statut</h3>
                                <div class="mt-2">
                                    <p class="text-sm text-gray-500">
                                        Êtes-vous sûr de vouloir changer le statut vers <span class="font-bold">{{ ucfirst(str_replace('_', ' ', $nextStatus)) }}</span> ? Cette action est irréversible.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button wire:click="confirmStatusChange" type="button" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:ml-3 sm:w-auto sm:text-sm">
                            Confirmer
                        </button>
                        <button wire:click="cancelStatusChange" type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                            Annuler
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
