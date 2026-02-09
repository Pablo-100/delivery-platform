<div class="p-6">
    <div class="mb-6 flex justify-between items-center" style="display: flex !important; justify-content: space-between !important;">
        <h2 class="text-2xl font-bold text-gray-900">Gestion des Colis</h2>
        <button 
            wire:click="create" 
            class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700"
            style="display: block !important;"
        >
            + Nouveau Colis
        </button>
    </div>

    @if (session()->has('message'))
        <div class="mb-4 p-4 bg-green-50 border border-green-200 rounded-lg">
            <p class="text-green-700">{{ session('message') }}</p>
        </div>
    @endif

    @if (session()->has('sms_status'))
        <div class="mb-4 p-4 bg-blue-50 border border-blue-200 rounded-lg">
            <p class="text-blue-700">{{ session('sms_status') }}</p>
        </div>
    @endif

    @if (session()->has('sms_error'))
        <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-lg">
            <p class="text-red-700">{{ session('sms_error') }}</p>
        </div>
    @endif

    <!-- Barre de recherche -->
    <div class="mb-6">
        <input 
            type="text" 
            wire:model.live="search" 
            placeholder="Rechercher par code, expéditeur, destinataire..."
            class="w-full px-4 py-2 border border-gray-300 rounded-lg"
        >
    </div>

    <!-- Liste des colis -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Code</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nom</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Destinataire</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Poids</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Dépôt</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Statut</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($colis as $col)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                        {{ $col->code_colis }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                        {{ $col->nom }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                        {{ $col->destinataire_nom }} {{ $col->destinataire_prenom }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                        {{ $col->poids }} kg
                        @if($col->poids_volumetrique)
                            <br><span class="text-xs text-gray-500">Vol: {{ number_format($col->poids_volumetrique, 2) }} kg</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                        {{ $col->depotActuel?->code ?? 'N/A' }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full
                            @if($col->statut === 'livre') bg-green-100 text-green-800
                            @elseif($col->statut === 'en_transit') bg-blue-100 text-blue-800
                            @elseif($col->statut === 'affecte') bg-purple-100 text-purple-800
                            @elseif($col->statut === 'en_attente') bg-yellow-100 text-yellow-800
                            @else bg-gray-100 text-gray-800
                            @endif
                        ">
                            {{ ucfirst(str_replace('_', ' ', $col->statut)) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                        @if($col->statut === 'en_attente')
                            <div class="flex gap-2">
                                <button 
                                    wire:click="recevoirColis({{ $col->id }})"
                                    class="text-blue-600 hover:text-blue-900"
                                >
                                    📸 Recevoir
                                </button>
                                
                                <!-- Affecter à une flotte -->
                                <select 
                                    wire:change="affecterFlotte({{ $col->id }}, $event.target.value)"
                                    class="text-sm border-gray-300 rounded"
                                >
                                    <option value="">Affecter...</option>
                                    @foreach($camions as $camion)
                                        <option value="{{ $camion->id }}">
                                            {{ $camion->immatriculation }} 
                                            @if($camion->livreur)
                                                ({{ $camion->livreur->nom }})
                                            @endif
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-4 text-center text-gray-500">
                        Aucun colis trouvé
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $colis->links() }}
    </div>

    <!-- Modal -->
    @if($showModal)
    <div class="fixed inset-0 bg-gray-500 bg-opacity-75 flex items-center justify-center z-50">
        <div class="bg-white rounded-lg shadow-xl max-w-4xl w-full max-h-[90vh] overflow-y-auto p-6">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-xl font-bold">
                    {{ $colisId ? 'Recevoir Colis' : 'Nouveau Colis' }}
                </h3>
                <button wire:click="$set('showModal', false)" class="text-gray-500 hover:text-gray-700">
                    ✕
                </button>
            </div>

            @if($colisId)
                <!-- Formulaire de réception -->
                <form wire:submit.prevent="saveReception">
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Photo de réception *</label>
                            <input 
                                type="file" 
                                wire:model="photo_reception" 
                                accept="image/*"
                                class="w-full"
                            >
                            @error('photo_reception') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            
                            @if($photo_reception)
                                <img src="{{ $photo_reception->temporaryUrl() }}" class="mt-2 w-32 h-32 object-cover rounded">
                            @endif
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Commentaire</label>
                            <textarea 
                                wire:model="commentaire_reception" 
                                rows="3"
                                class="w-full border-gray-300 rounded-lg"
                            ></textarea>
                        </div>

                        <div class="flex justify-end gap-3">
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
                                Enregistrer Réception
                            </button>
                        </div>
                    </div>
                </form>
            @else
                <!-- Formulaire de création -->
                <form wire:submit.prevent="store">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Code Colis -->
                        <div class="col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Code Colis</label>
                            <input 
                                type="text" 
                                wire:model="code_colis" 
                                readonly
                                class="w-full border-gray-300 rounded-lg bg-gray-50"
                            >
                        </div>

                        <!-- Informations produit -->
                        <div class="col-span-2">
                            <h4 class="font-semibold text-gray-900 mb-2">Informations du Colis</h4>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Nom *</label>
                            <input type="text" wire:model="nom" class="w-full border-gray-300 rounded-lg">
                            @error('nom') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Poids (kg) *</label>
                            <input type="number" step="0.01" wire:model="poids" class="w-full border-gray-300 rounded-lg">
                            @error('poids') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Longueur (cm)</label>
                            <input type="number" step="0.01" wire:model.live="longueur" class="w-full border-gray-300 rounded-lg">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Largeur (cm)</label>
                            <input type="number" step="0.01" wire:model.live="largeur" class="w-full border-gray-300 rounded-lg">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Hauteur (cm)</label>
                            <input type="number" step="0.01" wire:model.live="hauteur" class="w-full border-gray-300 rounded-lg">
                        </div>

                        @if($poids_volumetrique)
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Poids Volumétrique</label>
                            <input 
                                type="text" 
                                value="{{ number_format($poids_volumetrique, 2) }} kg" 
                                readonly
                                class="w-full border-gray-300 rounded-lg bg-gray-50"
                            >
                        </div>
                        @endif

                        <div class="col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                            <textarea wire:model="description" rows="2" class="w-full border-gray-300 rounded-lg"></textarea>
                        </div>

                        <!-- Expéditeur -->
                        <div class="col-span-2 mt-4">
                            <h4 class="font-semibold text-gray-900 mb-2">Expéditeur</h4>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Nom *</label>
                            <input type="text" wire:model="expediteur_nom" class="w-full border-gray-300 rounded-lg">
                            @error('expediteur_nom') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Email *</label>
                            <input type="email" wire:model="expediteur_email" class="w-full border-gray-300 rounded-lg">
                            @error('expediteur_email') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Téléphone *</label>
                            <div class="flex gap-2">
                                <div x-data="{
                                    open: false,
                                    search: '',
                                    selected: @entangle('expediteur_phone_code'),
                                    codes: {
                                        '+216': '🇹🇳 TN +216',
                                        '+213': '🇩🇿 DZ +213',
                                        '+212': '🇲🇦 MA +212',
                                        '+218': '🇱🇾 LY +218',
                                        '+20': '🇪🇬 EG +20',
                                        '+966': '🇸🇦 SA +966',
                                        '+971': '🇦🇪 AE +971',
                                        '+974': '🇶🇦 QA +974',
                                        '+965': '🇰🇼 KW +965',
                                        '+973': '🇧🇭 BH +973',
                                        '+968': '🇴🇲 OM +968',
                                        '+962': '🇯🇴 JO +962',
                                        '+961': '🇱🇧 LB +961',
                                        '+970': '🇵🇸 PS +970',
                                        '+33': '🇫🇷 FR +33',
                                        '+49': '🇩🇪 DE +49',
                                        '+39': '🇮🇹 IT +39',
                                        '+34': '🇪🇸 ES +34',
                                        '+44': '🇬🇧 UK +44',
                                        '+32': '🇧🇪 BE +32',
                                        '+41': '🇨🇭 CH +41',
                                        '+31': '🇳🇱 NL +31',
                                        '+90': '🇹🇷 TR +90',
                                        '+1': '🇺🇸 US +1',
                                        '+221': '🇸🇳 SN +221',
                                        '+225': '🇨🇮 CI +225',
                                        '+237': '🇨🇲 CM +237',
                                    },
                                    get filtered() {
                                        if (!this.search) return this.codes;
                                        let s = this.search.toLowerCase();
                                        return Object.fromEntries(Object.entries(this.codes).filter(([k, v]) => v.toLowerCase().includes(s) || k.includes(s)));
                                    },
                                    get label() { return this.codes[this.selected] || '🇹🇳 TN +216'; }
                                }" class="relative w-44">
                                    <button @click="open = !open" type="button" class="w-full flex items-center justify-between px-3 py-2 border border-gray-300 rounded-lg bg-white text-sm hover:border-blue-400 focus:ring-2 focus:ring-blue-300">
                                        <span x-text="label" class="truncate"></span>
                                        <svg class="w-4 h-4 ml-1 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                    </button>
                                    <div x-show="open" @click.away="open = false" x-transition class="absolute z-50 mt-1 w-56 bg-white border border-gray-200 rounded-lg shadow-lg">
                                        <div class="p-2">
                                            <input x-model="search" type="text" placeholder="🔍 Chercher..." class="w-full px-2 py-1.5 text-sm border border-gray-300 rounded focus:ring-blue-300 focus:border-blue-400">
                                        </div>
                                        <div class="max-h-48 overflow-y-auto">
                                            <template x-for="[code, lbl] in Object.entries(filtered)" :key="code">
                                                <button @click="selected = code; open = false; search = ''" type="button" class="w-full text-left px-3 py-2 text-sm hover:bg-blue-50 flex items-center gap-2" :class="selected === code ? 'bg-blue-100 font-semibold' : ''">
                                                    <span x-text="lbl"></span>
                                                </button>
                                            </template>
                                        </div>
                                    </div>
                                </div>
                                <input type="text" wire:model="expediteur_phone" placeholder="20 123 456" class="flex-1 border-gray-300 rounded-lg">
                            </div>
                            @error('expediteur_phone') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Société</label>
                            <input type="text" wire:model="expediteur_societe" class="w-full border-gray-300 rounded-lg">
                        </div>

                        <!-- Destinataire -->
                        <div class="col-span-2 mt-4">
                            <h4 class="font-semibold text-gray-900 mb-2">Destinataire</h4>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Nom *</label>
                            <input type="text" wire:model="destinataire_nom" class="w-full border-gray-300 rounded-lg">
                            @error('destinataire_nom') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Prénom *</label>
                            <input type="text" wire:model="destinataire_prenom" class="w-full border-gray-300 rounded-lg">
                            @error('destinataire_prenom') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Téléphone *</label>
                            <div class="flex gap-2">
                                <div x-data="{
                                    open: false,
                                    search: '',
                                    selected: @entangle('destinataire_phone_code'),
                                    codes: {
                                        '+216': '🇹🇳 TN +216',
                                        '+213': '🇩🇿 DZ +213',
                                        '+212': '🇲🇦 MA +212',
                                        '+218': '🇱🇾 LY +218',
                                        '+20': '🇪🇬 EG +20',
                                        '+966': '🇸🇦 SA +966',
                                        '+971': '🇦🇪 AE +971',
                                        '+974': '🇶🇦 QA +974',
                                        '+965': '🇰🇼 KW +965',
                                        '+973': '🇧🇭 BH +973',
                                        '+968': '🇴🇲 OM +968',
                                        '+962': '🇯🇴 JO +962',
                                        '+961': '🇱🇧 LB +961',
                                        '+970': '🇵🇸 PS +970',
                                        '+33': '🇫🇷 FR +33',
                                        '+49': '🇩🇪 DE +49',
                                        '+39': '🇮🇹 IT +39',
                                        '+34': '🇪🇸 ES +34',
                                        '+44': '🇬🇧 UK +44',
                                        '+32': '🇧🇪 BE +32',
                                        '+41': '🇨🇭 CH +41',
                                        '+31': '🇳🇱 NL +31',
                                        '+90': '🇹🇷 TR +90',
                                        '+1': '🇺🇸 US +1',
                                        '+221': '🇸🇳 SN +221',
                                        '+225': '🇨🇮 CI +225',
                                        '+237': '🇨🇲 CM +237',
                                    },
                                    get filtered() {
                                        if (!this.search) return this.codes;
                                        let s = this.search.toLowerCase();
                                        return Object.fromEntries(Object.entries(this.codes).filter(([k, v]) => v.toLowerCase().includes(s) || k.includes(s)));
                                    },
                                    get label() { return this.codes[this.selected] || '🇹🇳 TN +216'; }
                                }" class="relative w-44">
                                    <button @click="open = !open" type="button" class="w-full flex items-center justify-between px-3 py-2 border border-gray-300 rounded-lg bg-white text-sm hover:border-blue-400 focus:ring-2 focus:ring-blue-300">
                                        <span x-text="label" class="truncate"></span>
                                        <svg class="w-4 h-4 ml-1 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                    </button>
                                    <div x-show="open" @click.away="open = false" x-transition class="absolute z-50 mt-1 w-56 bg-white border border-gray-200 rounded-lg shadow-lg">
                                        <div class="p-2">
                                            <input x-model="search" type="text" placeholder="🔍 Chercher..." class="w-full px-2 py-1.5 text-sm border border-gray-300 rounded focus:ring-blue-300 focus:border-blue-400">
                                        </div>
                                        <div class="max-h-48 overflow-y-auto">
                                            <template x-for="[code, lbl] in Object.entries(filtered)" :key="code">
                                                <button @click="selected = code; open = false; search = ''" type="button" class="w-full text-left px-3 py-2 text-sm hover:bg-blue-50 flex items-center gap-2" :class="selected === code ? 'bg-blue-100 font-semibold' : ''">
                                                    <span x-text="lbl"></span>
                                                </button>
                                            </template>
                                        </div>
                                    </div>
                                </div>
                                <input type="text" wire:model="destinataire_phone" placeholder="20 123 456" class="flex-1 border-gray-300 rounded-lg">
                            </div>
                            @error('destinataire_phone') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Ville *</label>
                            <input type="text" wire:model="destinataire_ville" class="w-full border-gray-300 rounded-lg">
                            @error('destinataire_ville') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <div class="col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Adresse *</label>
                            <textarea wire:model="destinataire_adresse" rows="2" class="w-full border-gray-300 rounded-lg"></textarea>
                            @error('destinataire_adresse') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <!-- Dépôts -->
                        <div class="col-span-2 mt-4">
                            <h4 class="font-semibold text-gray-900 mb-2">Localisation</h4>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Dépôt Source</label>
                            <select wire:model="depot_source_id" class="w-full border-gray-300 rounded-lg">
                                <option value="">Sélectionner...</option>
                                @foreach($depots as $depot)
                                    <option value="{{ $depot->id }}">{{ $depot->code }} - {{ $depot->nom }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Dépôt Destination</label>
                            <select wire:model="depot_destination_id" class="w-full border-gray-300 rounded-lg">
                                <option value="">Sélectionner...</option>
                                @foreach($depots as $depot)
                                    <option value="{{ $depot->id }}">{{ $depot->code }} - {{ $depot->nom }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="mt-6 flex justify-end gap-3">
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
                            Créer le Colis
                        </button>
                    </div>
                </form>
            @endif
        </div>
    </div>
    @endif
</div>
