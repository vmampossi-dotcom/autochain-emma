<?php

use Illuminate\Support\Facades\Password;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    public string $email = '';

    /**
     * Send a password reset link to the provided email address.
     */
    public function sendPasswordResetLink(): void
    {
        $this->validate([
            'email' => ['required', 'string', 'email'],
        ]);

        // We will send the password reset link to this user. Once we have attempted
        // to send the link, we will examine the response then see the message we
        // need to show to the user. Finally, we'll send out a proper response.
        $status = Password::sendResetLink(
            $this->only('email')
        );

        if ($status != Password::RESET_LINK_SENT) {
            $messages = [
                Password::INVALID_USER => 'Nous ne trouvons pas d’utilisateur avec cette adresse e-mail.',
                Password::RESET_THROTTLED => 'Veuillez patienter avant de réessayer.',
            ];

            $this->addError('email', $messages[$status] ?? __($status));

            return;
        }

        $this->reset('email');

        session()->flash('status', __($status));
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
        <h1 class="text-2xl font-semibold text-slate-900">Mot de passe oublié</h1>
        <p class="mt-2 text-sm text-slate-600">Indiquez votre adresse e-mail pour recevoir un lien de réinitialisation.</p>
    </div>

    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form wire:submit="sendPasswordResetLink" class="space-y-5 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input wire:model="email" id="email" type="email" name="email" required autofocus />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <x-primary-button>
            {{ __('Envoyer le lien de réinitialisation') }}
        </x-primary-button>
    </form>
</div>
