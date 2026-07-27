<?php

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    public string $name = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';

    /**
     * Handle an incoming registration request.
     */
    public function register(): void
    {
        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'string', 'confirmed', Rules\Password::defaults()],
        ]);

        $validated['password'] = Hash::make($validated['password']);

        event(new Registered($user = User::create($validated)));

        session()->flash('status', 'Compte créé avec succès. Veuillez vous connecter.');

        $this->redirect(route('login', absolute: false), navigate: true);
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
        <h1 class="text-2xl font-semibold text-slate-900">Créer un compte AutoChain Emma+</h1>
        <p class="mt-2 text-sm text-slate-600">Rejoignez la plateforme de gestion de parc automobile sécurisée.</p>
    </div>

    <form wire:submit="register" class="space-y-5 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
        <div class="rounded-xl border border-indigo-100 bg-indigo-50/70 px-3 py-2 text-sm text-indigo-700">
            Création de compte · sécurisation des accès · traçabilité blockchain
        </div>

        <div>
            <x-input-label for="name" :value="__('Nom')" />
            <x-text-input wire:model="name" id="name" type="text" name="name" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="email" :value="__('Adresse e-mail')" />
            <x-text-input wire:model="email" id="email" type="email" name="email" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="password" :value="__('Mot de passe')" />
            <x-text-input wire:model="password" id="password" type="password" name="password" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="password_confirmation" :value="__('Confirmation du mot de passe')" />
            <x-text-input wire:model="password_confirmation" id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <x-primary-button>
            {{ __('Créer un compte') }}
        </x-primary-button>

        <div class="text-center text-sm text-slate-600">
            <span>Déjà inscrit ?</span>
            <a href="{{ route('login') }}" class="ml-1 font-semibold text-indigo-600 hover:text-indigo-500">
                {{ __('Se connecter') }}
            </a>
        </div>
    </form>
</div>
