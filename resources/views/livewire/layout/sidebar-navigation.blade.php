<?php

use Livewire\Volt\Component;

new class extends Component
{
    //
}; ?>

<nav class="space-y-1">
    @if(auth()->user()->role === 'super_admin')
        <x-nav-link :href="route('super-admin.dashboard')" :active="request()->routeIs('super-admin.dashboard')" class="flex items-center px-4 py-3 text-gray-300 hover:bg-white/10 hover:text-white rounded-lg transition-colors group" wire:navigate>
            <svg class="w-5 h-5 mr-3 text-gray-400 group-hover:text-blue-400 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
            </svg>
            <span class="font-medium">Dashboard</span>
        </x-nav-link>

        <x-nav-link :href="route('super-admin.produits')" :active="request()->routeIs('super-admin.produits')" class="flex items-center px-4 py-3 text-gray-300 hover:bg-white/10 hover:text-white rounded-lg transition-colors group" wire:navigate>
            <svg class="w-5 h-5 mr-3 text-gray-400 group-hover:text-blue-400 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
               <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
            </svg>
            <span class="font-medium">Tous les Colis</span>
        </x-nav-link>

        <x-nav-link :href="route('super-admin.camions')" :active="request()->routeIs('super-admin.camions')" class="flex items-center px-4 py-3 text-gray-300 hover:bg-white/10 hover:text-white rounded-lg transition-colors group" wire:navigate>
             <svg class="w-5 h-5 mr-3 text-gray-400 group-hover:text-blue-400 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0" />
            </svg>
            <span class="font-medium">Flotte Globale</span>
        </x-nav-link>

         <x-nav-link :href="route('super-admin.livreurs')" :active="request()->routeIs('super-admin.livreurs')" class="flex items-center px-4 py-3 text-gray-300 hover:bg-white/10 hover:text-white rounded-lg transition-colors group" wire:navigate>
             <svg class="w-5 h-5 mr-3 text-gray-400 group-hover:text-blue-400 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
            </svg>
            <span class="font-medium">Livreurs</span>
        </x-nav-link>

    @elseif(auth()->user()->role === 'admin')
        <x-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard')" class="flex items-center px-4 py-3 text-gray-300 hover:bg-white/10 hover:text-white rounded-lg transition-colors group" wire:navigate>
            <svg class="w-5 h-5 mr-3 text-gray-400 group-hover:text-indigo-400 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
            </svg>
            <span class="font-medium">Tableau de Bord</span>
        </x-nav-link>

        <div class="text-xs uppercase text-gray-500 font-semibold mt-6 mb-2 px-2">Gestion</div>

        <x-nav-link :href="route('admin.produits.index')" :active="request()->routeIs('admin.produits.*')" class="flex items-center px-4 py-3 text-gray-300 hover:bg-white/10 hover:text-white rounded-lg transition-colors group" wire:navigate>
            <svg class="w-5 h-5 mr-3 text-gray-400 group-hover:text-indigo-400 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
            </svg>
            <span class="font-medium">Mes Colis</span>
        </x-nav-link>

        <x-nav-link :href="route('admin.camions.index')" :active="request()->routeIs('admin.camions.*')" class="flex items-center px-4 py-3 text-gray-300 hover:bg-white/10 hover:text-white rounded-lg transition-colors group" wire:navigate>
            <svg class="w-5 h-5 mr-3 text-gray-400 group-hover:text-indigo-400 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0" />
            </svg>
            <span class="font-medium">Ma Flotte</span>
        </x-nav-link>

        <x-nav-link :href="route('admin.livreurs.index')" :active="request()->routeIs('admin.livreurs.*')" class="flex items-center px-4 py-3 text-gray-300 hover:bg-white/10 hover:text-white rounded-lg transition-colors group" wire:navigate>
           <svg class="w-5 h-5 mr-3 text-gray-400 group-hover:text-indigo-400 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
               <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
           </svg>
           <span class="font-medium">Mes Livreurs</span>
       </x-nav-link>

    @elseif(auth()->user()->role === 'livreur')
         <x-nav-link :href="route('livreur.dashboard')" :active="request()->routeIs('livreur.dashboard')" class="flex items-center px-4 py-3 text-gray-300 hover:bg-white/10 hover:text-white rounded-lg transition-colors group" wire:navigate>
            <svg class="w-5 h-5 mr-3 text-gray-400 group-hover:text-green-400 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
            </svg>
            <span class="font-medium">Dashboard</span>
        </x-nav-link>
         <x-nav-link :href="route('livreur.scan')" :active="request()->routeIs('livreur.scan')" class="flex items-center px-4 py-3 text-gray-300 hover:bg-white/10 hover:text-white rounded-lg transition-colors group" wire:navigate>
            <svg class="w-5 h-5 mr-3 text-gray-400 group-hover:text-green-400 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z" />
            </svg>
            <span class="font-medium">Scanner</span>
        </x-nav-link>
    @endif
</nav>
