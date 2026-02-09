<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Tous les Colis (Global)') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <p>Liste globale de tous les colis du système (Vue Super Admin).</p>
                    <table class="w-full text-sm text-left rtl:text-right text-gray-500 mt-4">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3">Produit</th>
                                <th scope="col" class="px-6 py-3">Société (Admin)</th>
                                <th scope="col" class="px-6 py-3">Statut</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach(\App\Models\Produit::with('admin')->latest()->paginate(20) as $produit)
                            <tr class="bg-white border-b">
                                <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                                    {{ $produit->nom }}
                                    <div class="text-xs text-gray-400">{{ $produit->qr_code }}</div>
                                </th>
                                <td class="px-6 py-4">{{ $produit->admin->company_info }}</td>
                                <td class="px-6 py-4">{{ $produit->statut }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="mt-4">
                        {{ \App\Models\Produit::with('admin')->latest()->paginate(20)->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
