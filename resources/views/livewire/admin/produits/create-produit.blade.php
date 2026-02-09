<div class="space-y-6">
    <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
        <h3 class="text-lg font-medium leading-6 text-gray-900 mb-4">Informations du Colis</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Nom -->
            <div>
                <x-input-label for="nom" :value="__('Nom du produit / colis')" />
                <x-text-input wire:model="nom" id="nom" class="block mt-1 w-full" type="text" required />
                <x-input-error :messages="$errors->get('nom')" class="mt-2" />
            </div>

            <!-- Description -->
            <div>
                <x-input-label for="description" :value="__('Description')" />
                <x-text-input wire:model="description" id="description" class="block mt-1 w-full" type="text" />
                <x-input-error :messages="$errors->get('description')" class="mt-2" />
            </div>

            <!-- Poids -->
            <div>
                <x-input-label for="poids" :value="__('Poids réel (kg)')" />
                <x-text-input wire:model="poids" id="poids" class="block mt-1 w-full" type="number" step="0.01" />
                <x-input-error :messages="$errors->get('poids')" class="mt-2" />
            </div>

            <!-- Volume -->
            <div>
                <x-input-label for="volume" :value="__('Volume (m³)')" />
                <x-text-input wire:model="volume" id="volume" class="block mt-1 w-full" type="number" step="0.01" />
                <x-input-error :messages="$errors->get('volume')" class="mt-2" />
            </div>
        </div>
    </div>

    <!-- NOUVEAU: Dimensions et Poids Volumétrique -->
    <div class="bg-green-50 p-4 rounded-lg border border-green-200">
        <h3 class="text-lg font-medium leading-6 text-green-900 mb-2">📦 Dimensions & Poids Volumétrique</h3>
        <p class="text-sm text-green-700 mb-4">Le poids volumétrique est calculé automatiquement (comme FedEx/DHL): (L × l × h) / 5000</p>
        
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <!-- Longueur -->
            <div>
                <x-input-label for="longueur" :value="__('Longueur (cm)')" />
                <x-text-input wire:model.live="longueur" id="longueur" class="block mt-1 w-full" type="number" step="0.1" placeholder="Ex: 40" />
                <x-input-error :messages="$errors->get('longueur')" class="mt-2" />
            </div>

            <!-- Largeur -->
            <div>
                <x-input-label for="largeur" :value="__('Largeur (cm)')" />
                <x-text-input wire:model.live="largeur" id="largeur" class="block mt-1 w-full" type="number" step="0.1" placeholder="Ex: 30" />
                <x-input-error :messages="$errors->get('largeur')" class="mt-2" />
            </div>

            <!-- Hauteur -->
            <div>
                <x-input-label for="hauteur" :value="__('Hauteur (cm)')" />
                <x-text-input wire:model.live="hauteur" id="hauteur" class="block mt-1 w-full" type="number" step="0.1" placeholder="Ex: 20" />
                <x-input-error :messages="$errors->get('hauteur')" class="mt-2" />
            </div>

            <!-- Poids Volumétrique (Calculé) -->
            <div>
                <x-input-label for="poids_volumetrique" :value="__('Poids Volumétrique (kg)')" />
                <div class="mt-1 relative">
                    <x-text-input 
                        wire:model="poids_volumetrique" 
                        id="poids_volumetrique" 
                        class="block w-full bg-green-100 font-bold text-green-800" 
                        type="text" 
                        readonly 
                        placeholder="Calculé auto"
                    />
                    @if($poids_volumetrique)
                        <span class="absolute right-3 top-1/2 transform -translate-y-1/2 text-green-600">✓</span>
                    @endif
                </div>
                <p class="text-xs text-green-600 mt-1">= ({{ $longueur ?? 0 }} × {{ $largeur ?? 0 }} × {{ $hauteur ?? 0 }}) / 5000</p>
            </div>
        </div>

        <!-- Choix du poids facturable -->
        @if($poids || $poids_volumetrique)
            <div class="mt-4 p-4 rounded-lg bg-yellow-50 border border-yellow-300">
                <h4 class="text-sm font-bold text-yellow-800 mb-3">⚖️ Choisir le poids facturable:</h4>
                
                <div class="flex flex-col md:flex-row gap-4">
                    @if($poids)
                        <label class="flex items-center p-3 rounded-lg cursor-pointer transition-all {{ $poids_choisi === 'reel' ? 'bg-blue-100 border-2 border-blue-500 ring-2 ring-blue-200' : 'bg-white border border-gray-300 hover:border-blue-300' }}">
                            <input type="radio" wire:model.live="poids_choisi" value="reel" class="form-radio text-blue-600 h-5 w-5">
                            <div class="ml-3">
                                <span class="block font-bold text-gray-900">{{ number_format($poids, 2) }} kg</span>
                                <span class="text-xs text-gray-500">Poids Réel (balance)</span>
                            </div>
                        </label>
                    @endif
                    
                    @if($poids_volumetrique)
                        <label class="flex items-center p-3 rounded-lg cursor-pointer transition-all {{ $poids_choisi === 'volumetrique' ? 'bg-green-100 border-2 border-green-500 ring-2 ring-green-200' : 'bg-white border border-gray-300 hover:border-green-300' }}">
                            <input type="radio" wire:model.live="poids_choisi" value="volumetrique" class="form-radio text-green-600 h-5 w-5">
                            <div class="ml-3">
                                <span class="block font-bold text-gray-900">{{ number_format($poids_volumetrique, 2) }} kg</span>
                                <span class="text-xs text-gray-500">Poids Volumétrique (dimensions)</span>
                            </div>
                        </label>
                    @endif
                </div>

                @if($poids_choisi)
                    <div class="mt-3 p-2 rounded bg-blue-100 border border-blue-300">
                        <p class="text-sm font-bold text-blue-800">
                            ✅ Poids facturable sélectionné: <span class="text-lg">{{ $poids_choisi === 'reel' ? number_format($poids, 2) : number_format($poids_volumetrique, 2) }} kg</span>
                        </p>
                    </div>
                @else
                    <p class="mt-2 text-xs text-yellow-700">⚠️ Veuillez sélectionner le poids à utiliser pour la facturation</p>
                @endif
            </div>
        @endif
    </div>

    <!-- NOUVEAU: Dépôt Source -->
    <div class="bg-purple-50 p-4 rounded-lg border border-purple-200">
        <h3 class="text-lg font-medium leading-6 text-purple-900 mb-2">🏪 Dépôt Source</h3>
        <p class="text-sm text-purple-700 mb-4">Le colis sera créé dans votre dépôt</p>
        
        <div class="grid grid-cols-1 gap-6">
            <!-- Dépôt Source (readonly - affichage) -->
            <div>
                <x-input-label for="depot_source_display" :value="__('Dépôt Source (Point de départ)')" />
                @php
                    $adminDepot = $depots->where('admin_id', auth()->id())->first();
                @endphp
                @if($adminDepot)
                    <div class="mt-1 p-3 bg-purple-100 border border-purple-300 rounded-md text-purple-800 font-bold">
                        📍 {{ $adminDepot->code }} - {{ $adminDepot->nom }} ({{ $adminDepot->ville }})
                    </div>
                    <input type="hidden" wire:model="depot_source_id" value="{{ $adminDepot->id }}">
                @else
                    <div class="mt-1 p-3 bg-yellow-100 border border-yellow-300 rounded-md text-yellow-800">
                        ⚠️ Aucun dépôt assigné à votre compte. Contactez le Super Admin.
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="bg-blue-50 p-4 rounded-lg border border-blue-200">
        <h3 class="text-lg font-medium leading-6 text-blue-900 mb-4">📍 Adresses de Trajet</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Source -->
            <div>
                <x-input-label for="source" :value="__('Adresse de départ (Source)')" />
                <x-text-input wire:model="source" id="source" class="block mt-1 w-full" type="text" required placeholder="Ex: 123 Rue de Tunis, La Marsa" />
                <x-input-error :messages="$errors->get('source')" class="mt-2" />
            </div>

            <!-- Destination -->
            <div>
                <x-input-label for="destination" :value="__('Adresse d\'arrivée (Destination)')" />
                <x-text-input wire:model="destination" id="destination" class="block mt-1 w-full" type="text" required placeholder="Ex: 456 Avenue Habib Bourguiba, Sfax" />
                <x-input-error :messages="$errors->get('destination')" class="mt-2" />
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Expéditeur -->
        <div class="bg-white p-4 rounded-lg border border-gray-200 shadow-sm">
             <h3 class="text-lg font-medium leading-6 text-gray-900 mb-4">👤 Expéditeur</h3>
             
             <div class="space-y-4">
                <div>
                    <x-input-label for="expediteur_nom" :value="__('Nom complet')" />
                    <x-text-input wire:model="expediteur_nom" id="expediteur_nom" class="block mt-1 w-full" type="text" placeholder="Ex: Mohamed Ben Ali" />
                    <x-input-error :messages="$errors->get('expediteur_nom')" class="mt-2" />
                </div>
                <div>
                    <x-input-label for="expediteur_email" :value="__('Email')" />
                    <x-text-input wire:model="expediteur_email" id="expediteur_email" class="block mt-1 w-full" type="email" required />
                    <x-input-error :messages="$errors->get('expediteur_email')" class="mt-2" />
                </div>
                <div>
                    <x-input-label for="expediteur_phone" :value="__('Téléphone *')" />
                    <div class="flex gap-2 mt-1">
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
                            <button @click="open = !open" type="button" class="w-full flex items-center justify-between px-3 py-2 border border-gray-300 rounded-md bg-white text-sm hover:border-indigo-400 focus:ring-2 focus:ring-indigo-300 shadow-sm">
                                <span x-text="label" class="truncate"></span>
                                <svg class="w-4 h-4 ml-1 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                            </button>
                            <div x-show="open" @click.away="open = false" x-transition class="absolute z-50 mt-1 w-56 bg-white border border-gray-200 rounded-lg shadow-lg">
                                <div class="p-2">
                                    <input x-model="search" type="text" placeholder="🔍 Chercher pays..." class="w-full px-2 py-1.5 text-sm border border-gray-300 rounded focus:ring-indigo-300 focus:border-indigo-400">
                                </div>
                                <div class="max-h-48 overflow-y-auto">
                                    <template x-for="[code, lbl] in Object.entries(filtered)" :key="code">
                                        <button @click="selected = code; open = false; search = ''" type="button" class="w-full text-left px-3 py-2 text-sm hover:bg-indigo-50 flex items-center" :class="selected === code ? 'bg-indigo-100 font-semibold' : ''">
                                            <span x-text="lbl"></span>
                                        </button>
                                    </template>
                                </div>
                            </div>
                        </div>
                        <x-text-input wire:model="expediteur_phone" id="expediteur_phone" class="flex-1" type="tel" required placeholder="55 123 456" />
                    </div>
                    <x-input-error :messages="$errors->get('expediteur_phone')" class="mt-2" />
                </div>
                <div>
                    <x-input-label for="expediteur_societe" :value="__('Société (Optionnel)')" />
                    <x-text-input wire:model="expediteur_societe" id="expediteur_societe" class="block mt-1 w-full" type="text" />
                </div>
                <div>
                    <x-input-label for="expediteur_matricule_fiscale" :value="__('Matricule Fiscale (Optionnel)')" />
                    <x-text-input wire:model="expediteur_matricule_fiscale" id="expediteur_matricule_fiscale" class="block mt-1 w-full" type="text" />
                </div>
             </div>
        </div>

        <!-- Destinataire -->
        <div class="bg-white p-4 rounded-lg border border-gray-200 shadow-sm">
             <h3 class="text-lg font-medium leading-6 text-gray-900 mb-4">📬 Destinataire</h3>
             
             <div class="space-y-4">
                <div>
                    <x-input-label for="destinataire_nom" :value="__('Nom')" />
                    <x-text-input wire:model="destinataire_nom" id="destinataire_nom" class="block mt-1 w-full" type="text" required />
                    <x-input-error :messages="$errors->get('destinataire_nom')" class="mt-2" />
                </div>
                <div>
                    <x-input-label for="destinataire_prenom" :value="__('Prénom')" />
                    <x-text-input wire:model="destinataire_prenom" id="destinataire_prenom" class="block mt-1 w-full" type="text" required />
                    <x-input-error :messages="$errors->get('destinataire_prenom')" class="mt-2" />
                </div>
                <div>
                    <x-input-label for="destinataire_phone" :value="__('Téléphone *')" />
                    <div class="flex gap-2 mt-1">
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
                            <button @click="open = !open" type="button" class="w-full flex items-center justify-between px-3 py-2 border border-gray-300 rounded-md bg-white text-sm hover:border-indigo-400 focus:ring-2 focus:ring-indigo-300 shadow-sm">
                                <span x-text="label" class="truncate"></span>
                                <svg class="w-4 h-4 ml-1 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                            </button>
                            <div x-show="open" @click.away="open = false" x-transition class="absolute z-50 mt-1 w-56 bg-white border border-gray-200 rounded-lg shadow-lg">
                                <div class="p-2">
                                    <input x-model="search" type="text" placeholder="🔍 Chercher pays..." class="w-full px-2 py-1.5 text-sm border border-gray-300 rounded focus:ring-indigo-300 focus:border-indigo-400">
                                </div>
                                <div class="max-h-48 overflow-y-auto">
                                    <template x-for="[code, lbl] in Object.entries(filtered)" :key="code">
                                        <button @click="selected = code; open = false; search = ''" type="button" class="w-full text-left px-3 py-2 text-sm hover:bg-indigo-50 flex items-center" :class="selected === code ? 'bg-indigo-100 font-semibold' : ''">
                                            <span x-text="lbl"></span>
                                        </button>
                                    </template>
                                </div>
                            </div>
                        </div>
                        <x-text-input wire:model="destinataire_phone" id="destinataire_phone" class="flex-1" type="tel" required placeholder="55 123 456" />
                    </div>
                    <x-input-error :messages="$errors->get('destinataire_phone')" class="mt-2" />
                </div>
                <div>
                    <x-input-label for="destinataire_ville" :value="__('Ville')" />
                    <x-text-input wire:model="destinataire_ville" id="destinataire_ville" class="block mt-1 w-full" type="text" placeholder="Ex: Sfax" />
                    <x-input-error :messages="$errors->get('destinataire_ville')" class="mt-2" />
                </div>
                <div>
                    <x-input-label for="destinataire_adresse" :value="__('Adresse complète')" />
                    <x-text-input wire:model="destinataire_adresse" id="destinataire_adresse" class="block mt-1 w-full" type="text" placeholder="Ex: 123 Rue de la Liberté" />
                    <x-input-error :messages="$errors->get('destinataire_adresse')" class="mt-2" />
                </div>
                <div>
                    <x-input-label for="destinataire_cin_3_derniers_chiffres" :value="__('CIN (3 derniers chiffres)')" />
                    <x-text-input wire:model="destinataire_cin_3_derniers_chiffres" id="destinataire_cin_3_derniers_chiffres" class="block mt-1 w-full" type="text" maxlength="3" required placeholder="Ex: 123" />
                    <x-input-error :messages="$errors->get('destinataire_cin_3_derniers_chiffres')" class="mt-2" />
                    <p class="text-xs text-gray-500 mt-1">Utilisé pour la vérification lors de la livraison.</p>
                </div>
             </div>
        </div>
    </div>

    <!-- Assignation -->
    <div class="bg-indigo-50 p-4 rounded-lg border border-indigo-200">
        <h3 class="text-lg font-medium leading-6 text-indigo-900 mb-2">🚚 Affectation Flotte</h3>
        <p class="text-sm text-indigo-700 mb-4">
            <strong>Note:</strong> Vous pouvez créer le colis sans l'affecter à une flotte. Il restera au dépôt en <span class="font-bold">stockage</span> jusqu'à l'affectation manuelle.
        </p>
        <div>
            <x-input-label for="camion_id" :value="__('Assigner à un camion (Optionnel)')" />
            <select wire:model="camion_id" id="camion_id" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                <option value="">-- Ne pas assigner pour l'instant (Stockage au dépôt) --</option>
                @foreach($camions as $camion)
                    <option value="{{ $camion->id }}">
                        {{ $camion->immatriculation }} - {{ $camion->modele }} 
                        ({{ $camion->statut == 'disponible' ? '✅ Disponible' : '🚛 En Service' }})
                    </option>
                @endforeach
            </select>
            <x-input-error :messages="$errors->get('camion_id')" class="mt-2" />
            <p class="text-xs text-indigo-600 mt-2">
                💡 Si vous ne sélectionnez pas de camion, le colis restera en <strong>stockage</strong> au dépôt. Vous pourrez l'affecter à une flotte plus tard depuis la liste des colis.
            </p>
        </div>
    </div>

    <div class="flex items-center justify-end gap-4">
        <a href="{{ route('admin.produits.index') }}" class="text-gray-600 hover:text-gray-900">
            Annuler
        </a>
        <x-primary-button wire:click="save" class="ml-4">
            {{ __('Créer le Colis & Générer QR') }}
        </x-primary-button>
    </div>
</div>
