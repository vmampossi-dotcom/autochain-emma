<?php

namespace App\Http\Controllers;

use App\Models\MaintenanceEntry;
use App\Models\Vehicle;
use App\Services\BlockchainProofService;
use Illuminate\Http\Request;

class BlockchainController extends Controller
{
    public function registerVehicle(Request $request, BlockchainProofService $service)
    {
        $vehicle = Vehicle::findOrFail($request->input('vehicle_id'));

        $proof = $service->createProof('vehicle', [
            'vin' => $vehicle->vin,
            'model' => $vehicle->model,
            'owner_address' => $vehicle->owner_address,
        ]);

        $vehicle->update(['owner_address' => $vehicle->owner_address]);

        return response()->json([
            'message' => 'Preuve de véhicule enregistrée localement.',
            'proof_hash' => $proof,
        ]);
    }

    public function registerMaintenance(Request $request, BlockchainProofService $service)
    {
        $entry = MaintenanceEntry::findOrFail($request->input('maintenance_id'));

        $proof = $service->createProof('maintenance', [
            'title' => $entry->title,
            'repairer' => $entry->repairer_name,
            'mileage' => $entry->mileage,
            'performed_at' => $entry->performed_at->toDateString(),
        ]);

        $entry->update(['proof_hash' => $proof]);

        return response()->json([
            'message' => 'Preuve de maintenance enregistrée.',
            'proof_hash' => $proof,
        ]);
    }
}
