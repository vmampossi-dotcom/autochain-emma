<div class="space-y-6">
    <div class="rounded-xl bg-white p-6 shadow-sm dark:bg-gray-800">
        <h1 class="text-xl font-semibold text-gray-900 dark:text-white">
            {{ $editingVehicleId ? 'Modifier le véhicule' : 'Enregistrer un véhicule' }}
        </h1>

        @if ($successMessage)
            <div class="mt-4 rounded-lg bg-green-50 p-4 text-green-800 dark:bg-green-900/20 dark:text-green-200">
                {{ $successMessage }}
            </div>
        @endif

        <form wire:submit.prevent="save" class="mt-6 space-y-4" id="vehicle-form">
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">VIN</label>
                <input type="text" wire:model="vin" maxlength="17" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-gray-700 dark:bg-gray-900 dark:text-white" />
                @error('vin') <span class="text-sm text-red-600">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">Modèle</label>
                <input type="text" wire:model="model" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-gray-700 dark:bg-gray-900 dark:text-white" />
                @error('model') <span class="text-sm text-red-600">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">Adresse du propriétaire (Wallet)</label>
                <input type="text" wire:model="owner_address" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-gray-700 dark:bg-gray-900 dark:text-white" />
                @error('owner_address') <span class="text-sm text-red-600">{{ $message }}</span> @enderror
            </div>

            <div class="flex items-center gap-3">
                <button type="submit" class="inline-flex items-center rounded-md bg-indigo-600 px-4 py-2 text-white hover:bg-indigo-700">
                    {{ $editingVehicleId ? 'Enregistrer les modifications' : 'Enregistrer' }}
                </button>

                @if ($editingVehicleId)
                    <button type="button" wire:click="cancelEdit" class="inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200">
                        Annuler
                    </button>
                @endif
            </div>
        </form>
    </div>

    <div class="rounded-xl bg-white p-6 shadow-sm dark:bg-gray-800">
        <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Derniers véhicules</h2>
        <div class="mt-4 space-y-4">
            @forelse($vehicles as $vehicle)
                <div wire:key="vehicle-{{ $vehicle->id }}" class="rounded-lg border border-gray-200 p-4 dark:border-gray-700">
                    <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                        <div>
                            <div class="font-medium text-gray-900 dark:text-white">{{ $vehicle->model }} — {{ $vehicle->vin }}</div>
                            <div class="text-sm mt-1">
                                @if($vehicle->status === 'on_mission')
                                    <span class="inline-flex items-center rounded-full bg-yellow-100 px-2 py-1 text-xs font-medium text-yellow-800">En mission</span>
                                @else
                                    <span class="inline-flex items-center rounded-full bg-green-100 px-2 py-1 text-xs font-medium text-green-800">Disponible</span>
                                @endif
                            </div>
                            <div class="text-sm text-gray-600 dark:text-gray-400">Wallet : {{ $vehicle->owner_address }}</div>
                            <div class="text-xs text-gray-500 dark:text-gray-400">Ajouté le {{ $vehicle->created_at->format('d/m/Y H:i') }}</div>
                        </div>
                        <div class="flex gap-2 mt-3 sm:mt-0">
                            <button type="button" wire:click.prevent="edit({{ $vehicle->id }})" wire:key="edit-{{ $vehicle->id }}" class="inline-flex items-center rounded-md border border-indigo-600 bg-indigo-50 px-3 py-2 text-sm text-indigo-700 hover:bg-indigo-100 dark:border-indigo-400 dark:bg-indigo-900/20 dark:text-indigo-200">
                                Modifier
                            </button>
                                @if($vehicle->status === 'available')
                                    <button type="button" wire:click.prevent="markOnMission({{ $vehicle->id }})" wire:key="mission-{{ $vehicle->id }}" class="inline-flex items-center rounded-md border border-yellow-600 bg-yellow-50 px-3 py-2 text-sm text-yellow-700 hover:bg-yellow-100 dark:border-yellow-400 dark:bg-yellow-900/20 dark:text-yellow-200">
                                        Marquer en mission
                                    </button>
                                @else
                                    <button type="button" wire:click.prevent="markAvailable({{ $vehicle->id }})" wire:key="available-{{ $vehicle->id }}" class="inline-flex items-center rounded-md border border-green-600 bg-green-50 px-3 py-2 text-sm text-green-700 hover:bg-green-100 dark:border-green-400 dark:bg-green-900/20 dark:text-green-200">
                                        Marquer disponible
                                    </button>
                                @endif
                            <button type="button" wire:click.prevent="delete({{ $vehicle->id }})" wire:key="delete-{{ $vehicle->id }}" class="inline-flex items-center rounded-md border border-red-600 bg-red-50 px-3 py-2 text-sm text-red-700 hover:bg-red-100 dark:border-red-400 dark:bg-red-900/20 dark:text-red-200">
                                Supprimer
                            </button>
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-sm text-gray-600 dark:text-gray-400">Aucun véhicule enregistré pour le moment.</div>
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