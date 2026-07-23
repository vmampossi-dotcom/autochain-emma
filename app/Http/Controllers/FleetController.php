<?php

namespace App\Http\Controllers;

use App\Models\Alert;
use App\Models\Document;
use App\Models\FuelEntry;
use App\Models\MaintenanceEntry;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class FleetController extends Controller
{
    public function storeMaintenance(Request $request)
    {
        $validated = $request->validate([
            'vehicle_id' => 'required|exists:vehicles,id',
            'title' => 'required|string|max:255',
            'maintenance_type' => 'required|string|max:255',
            'repairer_name' => 'required|string|max:255',
            'mileage' => 'required|integer',
            'performed_at' => 'required|date',
            'description' => 'nullable|string',
            'cost' => 'nullable|numeric',
            'critical' => 'nullable|boolean',
        ]);

        $validated['user_id'] = auth()->id();
        $validated['proof_hash'] = Hash::make($validated['title'] . '|' . $validated['performed_at']);

        MaintenanceEntry::create($validated);

        return redirect()->route('dashboard')->with('status', 'Maintenance enregistrée.');
    }

    public function storeFuel(Request $request)
    {
        $validated = $request->validate([
            'vehicle_id' => 'required|exists:vehicles,id',
            'fuel_amount' => 'required|numeric',
            'cost' => 'required|numeric',
            'mileage' => 'required|integer',
            'performed_at' => 'required|date',
            'station_name' => 'nullable|string|max:255',
        ]);

        $validated['user_id'] = auth()->id();
        $validated['proof_hash'] = Hash::make($validated['vehicle_id'] . '|' . $validated['performed_at']);

        FuelEntry::create($validated);

        return redirect()->route('dashboard')->with('status', 'Plein enregistré.');
    }

    public function storeDocument(Request $request)
    {
        $validated = $request->validate([
            'vehicle_id' => 'required|exists:vehicles,id',
            'document_type' => 'required|string|max:255',
            'title' => 'required|string|max:255',
            'file' => 'required|file|mimes:pdf,jpg,jpeg,png',
            'notes' => 'nullable|string',
        ]);

        $path = $request->file('file')->store('documents', 'public');

        Document::create([
            'vehicle_id' => $validated['vehicle_id'],
            'user_id' => auth()->id(),
            'document_type' => $validated['document_type'],
            'title' => $validated['title'],
            'file_path' => $path,
            'original_name' => $request->file('file')->getClientOriginalName(),
            'hash' => Hash::make($path),
            'notes' => $validated['notes'] ?? null,
        ]);

        return redirect()->route('dashboard')->with('status', 'Document enregistré.');
    }

    public function storeAlert(Request $request)
    {
        $validated = $request->validate([
            'vehicle_id' => 'required|exists:vehicles,id',
            'title' => 'required|string|max:255',
            'type' => 'required|string|max:255',
            'description' => 'nullable|string',
            'due_at' => 'required|date',
        ]);

        $validated['user_id'] = auth()->id();
        $validated['status'] = 'pending';

        Alert::create($validated);

        return redirect()->route('dashboard')->with('status', 'Alerte créée.');
    }

    public function index()
    {
        $vehicles = Vehicle::where('user_id', auth()->id())->with(['maintenanceEntries', 'fuelEntries', 'documents', 'alerts'])->latest()->get();

        return view('dashboard', compact('vehicles'));
    }
}
