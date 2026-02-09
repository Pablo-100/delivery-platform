<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Mes Colis') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="mb-4 flex flex-wrap gap-3">
                <a href="{{ route('admin.produits.create') }}" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition">
                    + Nouveau Colis
                </a>
                <a href="{{ route('admin.produits.gestion') }}" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition">
                    🚚 Gestion Stock & Affectation Flotte
                </a>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left rtl:text-right text-gray-500">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3">Produit / QR</th>
                                    <th scope="col" class="px-6 py-3">Destinataire</th>
                                    <th scope="col" class="px-6 py-3">Destination</th>
                                    <th scope="col" class="px-6 py-3">Statut</th>
                                    <th scope="col" class="px-6 py-3">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach(auth()->user()->produits()->latest()->get() as $produit)
                                <tr class="bg-white border-b hover:bg-gray-50">
                                    <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                                        {{ $produit->nom }}
                                        <div class="text-xs text-gray-400 font-mono">{{ $produit->qr_code }}</div>
                                    </th>
                                    <td class="px-6 py-4">{{ $produit->destinataire_nom }}</td>
                                    <td class="px-6 py-4">{{ $produit->destination }}</td>
                                    <td class="px-6 py-4">
                                        <span class="px-2 py-1 rounded text-xs text-white 
                                            {{ $produit->statut === 'livre' ? 'bg-green-500' : 
                                               ($produit->statut === 'en_route' ? 'bg-orange-500' : 
                                               ($produit->statut === 'prepare' ? 'bg-blue-500' : 'bg-gray-500')) }}">
                                            {{ ucfirst(str_replace('_', ' ', $produit->statut)) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                         <!-- Génération QR via simple-qrcode -->
                                        <div class="visible-print text-center mb-2">
                                            {!! \SimpleSoftwareIO\QrCode\Facades\QrCode::size(50)->generate($produit->qr_code) !!}
                                        </div>
                                        <div class="flex flex-col space-y-1">
                                            <a href="{{ route('admin.produits.qr', $produit->id) }}" target="_blank" class="text-xs text-center text-indigo-600 hover:text-indigo-900 border border-indigo-600 rounded px-2 py-1 hover:bg-indigo-50">
                                                Imprimer Étiquette (PDF)
                                            </a>
                                            <a href="{{ route('admin.produits.qr.image', $produit->id) }}" class="text-xs text-center text-green-600 hover:text-green-900 border border-green-600 rounded px-2 py-1 hover:bg-green-50">
                                                Télécharger Image (QR)
                                            </a>
                                        </div>
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
