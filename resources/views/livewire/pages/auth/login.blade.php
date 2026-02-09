<?php

use App\Livewire\Forms\LoginForm;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    public LoginForm $form;

    /**
     * Handle an incoming authentication request.
     */
    public function login(): void
    {
        $this->validate();

        $this->form->authenticate();

        Session::regenerate();

        $user = auth()->user();
        
        $redirectUrl = match($user->role) {
            'super_admin' => route('super-admin.dashboard', absolute: false),
            'admin' => route('admin.dashboard', absolute: false),
            'livreur' => route('livreur.dashboard', absolute: false),
            default => route('dashboard', absolute: false),
        };

        $this->redirectIntended(default: $redirectUrl, navigate: true);
    }
}; ?>

<div>
    <div class="mb-6 text-center">
        <h2 class="text-2xl font-bold text-white">Connexion</h2>
        <p class="text-gray-400 text-sm mt-1">Accédez à votre espace sécurisé</p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form wire:submit="login" class="space-y-6">
        <!-- Email Address -->
        <div>
            <label for="email" class="block text-sm font-medium text-gray-300 mb-1">Email</label>
            <input wire:model="form.email" id="email" class="block w-full rounded-lg border-slate-600 bg-slate-900/50 text-white placeholder-gray-500 focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm py-2.5 px-3" type="email" name="email" required autofocus autocomplete="username" placeholder="exemple@delivery.com" />
            <x-input-error :messages="$errors->get('form.email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div>
            <label for="password" class="block text-sm font-medium text-gray-300 mb-1">Mot de passe</label>
            <input wire:model="form.password" id="password" class="block w-full rounded-lg border-slate-600 bg-slate-900/50 text-white placeholder-gray-500 focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm py-2.5 px-3" type="password" name="password" required autocomplete="current-password" placeholder="••••••••" />
            <x-input-error :messages="$errors->get('form.password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="flex items-center justify-between">
            <label for="remember" class="inline-flex items-center">
                <input wire:model="form.remember" id="remember" type="checkbox" class="rounded border-slate-600 bg-slate-900 text-indigo-500 shadow-sm focus:ring-indigo-500/50" name="remember">
                <span class="ms-2 text-sm text-gray-400">Se souvenir de moi</span>
            </label>
            
            @if (Route::has('password.request'))
                <a class="text-sm text-indigo-400 hover:text-indigo-300 transition-colors" href="{{ route('password.request') }}" wire:navigate>
                    Mot de passe oublié ?
                </a>
            @endif
        </div>

        <div>
            <button type="submit" class="w-full flex justify-center py-2.5 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-gradient-to-r from-indigo-600 to-violet-600 hover:from-indigo-500 hover:to-violet-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 focus:ring-offset-slate-900 transition-all duration-200 shadow-indigo-500/30 hover:shadow-indigo-500/50">
                Se connecter
            </button>
        </div>
    </form>
</div>
