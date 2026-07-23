<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @livewireStyles
    </head>
    <body class="font-sans text-gray-900 antialiased">
        <div class="min-h-screen bg-[radial-gradient(circle_at_top_left,_rgba(79,70,229,0.18),_transparent_30%),linear-gradient(135deg,_#f8fafc_0%,_#eef2ff_100%)] px-4 py-8 sm:px-6 lg:px-8">
            <div class="mx-auto flex min-h-[calc(100vh-4rem)] max-w-6xl items-center justify-center">
                <div class="w-full overflow-hidden rounded-[32px] border border-white/70 bg-white/90 shadow-[0_25px_80px_-20px_rgba(15,23,42,0.35)] backdrop-blur-xl">
                    <div class="grid lg:grid-cols-[1.05fr_0.95fr]">
                        <div class="relative hidden lg:flex lg:flex-col lg:justify-between lg:bg-gradient-to-br lg:from-slate-950 lg:via-indigo-950 lg:to-sky-800 lg:p-10">
                            <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_right,_rgba(56,189,248,0.24),_transparent_35%),radial-gradient(circle_at_bottom_left,_rgba(129,140,248,0.28),_transparent_35%)]"></div>
                            <div class="relative">
                                <div class="inline-flex h-12 w-12 items-center justify-center rounded-2xl bg-white/15 text-white backdrop-blur">
                                    <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M4 8L12 3L20 8V16L12 21L4 16V8Z" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/>
                                        <path d="M8 10H16" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                                        <path d="M8 14H16" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                                    </svg>
                                </div>
                                <h2 class="mt-6 text-3xl font-semibold text-white">AutoChain Emma+</h2>
                                <p class="mt-3 max-w-md text-sm leading-6 text-slate-200">
                                    Plateforme de gestion de parc automobile certifiée sur blockchain pour garantir l’intégrité du kilométrage, des maintenances et des documents.
                                </p>
                            </div>

                            <div class="relative space-y-3 rounded-2xl border border-white/20 bg-white/10 p-4 text-sm text-slate-100 backdrop-blur">
                                <div>
                                    <p class="font-medium">Traçabilité immuable</p>
                                    <p class="mt-1 text-slate-200">Historique des véhicules, interventions et pièces certifiés.</p>
                                </div>
                                <div>
                                    <p class="font-medium">Gestion sécurisée</p>
                                    <p class="mt-1 text-slate-200">Affectations, alertes et documents centralisés en un seul espace.</p>
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center justify-center bg-slate-50/80 p-8 sm:p-10 lg:p-12">
                            {{ $slot }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @livewireScripts
    </body>
</html>
