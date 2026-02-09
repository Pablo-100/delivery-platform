<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard Admin') }} - {{ auth()->user()->company_info }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Actions Rapides -->
            <div class="mb-8 flex space-x-4">
                <a href="{{ route('admin.produits.gestion') }}" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    📦 Gestion Stock
                </a>
                <a href="{{ route('admin.produits.create') }}" class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    ➕ Nouveau Produit
                </a>
                <a href="{{ route('admin.camions.index') }}" class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    🚚 Gérer la Flotte
                </a>
            </div>

            <!-- Statistiques -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="text-gray-500 text-sm font-bold">Mes Colis Total</div>
                    <div class="text-3xl font-bold text-gray-800 mt-2">{{ auth()->user()->produits()->count() }}</div>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="text-gray-500 text-sm font-bold">Valides / À Préparer</div>
                    <div class="text-3xl font-bold text-indigo-600 mt-2">{{ auth()->user()->produits()->whereIn('statut', ['valide', 'prepare'])->count() }}</div>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="text-gray-500 text-sm font-bold">En Route</div>
                    <div class="text-3xl font-bold text-orange-500 mt-2">{{ auth()->user()->produits()->where('statut', 'en_route')->count() }}</div>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="text-gray-500 text-sm font-bold">Livrés</div>
                    <div class="text-3xl font-bold text-green-600 mt-2">{{ auth()->user()->produits()->where('statut', 'livre')->count() }}</div>
                </div>
            </div>

            <!-- Derniers Colis -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-bold mb-4">Derniers Colis Ajoutés</h3>
                    <div class="relative overflow-x-auto">
                        <table class="w-full text-sm text-left rtl:text-right text-gray-500">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3">Produit</th>
                                    <th scope="col" class="px-6 py-3">Destinataire</th>
                                    <th scope="col" class="px-6 py-3">Destination</th>
                                    <th scope="col" class="px-6 py-3">Statut</th>
                                    <th scope="col" class="px-6 py-3">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach(auth()->user()->produits()->latest()->take(5)->get() as $produit)
                                <tr class="bg-white border-b hover:bg-gray-50">
                                    <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                                        {{ $produit->nom }}
                                        <div class="text-xs text-gray-400">{{ $produit->qr_code }}</div>
                                    </th>
                                    <td class="px-6 py-4">{{ $produit->destinataire_nom }} {{ $produit->destinataire_prenom }}</td>
                                    <td class="px-6 py-4">{{ $produit->destination }}</td>
                                    <td class="px-6 py-4">
                                        <span class="px-2 py-1 rounded text-xs text-white 
                                            {{ $produit->statut === 'livre' ? 'bg-green-500' : 
                                               ($produit->statut === 'en_route' ? 'bg-orange-500' : 'bg-blue-500') }}">
                                            {{ ucfirst(str_replace('_', ' ', $produit->statut)) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <a href="#" class="text-indigo-600 hover:text-indigo-900">QR</a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
