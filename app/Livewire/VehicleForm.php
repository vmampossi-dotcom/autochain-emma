<?php

namespace App\Livewire;

use App\Models\Vehicle;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Livewire\Component;

class VehicleForm extends Component
{
    public ?int $editingVehicleId = null;
    public string $vin = '';
    public string $model = '';
    public string $owner_address = '';
    public string $successMessage = '';
    public string $status = 'available';

    protected function rules(): array
    {
        return [
            'vin' => [
                'required',
                'string',
                'size:17',
                Rule::unique('vehicles', 'vin')->ignore($this->editingVehicleId),
            ],
            'model' => ['required', 'string', 'max:255'],
            'owner_address' => ['required', 'string', 'max:255'],
            'status' => ['sometimes', 'string', 'in:available,on_mission'],
        ];
    }

    public function save(): void
    {
        $validated = $this->validate();

        if ($this->editingVehicleId) {
            $vehicle = Vehicle::where('user_id', Auth::id())
                ->findOrFail($this->editingVehicleId);

            $oldStatus = $vehicle->status;
            $vehicle->update($validated);
            $newStatus = $vehicle->status;
            $this->successMessage = 'Véhicule modifié avec succès.';
            $this->dispatch('vehicleSaved', ['action' => 'edited', 'vehicleId' => $vehicle->id, 'oldStatus' => $oldStatus, 'newStatus' => $newStatus]);
        } else {
            $validated['user_id'] = Auth::id();
            $validated['status'] = $validated['status'] ?? 'available';
            $vehicle = Vehicle::create($validated);
            $this->successMessage = 'Véhicule enregistré avec succès.';
            $this->dispatch('vehicleSaved', ['action' => 'created', 'vehicleId' => $vehicle->id, 'newStatus' => $vehicle->status]);
        }

        $this->resetForm();
    }

    public function edit(int $vehicleId): void
    {
        $vehicle = Vehicle::where('user_id', Auth::id())
            ->findOrFail($vehicleId);

        $this->editingVehicleId = $vehicle->id;
        $this->vin = $vehicle->vin;
        $this->model = $vehicle->model;
        $this->owner_address = $vehicle->owner_address;
        $this->status = $vehicle->status ?? 'available';
        $this->successMessage = '';
        $this->resetValidation();
    }

    public function markOnMission(int $vehicleId): void
    {
        $vehicle = Vehicle::where('user_id', Auth::id())->findOrFail($vehicleId);
        $old = $vehicle->status;
        $vehicle->update(['status' => 'on_mission']);
        $this->dispatch('vehicleSaved', ['action' => 'status_changed', 'vehicleId' => $vehicleId, 'oldStatus' => $old, 'newStatus' => 'on_mission']);
    }

    public function markAvailable(int $vehicleId): void
    {
        $vehicle = Vehicle::where('user_id', Auth::id())->findOrFail($vehicleId);
        $old = $vehicle->status;
        $vehicle->update(['status' => 'available']);
        $this->dispatch('vehicleSaved', ['action' => 'status_changed', 'vehicleId' => $vehicleId, 'oldStatus' => $old, 'newStatus' => 'available']);
    }

    public function cancelEdit(): void
    {
        $this->resetForm();
    }

    public function delete(int $vehicleId): void
    {
        $vehicle = Vehicle::where('user_id', Auth::id())
            ->findOrFail($vehicleId);

        $oldStatus = $vehicle->status;
        $vehicle->delete();

        if ($this->editingVehicleId === $vehicleId) {
            $this->resetForm();
        }

        $this->successMessage = 'Véhicule supprimé avec succès.';
        $this->dispatch('vehicleSaved', ['action' => 'deleted', 'vehicleId' => $vehicleId, 'oldStatus' => $oldStatus]);
    }

    protected function resetForm(): void
    {
        $this->editingVehicleId = null;
        $this->vin = '';
        $this->model = '';
        $this->owner_address = '';
        $this->resetValidation();
    }

    public function render()
    {
        return view('livewire.vehicle-form', [
            'vehicles' => Vehicle::where('user_id', Auth::id())
                ->orderBy('created_at', 'desc')
                ->take(10)
                ->get(),
        ]);
    }
}