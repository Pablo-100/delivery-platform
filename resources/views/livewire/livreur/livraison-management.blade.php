<div class="p-6">
    <div class="mb-6 flex justify-between items-center">
        <h2 class="text-2xl font-bold text-gray-900">Mes Livraisons</h2>
        
        <div>
            @if($tourneeActive)
                <div class="flex items-center gap-4">
                    <div class="bg-green-100 px-4 py-2 rounded-lg">
                        <p class="text-sm text-green-800">Tournée en cours</p>
                        <p class="text-xs text-green-600">Début: {{ $tourneeActive->heure_debut->format('H:i') }}</p>
                        <p class="text-xs text-green-600">Colis: {{ $tourneeActive->nombre_colis }} ({{ $tourneeActive->colis_livres }} livrés)</p>
                    </div>
                    <button 
                        wire:click="terminerTournee"
                        class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700"
                    >
                        Terminer la Tournée
                    </button>
                </div>
            @else
                <button 
                    wire:click="demarrerTournee"
                    class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700"
                >
                    Démarrer une Tournée
                </button>
            @endif
        </div>
    </div>

    @if (session()->has('message'))
        <div class="mb-4 p-4 bg-green-50 border border-green-200 rounded-lg">
            <p class="text-green-700">{{ session('message') }}</p>
        </div>
    @endif

    @if (session()->has('error'))
        <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-lg">
            <p class="text-red-700">{{ session('error') }}</p>
        </div>
    @endif

    <!-- Mes colis à livrer -->
    <div class="bg-white rounded-lg shadow mb-6">
        <div class="p-6">
            <h3 class="text-lg font-semibold mb-4">Colis à Livrer</h3>
            
            @if($mesColis->count() > 0)
                <div class="space-y-4">
                    @foreach($mesColis as $colis)
                    <div class="border border-gray-200 rounded-lg p-4">
                        <div class="flex justify-between items-start mb-3">
                            <div>
                                <h4 class="font-semibold text-gray-900">{{ $colis->code_colis }}</h4>
                                <p class="text-sm text-gray-600">{{ $colis->nom }}</p>
                            </div>
                            <span class="inline-flex px-3 py-1 rounded-full text-xs font-semibold
                                @if($colis->statut === 'en_transit') bg-blue-100 text-blue-800
                                @else bg-yellow-100 text-yellow-800
                                @endif
                            ">
                                {{ ucfirst(str_replace('_', ' ', $colis->statut)) }}
                            </span>
                        </div>

                        <div class="grid grid-cols-2 gap-4 mb-3">
                            <div>
                                <p class="text-sm text-gray-500">Destinataire</p>
                                <p class="font-medium">{{ $colis->destinataire_nom }} {{ $colis->destinataire_prenom }}</p>
                                <p class="text-sm text-gray-600">{{ $colis->destinataire_ville }}</p>
                                <p class="text-sm text-gray-600">{{ $colis->destinataire_phone }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Adresse</p>
                                <p class="text-sm text-gray-700">{{ $colis->destinataire_adresse }}</p>
                            </div>
                        </div>

                        @if($colis->depotDestination)
                            <div class="mb-3">
                                <p class="text-sm text-gray-500">Dépôt de destination</p>
                                <p class="font-medium">{{ $colis->depotDestination->nom }} ({{ $colis->depotDestination->code }})</p>
                            </div>
                        @endif

                        <div class="flex gap-2">
                            @if($colis->statut === 'affecte')
                                <button 
                                    wire:click="prendreEnCharge({{ $colis->id }})"
                                    class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 text-sm"
                                >
                                    📸 Prendre en Charge
                                </button>
                            @endif

                            @if($colis->statut === 'en_transit')
                                @if($colis->depotDestination)
                                    <button 
                                        wire:click="transferer({{ $colis->id }})"
                                        class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 text-sm"
                                    >
                                        📸 Transférer au Dépôt
                                    </button>
                                @endif
                                
                                <button 
                                    wire:click="livrer({{ $colis->id }})"
                                    class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 text-sm"
                                >
                                    📸 Marquer comme Livré
                                </button>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
            @else
                <p class="text-gray-500 text-center py-8">Aucun colis à livrer pour le moment</p>
            @endif
        </div>
    </div>

    <!-- Historique récent -->
    <div class="bg-white rounded-lg shadow">
        <div class="p-6">
            <h3 class="text-lg font-semibold mb-4">Historique Récent (10 derniers)</h3>
            
            @if($historique->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Code</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Destinataire</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date Livraison</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach($historique as $colis)
                            <tr>
                                <td class="px-4 py-3 text-sm font-medium text-gray-900">{{ $colis->code_colis }}</td>
                                <td class="px-4 py-3 text-sm text-gray-700">
                                    {{ $colis->destinataire_nom }} {{ $colis->destinataire_prenom }}
                                </td>
                                <td class="px-4 py-3 text-sm text-gray-700">
                                    {{ $colis->date_livraison->format('d/m/Y H:i') }}
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="text-gray-500 text-center py-8">Aucun historique de livraison</p>
            @endif
        </div>
    </div>

    <!-- Modal -->
    @if($showModal)
    <div class="fixed inset-0 bg-gray-500 bg-opacity-75 flex items-center justify-center z-50">
        <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full p-6">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-xl font-bold">
                    @if($action === 'prendre_en_charge')
                        Prendre en Charge le Colis
                    @elseif($action === 'transferer')
                        Transférer au Dépôt
                    @else
                        Marquer comme Livré
                    @endif
                </h3>
                <button wire:click="$set('showModal', false)" class="text-gray-500 hover:text-gray-700">✕</button>
            </div>

            @if($selectedColis)
                <div class="mb-4 p-4 bg-gray-50 rounded-lg">
                    <p class="font-semibold">{{ $selectedColis->code_colis }}</p>
                    <p class="text-sm text-gray-600">{{ $selectedColis->destinataire_nom }} {{ $selectedColis->destinataire_prenom }}</p>
                </div>

                <form wire:submit.prevent="save">
                    <div class="space-y-4">
                        @if($action === 'transferer')
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Dépôt de destination *</label>
                                <select wire:model="depot_destination_id" class="w-full border-gray-300 rounded-lg">
                                    <option value="">Sélectionner un dépôt...</option>
                                    @foreach($depots as $depot)
                                        <option value="{{ $depot->id }}">{{ $depot->code }} - {{ $depot->nom }}</option>
                                    @endforeach
                                </select>
                                @error('depot_destination_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                        @endif

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Photo de preuve * 📸</label>
                            <input 
                                type="file" 
                                wire:model="photo" 
                                accept="image/*"
                                capture="environment"
                                class="w-full"
                            >
                            @error('photo') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            
                            @if($photo)
                                <img src="{{ $photo->temporaryUrl() }}" class="mt-2 w-48 h-48 object-cover rounded">
                            @endif
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Commentaire</label>
                            <textarea 
                                wire:model="commentaire" 
                                rows="3"
                                class="w-full border-gray-300 rounded-lg"
                                placeholder="Ajouter un commentaire..."
                            ></textarea>
                        </div>

                        <div class="flex justify-end gap-3 pt-4">
                            <button 
                                type="button"
                                wire:click="$set('showModal', false)"
                                class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50"
                            >
                                Annuler
                            </button>
                            <button 
                                type="submit"
                                class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700"
                            >
                                Confirmer
                            </button>
                        </div>
                    </div>
                </form>
            @endif
        </div>
    </div>
    @endif
</div>
