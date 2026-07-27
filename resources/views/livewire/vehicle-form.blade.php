<div class="space-y-6">
    <div class="rounded-2xl border border-slate-200 bg-white/90 p-6 shadow-sm">
        <h1 class="text-xl font-semibold text-slate-900">
            {{ $editingVehicleId ? 'Modifier le véhicule' : 'Enregistrer un véhicule' }}
        </h1>

        @if ($successMessage)
            <div class="mt-4 rounded-lg bg-green-50 p-4 text-green-800">
                {{ $successMessage }}
            </div>
        @endif

        <form wire:submit.prevent="save" class="mt-6 space-y-4" id="vehicle-form">
            <div>
                <label class="block text-sm font-medium text-slate-700">VIN</label>
                <input type="text" wire:model="vin" maxlength="17" class="mt-1 block w-full rounded-xl border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" />
                @error('vin') <span class="text-sm text-red-600">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700">Modèle</label>
                <input type="text" wire:model="model" class="mt-1 block w-full rounded-xl border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" />
                @error('model') <span class="text-sm text-red-600">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700">Adresse du propriétaire (Wallet)</label>
                <input type="text" wire:model="owner_address" class="mt-1 block w-full rounded-xl border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" />
                @error('owner_address') <span class="text-sm text-red-600">{{ $message }}</span> @enderror
            </div>

            <div class="flex items-center gap-3">
                <button type="submit" class="inline-flex items-center rounded-xl bg-indigo-600 px-4 py-2 text-white transition hover:bg-indigo-700">
                    {{ $editingVehicleId ? 'Enregistrer les modifications' : 'Enregistrer' }}
                </button>

                @if ($editingVehicleId)
                    <button type="button" wire:click="cancelEdit" class="inline-flex items-center rounded-xl border border-slate-300 bg-white px-4 py-2 text-slate-700 transition hover:bg-slate-50">
                        Annuler
                    </button>
                @endif
            </div>
        </form>
    </div>

    <div class="rounded-2xl border border-slate-200 bg-white/90 p-6 shadow-sm">
        <h2 class="text-lg font-semibold text-slate-900">Derniers véhicules</h2>
        <div class="mt-4 space-y-4">
            @forelse($vehicles as $vehicle)
                <div wire:key="vehicle-{{ $vehicle->id }}" class="rounded-xl border border-slate-200 bg-slate-50 p-4">
                    <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                        <div>
                            <div class="font-medium text-slate-900">{{ $vehicle->model }} — {{ $vehicle->vin }}</div>
                            <div class="mt-1 text-sm">
                                @if($vehicle->status === 'on_mission')
                                    <span class="inline-flex items-center rounded-full bg-yellow-100 px-2 py-1 text-xs font-medium text-yellow-800">En mission</span>
                                @else
                                    <span class="inline-flex items-center rounded-full bg-green-100 px-2 py-1 text-xs font-medium text-green-800">Disponible</span>
                                @endif
                            </div>
                            <div class="mt-2 text-sm text-slate-600">Wallet : {{ $vehicle->owner_address }}</div>
                            <div class="text-xs text-slate-500">Ajouté le {{ $vehicle->created_at->format('d/m/Y H:i') }}</div>
                        </div>
                        <div class="mt-3 flex flex-wrap gap-2 sm:mt-0">
                            <button type="button" wire:click.prevent="edit({{ $vehicle->id }})" wire:key="edit-{{ $vehicle->id }}" class="inline-flex items-center rounded-xl border border-indigo-600 bg-indigo-50 px-3 py-2 text-sm text-indigo-700 transition hover:bg-indigo-100">
                                Modifier
                            </button>
                            @if($vehicle->status === 'available')
                                <button type="button" wire:click.prevent="markOnMission({{ $vehicle->id }})" wire:key="mission-{{ $vehicle->id }}" class="inline-flex items-center rounded-xl border border-yellow-600 bg-yellow-50 px-3 py-2 text-sm text-yellow-700 transition hover:bg-yellow-100">
                                    Marquer en mission
                                </button>
                            @else
                                <button type="button" wire:click.prevent="markAvailable({{ $vehicle->id }})" wire:key="available-{{ $vehicle->id }}" class="inline-flex items-center rounded-xl border border-green-600 bg-green-50 px-3 py-2 text-sm text-green-700 transition hover:bg-green-100">
                                    Marquer disponible
                                </button>
                            @endif
                            <button type="button" wire:click.prevent="delete({{ $vehicle->id }})" wire:key="delete-{{ $vehicle->id }}" class="inline-flex items-center rounded-xl border border-red-600 bg-red-50 px-3 py-2 text-sm text-red-700 transition hover:bg-red-100">
                                Supprimer
                            </button>
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-sm text-slate-600">Aucun véhicule enregistré pour le moment.</div>
            @endforelse
        </div>
    </div>
</div>

<script>
    document.addEventListener('livewire:load', function () {
        const form = document.getElementById('vehicle-form');
        if (!form) return;

        form.addEventListener('submit', function () {
            console.log('vehicle-form submitted (client)');
        });
    });
</script>