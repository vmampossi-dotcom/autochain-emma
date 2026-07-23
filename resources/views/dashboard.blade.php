<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-2 md:flex-row md:items-center md:justify-between">
            <div>
                <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
                    {{ __('Tableau de bord AutoChain Emma+') }}
                </h2>
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                    {{ __('Suivi du parc, traçabilité blockchain, alertes et documents administratifs.') }}
                </p>
            </div>
            <div class="inline-flex items-center rounded-full border border-indigo-200 bg-indigo-50 px-3 py-1 text-sm font-medium text-indigo-700">
                🔐 Intégrité certifiée
            </div>
        </div>
    </x-slot>

    @php
        $totalVehicles = $vehicles->count();
        $available = max(0, (int) floor($totalVehicles / 2));
        $inMission = max(0, (int) floor($totalVehicles / 3));
        $inRepair = max(0, $totalVehicles - $available - $inMission);
    @endphp

    <div class="py-8">
        <div class="mx-auto max-w-7xl space-y-6 px-4 sm:px-6 lg:px-8">
                <div class="grid gap-4 md:grid-cols-4">
                <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                    <p class="text-sm font-medium text-slate-500">Véhicules total</p>
                    <p id="totalVehiclesCount" class="mt-2 text-3xl font-semibold text-slate-900">{{ $totalVehicles }}</p>
                </div>
                <div class="rounded-2xl border border-emerald-200 bg-emerald-50 p-5 shadow-sm">
                    <p class="text-sm font-medium text-emerald-700">Disponibles</p>
                    <p id="availableCount" class="mt-2 text-3xl font-semibold text-emerald-900">{{ $available }}</p>
                </div>
                <div class="rounded-2xl border border-amber-200 bg-amber-50 p-5 shadow-sm">
                    <p class="text-sm font-medium text-amber-700">En mission</p>
                    <p id="inMissionCount" class="mt-2 text-3xl font-semibold text-amber-900">{{ $inMission }}</p>
                </div>
                <div class="rounded-2xl border border-rose-200 bg-rose-50 p-5 shadow-sm">
                    <p class="text-sm font-medium text-rose-700">En panne</p>
                    <p id="inRepairCount" class="mt-2 text-3xl font-semibold text-rose-900">{{ $inRepair }}</p>
                </div>
            </div>

            <div class="grid gap-6 xl:grid-cols-[1.3fr_0.9fr]">
                <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-semibold text-slate-900">Parc automobile</h3>
                            <p class="mt-1 text-sm text-slate-500">Vue rapide des actifs enregistrés</p>
                        </div>
                        <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-medium text-slate-600">{{ $totalVehicles }} actifs</span>
                    </div>

                    <div class="mt-6 space-y-3">
                        @forelse ($vehicles as $vehicle)
                            <div class="flex items-center justify-between rounded-xl border border-slate-200 bg-slate-50 px-4 py-3">
                                <div>
                                    <p class="font-medium text-slate-900">{{ $vehicle->model }}</p>
                                    <p class="text-sm text-slate-500">VIN : {{ $vehicle->vin }}</p>
                                </div>
                                <div class="flex items-center gap-2">
                                    <span class="rounded-full bg-indigo-100 px-3 py-1 text-xs font-semibold text-indigo-700">Trace blockchain</span>
                                    <x-blockchain-actions :vehicle="$vehicle" />
                                </div>
                            </div>
                        @empty
                            <div class="rounded-xl border border-dashed border-slate-300 bg-slate-50 px-4 py-8 text-center text-sm text-slate-500">
                                Aucun véhicule n’a encore été enregistré. Ajoutez-en un pour commencer la traçabilité.
                            </div>
                        @endforelse
                    </div>
                </div>

                <div class="space-y-6">
                    <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                        <h3 class="text-lg font-semibold text-slate-900">Alertes & échéances</h3>
                        <ul class="mt-4 space-y-3 text-sm text-slate-600">
                            <li class="rounded-lg bg-amber-50 p-3 text-amber-800">Contrôle technique à venir dans 15 jours</li>
                            <li class="rounded-lg bg-blue-50 p-3 text-blue-800">Vidange prévue dans 3 jours</li>
                            <li class="rounded-lg bg-emerald-50 p-3 text-emerald-800">Assurance renouvelée avec succès</li>
                        </ul>
                    </div>

                    <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                        <h3 class="text-lg font-semibold text-slate-900">Traçabilité blockchain</h3>
                        <ul class="mt-4 space-y-3 text-sm text-slate-600">
                            <li>● Historique de maintenance immuable</li>
                            <li>● Relevés kilométriques certifiés</li>
                            <li>● Documents administratifs liés aux véhicules</li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="grid gap-6 xl:grid-cols-[1.1fr_0.9fr]">
                <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                    <h3 class="text-lg font-semibold text-slate-900">Chronologie du véhicule</h3>
                    <div class="mt-4 space-y-4">
                        @forelse ($vehicles as $vehicle)
                            @foreach ($vehicle->maintenanceEntries as $entry)
                                <div class="rounded-xl border border-slate-200 bg-slate-50 p-4">
                                    <p class="text-sm font-semibold text-slate-900">{{ $entry->performed_at->format('d/m/Y') }} — {{ $entry->title }}</p>
                                    <p class="mt-1 text-sm text-slate-600">{{ $entry->description ?? 'Entrée enregistrée dans le système.' }}</p>
                                </div>
                            @endforeach
                        @empty
                            <div class="text-sm text-slate-500">Aucune chronologie disponible pour le moment.</div>
                        @endforelse
                    </div>
                </div>

                <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                    <h3 class="text-lg font-semibold text-slate-900">Gestion documentaire</h3>
                    <div class="mt-4 space-y-3">
                        @forelse ($vehicles as $vehicle)
                            @foreach ($vehicle->documents as $document)
                                <div class="rounded-xl border border-slate-200 bg-slate-50 p-4 text-sm text-slate-600">
                                    {{ $document->document_type }} · {{ $document->title }}
                                </div>
                            @endforeach
                        @empty
                            <div class="text-sm text-slate-500">Aucun document enregistré.</div>
                        @endforelse
                    </div>
                </div>
            </div>

            <div class="grid gap-6 xl:grid-cols-2">
                <x-fleet-card title="Enregistrer une maintenance" description="Ajouter une intervention certifiée avec preuve de hash" badge="Maintenance">
                    <form method="POST" action="{{ route('dashboard.maintenance') }}" class="space-y-3">
                        @csrf
                        <select name="vehicle_id" class="w-full rounded-xl border-slate-300">
                            <option value="">Sélectionner un véhicule</option>
                            @foreach ($vehicles as $vehicle)
                                <option value="{{ $vehicle->id }}">{{ $vehicle->model }} - {{ $vehicle->vin }}</option>
                            @endforeach
                        </select>
                        <input type="text" name="title" placeholder="Titre" class="w-full rounded-xl border-slate-300" />
                        <input type="text" name="maintenance_type" placeholder="Type" class="w-full rounded-xl border-slate-300" />
                        <input type="text" name="repairer_name" placeholder="Réparateur" class="w-full rounded-xl border-slate-300" />
                        <input type="number" name="mileage" placeholder="Kilométrage" class="w-full rounded-xl border-slate-300" />
                        <input type="date" name="performed_at" class="w-full rounded-xl border-slate-300" />
                        <textarea name="description" placeholder="Description" class="w-full rounded-xl border-slate-300"></textarea>
                        <input type="number" step="0.01" name="cost" placeholder="Coût" class="w-full rounded-xl border-slate-300" />
                        <label class="flex items-center gap-2 text-sm text-slate-600"><input type="checkbox" name="critical" value="1" /> Critique</label>
                        <button class="rounded-xl bg-indigo-600 px-4 py-2 text-sm font-semibold text-white">Enregistrer</button>
                    </form>
                </x-fleet-card>

                <x-fleet-card title="Ajouter un plein" description="Suivre la consommation et le carburant" badge="Carburant">
                    <form method="POST" action="{{ route('dashboard.fuel') }}" class="space-y-3">
                        @csrf
                        <select name="vehicle_id" class="w-full rounded-xl border-slate-300">
                            <option value="">Sélectionner un véhicule</option>
                            @foreach ($vehicles as $vehicle)
                                <option value="{{ $vehicle->id }}">{{ $vehicle->model }} - {{ $vehicle->vin }}</option>
                            @endforeach
                        </select>
                        <input type="number" step="0.01" name="fuel_amount" placeholder="Quantité de carburant" class="w-full rounded-xl border-slate-300" />
                        <input type="number" step="0.01" name="cost" placeholder="Coût" class="w-full rounded-xl border-slate-300" />
                        <input type="number" name="mileage" placeholder="Kilométrage" class="w-full rounded-xl border-slate-300" />
                        <input type="date" name="performed_at" class="w-full rounded-xl border-slate-300" />
                        <input type="text" name="station_name" placeholder="Station" class="w-full rounded-xl border-slate-300" />
                        <button class="rounded-xl bg-indigo-600 px-4 py-2 text-sm font-semibold text-white">Enregistrer</button>
                    </form>
                </x-fleet-card>
            </div>

            <div class="grid gap-6 xl:grid-cols-2">
                <x-fleet-card title="Ajouter un document" description="Cartes grises, assurances, factures" badge="Documents">
                    <form method="POST" action="{{ route('dashboard.document') }}" enctype="multipart/form-data" class="space-y-3">
                        @csrf
                        <select name="vehicle_id" class="w-full rounded-xl border-slate-300">
                            <option value="">Sélectionner un véhicule</option>
                            @foreach ($vehicles as $vehicle)
                                <option value="{{ $vehicle->id }}">{{ $vehicle->model }} - {{ $vehicle->vin }}</option>
                            @endforeach
                        </select>
                        <input type="text" name="document_type" placeholder="Type de document" class="w-full rounded-xl border-slate-300" />
                        <input type="text" name="title" placeholder="Titre" class="w-full rounded-xl border-slate-300" />
                        <input type="file" name="file" class="w-full rounded-xl border-slate-300" />
                        <textarea name="notes" placeholder="Notes" class="w-full rounded-xl border-slate-300"></textarea>
                        <button class="rounded-xl bg-indigo-600 px-4 py-2 text-sm font-semibold text-white">Téléverser</button>
                    </form>
                </x-fleet-card>

                <x-fleet-card title="Créer une alerte" description="Planifier les contrôles et renouvellements" badge="Alertes">
                    <form method="POST" action="{{ route('dashboard.alert') }}" class="space-y-3">
                        @csrf
                        <select name="vehicle_id" class="w-full rounded-xl border-slate-300">
                            <option value="">Sélectionner un véhicule</option>
                            @foreach ($vehicles as $vehicle)
                                <option value="{{ $vehicle->id }}">{{ $vehicle->model }} - {{ $vehicle->vin }}</option>
                            @endforeach
                        </select>
                        <input type="text" name="title" placeholder="Titre" class="w-full rounded-xl border-slate-300" />
                        <input type="text" name="type" placeholder="Type" class="w-full rounded-xl border-slate-300" />
                        <textarea name="description" placeholder="Description" class="w-full rounded-xl border-slate-300"></textarea>
                        <input type="date" name="due_at" class="w-full rounded-xl border-slate-300" />
                        <button class="rounded-xl bg-indigo-600 px-4 py-2 text-sm font-semibold text-white">Créer</button>
                    </form>
                </x-fleet-card>
            </div>

            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <livewire:vehicle-form />
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('livewire:load', function () {
            if (window.Livewire) {
                window.Livewire.on('vehicleSaved', function (payload) {
                    try {
                        const totalEl = document.getElementById('totalVehiclesCount');
                        const availEl = document.getElementById('availableCount');
                        const missionEl = document.getElementById('inMissionCount');
                        const repairEl = document.getElementById('inRepairCount');

                        const parse = el => el ? parseInt(el.innerText || '0', 10) : 0;
                        let total = parse(totalEl);
                        let avail = parse(availEl);
                        let mission = parse(missionEl);
                        let repair = parse(repairEl);

                        if (!payload || !payload.action) {
                            // fallback to full reload
                            window.location.reload();
                            return;
                        }

                        const action = payload.action;

                        if (action === 'created') {
                            total = Math.max(0, total + 1);
                            if (payload.newStatus === 'available') avail = Math.max(0, avail + 1);
                            else if (payload.newStatus === 'on_mission') mission = Math.max(0, mission + 1);
                        } else if (action === 'deleted') {
                            total = Math.max(0, total - 1);
                            if (payload.oldStatus === 'available') avail = Math.max(0, avail - 1);
                            else if (payload.oldStatus === 'on_mission') mission = Math.max(0, mission - 1);
                        } else if (action === 'status_changed') {
                            const oldS = payload.oldStatus;
                            const newS = payload.newStatus;
                            if (oldS !== newS) {
                                if (oldS === 'available') avail = Math.max(0, avail - 1);
                                if (oldS === 'on_mission') mission = Math.max(0, mission - 1);

                                if (newS === 'available') avail = Math.max(0, avail + 1);
                                if (newS === 'on_mission') mission = Math.max(0, mission + 1);
                            }
                        } else if (action === 'edited') {
                            // If status changed in edit
                            const oldS = payload.oldStatus;
                            const newS = payload.newStatus;
                            if (oldS !== newS) {
                                if (oldS === 'available') avail = Math.max(0, avail - 1);
                                if (oldS === 'on_mission') mission = Math.max(0, mission - 1);

                                if (newS === 'available') avail = Math.max(0, avail + 1);
                                if (newS === 'on_mission') mission = Math.max(0, mission + 1);
                            }
                        }

                        if (totalEl) totalEl.innerText = total;
                        if (availEl) availEl.innerText = avail;
                        if (missionEl) missionEl.innerText = mission;
                        if (repairEl) repairEl.innerText = repair;
                    } catch (e) {
                        window.location.reload();
                    }
                });
            }
        });
    </script>
</x-app-layout>
