<div class="py-6">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Messages Flash -->
        @if (session()->has('message'))
            <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg">
                ✅ {{ session('message') }}
            </div>
        @endif
        @if (session()->has('error'))
            <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg">
                ❌ {{ session('error') }}
            </div>
        @endif

        <!-- Banner Permission -->
        @if(!$this->canManage)
            <div class="mb-6 p-4 bg-orange-100 border-l-4 border-orange-500 text-orange-700">
                <p class="font-bold">🔒 Mode Lecture Seule</p>
                <p>Ce colis est actuellement sous la responsabilité du dépôt <span class="font-bold">{{ $produit->depotActuel->nom ?? 'Inconnu' }}</span>. Vous ne pouvez pas le modifier.</p>
            </div>
        @endif

        <!-- Header -->
        <div class="mb-6 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                <a href="{{ route('admin.produits.gestion') }}" class="text-indigo-600 hover:text-indigo-800 text-sm mb-2 inline-block">
                    ← Retour à la liste
                </a>
                <h2 class="text-2xl font-bold text-gray-800">📦 {{ $produit->nom }}</h2>
                <p class="text-sm text-gray-500 font-mono">QR: {{ $produit->qr_code }}</p>
            </div>
            <div class="flex gap-2">
                <!-- Statut badge -->
                @php
                    $statutColors = [
                        'stockage' => 'bg-yellow-500',
                        'valide' => 'bg-gray-500',
                        'prepare' => 'bg-blue-500',
                        'en_route' => 'bg-orange-500',
                        'en_attente_reception' => 'bg-purple-500',
                        'livre' => 'bg-green-500',
                    ];
                @endphp
                <span class="px-4 py-2 rounded-lg text-white text-lg font-bold {{ $statutColors[$produit->statut] ?? 'bg-gray-500' }}">
                    {{ ucfirst(str_replace('_', ' ', $produit->statut)) }}
                </span>
            </div>
        </div>

        <!-- Banner En Attente de Réception -->
        @if($produit->statut === 'en_attente_reception')
            <div class="mb-6 p-6 bg-purple-100 border-2 border-purple-400 rounded-lg">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="font-bold text-purple-900 text-lg">📦 Colis en attente de réception</p>
                        <p class="text-purple-700">Ce colis a été livré par un livreur et attend la confirmation de réception.</p>
                        @if($produit->depotDestination)
                            <p class="text-sm text-purple-600 mt-1">Destination: <strong>{{ $produit->depotDestination->nom }}</strong></p>
                        @endif
                    </div>
                    @if($this->canConfirmReception)
                        <button wire:click="confirmReception" class="px-6 py-3 bg-purple-700 text-white rounded-lg hover:bg-purple-800 font-bold text-lg shadow-lg">
                            ✅ Confirmer la réception
                        </button>
                    @endif
                </div>
            </div>
        @endif

        <!-- Onglets -->
        <div class="mb-6 border-b border-gray-200">
            <nav class="flex space-x-4">
                <button wire:click="$set('activeTab', 'info')" 
                    class="px-4 py-2 border-b-2 {{ $activeTab === 'info' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700' }}">
                    📋 Informations
                </button>
                <button wire:click="$set('activeTab', 'flotte')" 
                    class="px-4 py-2 border-b-2 {{ $activeTab === 'flotte' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700' }}">
                    🚚 Flotte & Dépôts
                </button>
                <button wire:click="$set('activeTab', 'tracking')" 
                    class="px-4 py-2 border-b-2 {{ $activeTab === 'tracking' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700' }}">
                    📍 Suivi & Photos ({{ $etapes->count() }})
                </button>
                <button wire:click="$set('activeTab', 'qr')" 
                    class="px-4 py-2 border-b-2 {{ $activeTab === 'qr' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700' }}">
                    🏷️ QR Code
                </button>
            </nav>
        </div>

        <!-- Contenu des onglets -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            
            <!-- Onglet Info -->
            @if($activeTab === 'info')
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-bold">Informations du Colis</h3>
                    @if($this->canManage)
                    <button wire:click="toggleEditMode" class="px-4 py-2 {{ $editMode ? 'bg-gray-500' : 'bg-indigo-600' }} text-white rounded-lg hover:opacity-90">
                        {{ $editMode ? '✕ Annuler' : '✏️ Modifier' }}
                    </button>
                    @endif
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Colonne gauche -->
                    <div class="space-y-4">
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h4 class="font-medium text-gray-700 mb-3">📦 Produit</h4>
                            @if($editMode)
                                <div class="space-y-3">
                                    <div>
                                        <label class="block text-sm text-gray-600">Nom</label>
                                        <input type="text" wire:model="nom" class="w-full border-gray-300 rounded-md">
                                    </div>
                                    <div>
                                        <label class="block text-sm text-gray-600">Description</label>
                                        <textarea wire:model="description" class="w-full border-gray-300 rounded-md" rows="2"></textarea>
                                    </div>
                                </div>
                            @else
                                <p class="font-medium">{{ $produit->nom }}</p>
                                <p class="text-sm text-gray-600">{{ $produit->description ?: 'Aucune description' }}</p>
                            @endif
                        </div>

                        <div class="bg-green-50 p-4 rounded-lg">
                            <h4 class="font-medium text-green-700 mb-3">⚖️ Poids & Dimensions</h4>
                            @if($editMode)
                                <div class="grid grid-cols-2 gap-3">
                                    <div>
                                        <label class="block text-sm text-gray-600">Poids (kg)</label>
                                        <input type="number" wire:model="poids" step="0.01" class="w-full border-gray-300 rounded-md">
                                    </div>
                                    <div>
                                        <label class="block text-sm text-gray-600">Volume (m³)</label>
                                        <input type="number" wire:model="volume" step="0.01" class="w-full border-gray-300 rounded-md">
                                    </div>
                                    <div>
                                        <label class="block text-sm text-gray-600">Longueur (cm)</label>
                                        <input type="number" wire:model="longueur" step="0.1" class="w-full border-gray-300 rounded-md">
                                    </div>
                                    <div>
                                        <label class="block text-sm text-gray-600">Largeur (cm)</label>
                                        <input type="number" wire:model="largeur" step="0.1" class="w-full border-gray-300 rounded-md">
                                    </div>
                                    <div>
                                        <label class="block text-sm text-gray-600">Hauteur (cm)</label>
                                        <input type="number" wire:model="hauteur" step="0.1" class="w-full border-gray-300 rounded-md">
                                    </div>
                                    <div>
                                        <label class="block text-sm text-gray-600">Poids Facturable (kg)</label>
                                        <input type="number" wire:model="poids_facturable" step="0.01" class="w-full border-gray-300 rounded-md bg-green-100">
                                    </div>
                                </div>
                            @else
                                <div class="grid grid-cols-2 gap-2 text-sm">
                                    <div>Poids réel: <strong>{{ $produit->poids ?? '-' }} kg</strong></div>
                                    <div>Volume: <strong>{{ $produit->volume ?? '-' }} m³</strong></div>
                                    <div>Dimensions: <strong>{{ $produit->longueur ?? '-' }} × {{ $produit->largeur ?? '-' }} × {{ $produit->hauteur ?? '-' }} cm</strong></div>
                                    <div>Poids Vol.: <strong>{{ $produit->poids_volumetrique ?? '-' }} kg</strong></div>
                                </div>
                                @if($produit->poids_facturable)
                                    <div class="mt-2 p-2 bg-green-200 rounded text-center">
                                        <span class="font-bold text-green-800">Poids Facturable: {{ number_format($produit->poids_facturable, 2) }} kg</span>
                                    </div>
                                @endif
                            @endif
                        </div>

                        <div class="bg-blue-50 p-4 rounded-lg">
                            <h4 class="font-medium text-blue-700 mb-3">📍 Trajet</h4>
                            @if($editMode)
                                <div class="space-y-3">
                                    <div>
                                        <label class="block text-sm text-gray-600">Source</label>
                                        <input type="text" wire:model="source" class="w-full border-gray-300 rounded-md">
                                    </div>
                                    <div>
                                        <label class="block text-sm text-gray-600">Destination</label>
                                        <input type="text" wire:model="destination" class="w-full border-gray-300 rounded-md">
                                    </div>
                                </div>
                            @else
                                <div class="flex items-center gap-2">
                                    <span class="text-gray-600">{{ $produit->source }}</span>
                                    <span class="text-2xl">→</span>
                                    <span class="font-bold text-blue-800">{{ $produit->destination }}</span>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Colonne droite -->
                    <div class="space-y-4">
                        <div class="bg-white border p-4 rounded-lg">
                            <h4 class="font-medium text-gray-700 mb-3">📬 Destinataire</h4>
                            @if($editMode)
                                <div class="space-y-3">
                                    <div class="grid grid-cols-2 gap-3">
                                        <div>
                                            <label class="block text-sm text-gray-600">Nom</label>
                                            <input type="text" wire:model="destinataire_nom" class="w-full border-gray-300 rounded-md">
                                        </div>
                                        <div>
                                            <label class="block text-sm text-gray-600">Prénom</label>
                                            <input type="text" wire:model="destinataire_prenom" class="w-full border-gray-300 rounded-md">
                                        </div>
                                    </div>
                                    <div>
                                        <label class="block text-sm text-gray-600">Téléphone</label>
                                        <div class="flex gap-2 mt-1" x-data="{
                                            open: false,
                                            search: '',
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
                                            selectedCode: '{{ $this->getPhoneCode($produit->destinataire_phone) }}',
                                            phoneNumber: '{{ $this->getPhoneLocal($produit->destinataire_phone) }}',
                                            get filtered() {
                                                if (!this.search) return this.codes;
                                                let s = this.search.toLowerCase();
                                                return Object.fromEntries(Object.entries(this.codes).filter(([k, v]) => v.toLowerCase().includes(s) || k.includes(s)));
                                            },
                                            get label() { return this.codes[this.selectedCode] || '🇹🇳 TN +216'; },
                                            updatePhone() {
                                                $wire.set('destinataire_phone', this.selectedCode + this.phoneNumber.replace(/[^0-9]/g, ''));
                                            }
                                        }">
                                            <div class="relative w-40">
                                                <button @click="open = !open" type="button" class="w-full flex items-center justify-between px-3 py-2 border border-gray-300 rounded-md bg-white text-sm hover:border-indigo-400">
                                                    <span x-text="label" class="truncate"></span>
                                                    <svg class="w-4 h-4 ml-1 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                                </button>
                                                <div x-show="open" @click.away="open = false" x-transition class="absolute z-50 mt-1 w-56 bg-white border border-gray-200 rounded-lg shadow-lg">
                                                    <div class="p-2">
                                                        <input x-model="search" type="text" placeholder="🔍 Chercher..." class="w-full px-2 py-1.5 text-sm border border-gray-300 rounded">
                                                    </div>
                                                    <div class="max-h-48 overflow-y-auto">
                                                        <template x-for="[code, lbl] in Object.entries(filtered)" :key="code">
                                                            <button @click="selectedCode = code; open = false; search = ''; updatePhone()" type="button" class="w-full text-left px-3 py-2 text-sm hover:bg-indigo-50" :class="selectedCode === code ? 'bg-indigo-100 font-semibold' : ''">
                                                                <span x-text="lbl"></span>
                                                            </button>
                                                        </template>
                                                    </div>
                                                </div>
                                            </div>
                                            <input type="text" x-model="phoneNumber" @input="updatePhone()" placeholder="55 123 456" class="flex-1 border-gray-300 rounded-md">
                                        </div>
                                    </div>
                                    <div>
                                        <label class="block text-sm text-gray-600">Ville</label>
                                        <input type="text" wire:model="destinataire_ville" class="w-full border-gray-300 rounded-md">
                                    </div>
                                    <div>
                                        <label class="block text-sm text-gray-600">Adresse</label>
                                        <textarea wire:model="destinataire_adresse" class="w-full border-gray-300 rounded-md" rows="2"></textarea>
                                    </div>
                                </div>
                            @else
                                <p class="font-bold text-lg">{{ $produit->destinataire_nom }} {{ $produit->destinataire_prenom }}</p>
                                @if($produit->destinataire_phone)
                                    <p class="text-sm">📞 {{ $produit->destinataire_phone }}</p>
                                @endif
                                @if($produit->destinataire_ville)
                                    <p class="text-sm">🏙️ {{ $produit->destinataire_ville }}</p>
                                @endif
                                @if($produit->destinataire_adresse)
                                    <p class="text-sm text-gray-600">📍 {{ $produit->destinataire_adresse }}</p>
                                @endif
                            @endif
                        </div>

                        <div class="bg-white border p-4 rounded-lg">
                            <h4 class="font-medium text-gray-700 mb-3">👤 Expéditeur</h4>
                            <p class="font-medium">{{ $produit->expediteur_nom }}</p>
                            <p class="text-sm">📧 {{ $produit->expediteur_email }}</p>
                            <p class="text-sm">📞 {{ $produit->expediteur_phone }}</p>
                            @if($produit->expediteur_societe)
                                <p class="text-sm">🏢 {{ $produit->expediteur_societe }}</p>
                            @endif
                        </div>
                    </div>
                </div>

                @if($editMode)
                    <div class="mt-6 flex justify-end">
                        <button wire:click="saveInfo" class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                            ✓ Enregistrer les modifications
                        </button>
                    </div>
                @endif
            @endif

            <!-- Onglet Flotte & Dépôts -->
            @if($activeTab === 'flotte')
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Affectation Flotte -->
                    <div class="bg-indigo-50 p-6 rounded-lg">
                        <h3 class="text-lg font-bold text-indigo-900 mb-4">🚚 Affectation Flotte</h3>
                        
                        @if($produit->camion)
                            <div class="bg-white p-4 rounded-lg mb-4">
                                <p class="text-sm text-gray-500">Actuellement assigné à:</p>
                                <p class="text-xl font-bold text-indigo-700">{{ $produit->camion->immatriculation }}</p>
                                <p class="text-sm">{{ $produit->camion->modele }}</p>
                            </div>
                            @if($this->canManage)
                            <button wire:click="removeFromFleet" class="w-full px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 font-bold shadow">
                                ↩️ Retirer de la flotte (Retour stockage)
                            </button>
                            @endif
                        @else
                            <div class="bg-yellow-100 p-4 rounded-lg mb-4 text-center">
                                <p class="text-yellow-800 font-bold">📦 Ce colis est en stockage</p>
                                <p class="text-sm text-yellow-700">Non assigné à aucun camion</p>
                            </div>
                            
                            <div class="space-y-3">
                                <select wire:model="camion_id" class="w-full border-gray-300 rounded-md" @if(!$this->canManage) disabled @endif>
                                    <option value="">-- Choisir un camion --</option>
                                    @foreach($camions as $camion)
                                        <option value="{{ $camion->id }}">
                                            {{ $camion->immatriculation }} - {{ $camion->modele }}
                                            ({{ $camion->statut == 'disponible' ? '✅' : '🚛' }})
                                        </option>
                                    @endforeach
                                </select>
                                @if($this->canManage)
                                <button wire:click="assignToFleet" class="w-full px-4 py-3 bg-gray-900 text-white rounded-lg hover:bg-black font-bold text-lg shadow-lg border border-gray-700">
                                    🚚 Affecter à la flotte
                                </button>
                                @endif
                            </div>
                        @endif
                    </div>

                    <!-- Dépôts -->
                    <div class="bg-purple-50 p-6 rounded-lg">
                        <h3 class="text-lg font-bold text-purple-900 mb-4">🏪 Dépôts</h3>
                        
                        <div class="space-y-4">
                            <div class="bg-blue-50 p-4 rounded-lg border border-blue-200">
                                <p class="text-sm text-blue-800 font-bold mb-2">🎯 Destination (Instruction Livreur):</p>
                                <select wire:model="depot_destination_id" class="w-full border-gray-300 rounded-md text-sm" @if(!$this->canManage) disabled @endif>
                                    <option value="">🏠 Livraison directe au Client</option>
                                    @foreach($depots as $depot)
                                        <option value="{{ $depot->id }}">
                                            🏪 {{ $depot->code }} - {{ $depot->nom }} ({{ $depot->ville }})
                                        </option>
                                    @endforeach
                                </select>
                                @if($this->canManage)
                                <button wire:click="updateDestinationTarget" class="w-full mt-2 px-3 py-2 bg-blue-700 text-white rounded text-sm font-bold hover:bg-blue-800 shadow">
                                    💾 Définir la cible
                                </button>
                                @endif
                            </div>


                        </div>
                    </div>
                </div>

                <!-- Changer le statut -->
                <div class="mt-6 bg-gray-50 p-6 rounded-lg">
                    <h3 class="text-lg font-bold text-gray-800 mb-4">📊 Changer le Statut</h3>
                    @if($this->canManage)
                    <div class="flex flex-wrap gap-2">
                        @foreach(['stockage' => '📦 Stockage', 'valide' => '✓ Validé', 'prepare' => '🔧 Préparé', 'en_route' => '🚚 En Route', 'livre' => '✅ Livré'] as $statut => $label)
                            <button 
                                wire:click="changeStatut('{{ $statut }}')"
                                class="px-4 py-2 rounded-lg transition font-medium {{ $produit->statut === $statut ? 'bg-gray-900 text-white border border-gray-900' : 'bg-white border border-gray-300 text-gray-800 hover:border-indigo-500 hover:bg-indigo-50' }}"
                            >
                                {{ $label }}
                            </button>
                        @endforeach
                    </div>
                    @else
                        <p class="text-gray-500 italic bg-white p-3 rounded border text-center">🚫 Modification du statut réservée à l'administrateur du dépôt actuel.</p>
                    @endif
                </div>
            @endif

            <!-- Onglet Tracking & Photos -->
            @if($activeTab === 'tracking')
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6">
                    <div>
                        <h3 class="text-lg font-bold text-gray-900">📍 Historique de Suivi & Photos</h3>
                        <p class="text-sm text-gray-600">Ajoutez des étapes avec photos pour documenter le parcours du colis</p>
                    </div>
                    @if($this->canManage)
                    <button wire:click="openAddEtapeModal" class="px-6 py-3 bg-gray-900 text-white rounded-lg hover:bg-black font-bold shadow-lg text-lg border border-gray-700">
                        📸 + Ajouter une étape
                    </button>
                    @endif
                </div>

                <!-- Boutons rapides pour photos -->
                @if($this->canManage)
                <div class="mb-6 p-4 bg-gray-100 rounded-lg border border-gray-300">
                    <h4 class="font-bold text-gray-800 mb-3">📸 Actions rapides:</h4>
                    <div class="flex flex-wrap gap-2">
                        <button wire:click="openAddEtapeModal" class="px-4 py-2 bg-blue-100 text-blue-700 border border-blue-200 rounded-lg hover:bg-blue-200 text-sm font-bold shadow-sm transition">
                            📥 Photo Réception
                        </button>
                        <button wire:click="openAddEtapeModal" class="px-4 py-2 bg-orange-100 text-orange-700 border border-orange-200 rounded-lg hover:bg-orange-200 text-sm font-bold shadow-sm transition">
                            🚛 Photo Chargement
                        </button>
                        <button wire:click="openAddEtapeModal" class="px-4 py-2 bg-purple-100 text-purple-700 border border-purple-200 rounded-lg hover:bg-purple-200 text-sm font-bold shadow-sm transition">
                            🏪 Photo Dépôt
                        </button>
                        <button wire:click="openAddEtapeModal" class="px-4 py-2 bg-green-100 text-green-700 border border-green-200 rounded-lg hover:bg-green-200 text-sm font-bold shadow-sm transition">
                            ✅ Photo Livraison
                        </button>
                    </div>
                </div>
                @endif

                <!-- Timeline -->
                <div class="space-y-4">
                    @forelse($etapes as $etape)
                        <div class="flex gap-4 p-4 bg-gray-50 rounded-lg">
                            <div class="flex-shrink-0">
                                <div class="w-10 h-10 rounded-full bg-indigo-100 flex items-center justify-center">
                                    @php
                                        $icons = [
                                            'stockage' => '📦',
                                            'affecte_flotte' => '🚚',
                                            'retour_stockage' => '↩️',
                                            'arrivee_depot' => '🏪',
                                            'en_route' => '🛣️',
                                            'livre' => '✅',
                                            'photo' => '📸',
                                        ];
                                    @endphp
                                    <span class="text-xl">{{ $icons[$etape->statut] ?? '📍' }}</span>
                                </div>
                            </div>
                            <div class="flex-grow">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <p class="font-medium text-gray-900">{{ $etape->description }}</p>
                                        <p class="text-sm text-gray-500">
                                            Par: {{ $etape->user?->nom ?? 'Système' }}
                                            @if($etape->depot)
                                                • Dépôt: {{ $etape->depot->code }}
                                            @endif
                                        </p>
                                    </div>
                                    <span class="text-xs text-gray-400">{{ $etape->created_at->format('d/m/Y H:i') }}</span>
                                </div>
                                
                                <!-- Photos de l'étape -->
                                @if($etape->photos && count($etape->photos) > 0)
                                    <div class="mt-3 flex flex-wrap gap-2">
                                        @foreach($etape->photos as $photo)
                                            <a href="{{ Storage::url($photo) }}" target="_blank">
                                                <img src="{{ Storage::url($photo) }}" alt="Photo" class="w-20 h-20 object-cover rounded-lg border hover:opacity-75 transition">
                                            </a>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-12 text-gray-500">
                            <div class="text-4xl mb-2">📭</div>
                            <p>Aucune étape enregistrée</p>
                            <p class="text-sm">Ajoutez des étapes pour suivre le parcours du colis</p>
                        </div>
                    @endforelse
                </div>
            @endif

            <!-- Onglet QR Code -->
            @if($activeTab === 'qr')
                <div class="text-center">
                    <h3 class="text-lg font-bold mb-6">🏷️ QR Code du Colis</h3>
                    
                    <div class="inline-block p-6 bg-white border-2 border-gray-200 rounded-lg">
                        {!! \SimpleSoftwareIO\QrCode\Facades\QrCode::size(250)->generate($produit->qr_code) !!}
                    </div>
                    
                    <p class="mt-4 font-mono text-sm text-gray-500">{{ $produit->qr_code }}</p>
                    
                    <div class="mt-6 flex justify-center gap-4">
                        <a href="{{ route('admin.produits.qr', $produit->id) }}" target="_blank" 
                            class="px-6 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">
                            📄 Télécharger PDF
                        </a>
                        <a href="{{ route('admin.produits.qr.image', $produit->id) }}" 
                            class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                            🖼️ Télécharger Image
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Modal Ajouter Étape -->
    @if($showAddEtapeModal)
        <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-white rounded-lg shadow-xl max-w-lg w-full mx-4">
                <div class="p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">📍 Ajouter une étape de suivi</h3>
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Type d'étape</label>
                            <select wire:model="newEtapeStatut" class="w-full border-gray-300 rounded-md">
                                <option value="">-- Sélectionner --</option>
                                <option value="reception">📥 Réception</option>
                                <option value="verification">🔍 Vérification</option>
                                <option value="emballage">📦 Emballage</option>
                                <option value="chargement">🚛 Chargement</option>
                                <option value="depart">🚀 Départ</option>
                                <option value="transit">🛣️ En Transit</option>
                                <option value="arrivee_depot">🏪 Arrivée Dépôt</option>
                                <option value="tentative_livraison">🚪 Tentative Livraison</option>
                                <option value="livre">✅ Livré</option>
                                <option value="retour">↩️ Retour</option>
                                <option value="probleme">⚠️ Problème</option>
                                <option value="photo">📸 Photo/Preuve</option>
                            </select>
                            @error('newEtapeStatut') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                            <textarea wire:model="newEtapeDescription" class="w-full border-gray-300 rounded-md" rows="3" 
                                placeholder="Décrivez l'étape..."></textarea>
                            @error('newEtapeDescription') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">📸 Photos (optionnel)</label>
                            <input type="file" wire:model="newEtapePhotos" multiple accept="image/*" 
                                class="w-full border border-gray-300 rounded-md p-2">
                            <p class="text-xs text-gray-500 mt-1">Max 5MB par image. Plusieurs fichiers autorisés.</p>
                            
                            @if($newEtapePhotos)
                                <div class="mt-2 flex flex-wrap gap-2">
                                    @foreach($newEtapePhotos as $photo)
                                        <img src="{{ $photo->temporaryUrl() }}" class="w-16 h-16 object-cover rounded">
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="mt-6 flex justify-end gap-3">
                        <button wire:click="closeAddEtapeModal" class="px-4 py-2 text-gray-600 hover:text-gray-900 bg-gray-100 rounded-lg">
                            Annuler
                        </button>
                        <button wire:click="addEtape" class="px-4 py-2 bg-gray-900 text-white rounded-lg hover:bg-black font-bold">
                            ✓ Ajouter l'étape
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
