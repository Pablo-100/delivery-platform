<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Flotte Globale') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <table class="w-full text-sm text-left text-gray-500">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                            <tr>
                                <th class="px-6 py-3">Immatriculation</th>
                                <th class="px-6 py-3">Modèle</th>
                                <th class="px-6 py-3">Propriétaire (Admin)</th>
                                <th class="px-6 py-3">Chauffeur Actuel</th>
                                <th class="px-6 py-3">Statut</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach(\App\Models\Camion::with(['admin', 'livreur', 'conducteur'])->paginate(15) as $camion)
                            <tr class="bg-white border-b hover:bg-gray-50">
                                <td class="px-6 py-4 font-bold text-gray-900">{{ $camion->immatriculation }}</td>
                                <td class="px-6 py-4">{{ $camion->modele }}</td>
                                <td class="px-6 py-4">{{ $camion->admin->company_info ?? $camion->admin->nom }}</td>
                                <td class="px-6 py-4">
                                    @php $chauffeur = $camion->conducteur ?? $camion->livreur; @endphp
                                    @if($chauffeur)
                                        <div class="flex items-center">
                                            <div class="w-6 h-6 rounded-full bg-gray-200 flex items-center justify-center text-xs font-bold mr-2">
                                                {{ substr($chauffeur->prenom, 0, 1) }}
                                            </div>
                                            {{ $chauffeur->prenom }} {{ $chauffeur->nom }}
                                        </div>
                                    @else
                                        <span class="text-gray-400 italic">Aucun</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                     <span class="px-2 py-1 rounded text-xs text-white 
                                        {{ $camion->statut === 'en_service' ? 'bg-green-500' : 
                                           ($camion->statut === 'maintenance' ? 'bg-red-500' : 'bg-blue-500') }}">
                                        {{ ucfirst(str_replace('_', ' ', $camion->statut)) }}
                                    </span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="mt-4">
                        {{ \App\Models\Camion::paginate(15)->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
