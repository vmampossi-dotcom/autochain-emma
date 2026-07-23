<?php

namespace App\Http\Controllers;

use App\Models\Vehicle;
use Illuminate\Http\Request;

class VehicleController extends Controller
{
    public function store(Request $request)
    {
        // 1. Validation des données envoyées par le front
        $validated = $request->validate([
            'vin' => 'required|unique:vehicles|max:17',
            'model' => 'required|string',
            'owner_address' => 'required|string',
        ]);

        // 2. Enregistrement dans MySQL
        $vehicle = Vehicle::create($validated);

        return response()->json([
            'message' => 'Vehicule enregistre avec succes dans la base!',
            'vehicle' => $vehicle
        ], 201);
    }
}