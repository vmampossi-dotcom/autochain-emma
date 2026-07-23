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

        $this->redirectIntended(default: route('dashboard', absolute: false), navigate: true);
    }
}; ?>

<div class="w-full max-w-md">
    <div class="mb-8 text-center lg:text-left">
        <div class="mx-auto mb-4 inline-flex h-12 w-12 items-center justify-center rounded-2xl bg-indigo-600 text-white shadow-lg lg:mx-0">
            <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M4 8L12 3L20 8V16L12 21L4 16V8Z" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/>
                <path d="M8 10H16" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                <path d="M8 14H16" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
            </svg>
        </div>
        <h1 class="text-2xl font-semibold text-slate-900">Connexion AutoChain Emma+</h1>
        <p class="mt-2 text-sm text-slate-600">
            Connectez-vous pour piloter votre parc automobile avec traçabilité, sécurité et suivi blockchain.
        </p>
    </div>

    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form wire:submit="login" class="space-y-5 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
        <div class="rounded-xl border border-indigo-100 bg-indigo-50/70 px-3 py-2 text-sm text-indigo-700">
            Sécurisation des accès · vérification des identifiants · suivi des opérations critiques
        </div>
        <div>
            <x-input-label for="email" :value="__('Adresse e-mail')" />
            <x-text-input wire:model="form.email" id="email" class="mt-1 block w-full rounded-xl border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" type="email" name="email" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('form.email')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="password" :value="__('Mot de passe')" />
            <x-text-input wire:model="form.password" id="password" class="mt-1 block w-full rounded-xl border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            type="password"
                            name="password"
                            required autocomplete="current-password" />
            <x-input-error :messages="$errors->get('form.password')" class="mt-2" />
        </div>

        <div class="flex items-center justify-between">
            <label for="remember" class="inline-flex items-center">
                <input wire:model="form.remember" id="remember" type="checkbox" class="rounded border-slate-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="remember">
                <span class="ms-2 text-sm text-slate-600">{{ __('Se souvenir de moi') }}</span>
            </label>

            @if (Route::has('password.request'))
                <a class="text-sm font-medium text-indigo-600 hover:text-indigo-500" href="{{ route('password.request') }}">
                    {{ __('Mot de passe oublié ?') }}
                </a>
            @endif
        </div>

        <x-primary-button class="w-full justify-center rounded-xl bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white shadow hover:bg-indigo-500">
            {{ __('Se connecter') }}
        </x-primary-button>
    </form>

    <div class="mt-6 text-center text-sm text-slate-600">
        <span>Pas encore de compte ?</span>
        <a href="{{ route('register') }}" class="ml-1 font-semibold text-indigo-600 hover:text-indigo-500">
            {{ __('Créer un compte') }}
        </a>
    </div>
</div>
