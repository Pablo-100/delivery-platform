<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Effectif Livreurs') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <table class="w-full text-sm text-left text-gray-500">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                            <tr>
                                <th class="px-6 py-3">Livreur</th>
                                <th class="px-6 py-3">Email</th>
                                <th class="px-6 py-3">Camion Actuel</th>
                                <th class="px-6 py-3">Société (Admin)</th>
                                <th class="px-6 py-3">Date d'embauche</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach(\App\Models\User::where('role', 'livreur')->with('camion')->paginate(15) as $livreur)
                            <tr class="bg-white border-b hover:bg-gray-50">
                                <td class="px-6 py-4 font-bold text-gray-900">
                                    <div class="flex items-center">
                                        <div class="w-8 h-8 rounded-full bg-indigo-100 text-indigo-600 flex items-center justify-center font-bold mr-3">
                                            {{ substr($livreur->prenom, 0, 1) }}
                                        </div>
                                        <div>
                                            {{ $livreur->nom }} {{ $livreur->prenom }}
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">{{ $livreur->email }}</td>
                                <td class="px-6 py-4">
                                     @if($livreur->camion)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            {{ $livreur->camion->immatriculation }}
                                        </span>
                                    @else
                                        <span class="text-gray-400">Aucun</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    {{-- On essaie de trouver l'admin via company_info hack ou via le camion --}}
                                    @php
                                        $admin = \App\Models\User::find($livreur->company_info);
                                    @endphp
                                    {{ $admin ? ($admin->company_info ?? $admin->nom) : 'N/A' }}
                                </td>
                                <td class="px-6 py-4">{{ $livreur->created_at->format('d/m/Y') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="mt-4">
                        {{ \App\Models\User::where('role', 'livreur')->paginate(15)->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
