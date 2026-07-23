<?php

use App\Http\Controllers\BlockchainController;
use App\Http\Controllers\FleetController;
use App\Http\Controllers\WalletAuthController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Vehicle;

Route::get('/', function () {
    return auth()->check()
        ? redirect()->route('dashboard')
        : redirect()->route('login');
});

Route::get('/wallet/login', function () {
    return view('auth.wallet-login');
})->name('wallet.login');

// (debug routes removed)

Route::get('/wallet/nonce', [WalletAuthController::class, 'getNonce']);
Route::post('/wallet/login', [WalletAuthController::class, 'login']);

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [FleetController::class, 'index'])->name('dashboard');
    Route::post('/dashboard/maintenance', [FleetController::class, 'storeMaintenance'])->name('dashboard.maintenance');
    Route::post('/dashboard/fuel', [FleetController::class, 'storeFuel'])->name('dashboard.fuel');
    Route::post('/dashboard/document', [FleetController::class, 'storeDocument'])->name('dashboard.document');
    Route::post('/dashboard/alert', [FleetController::class, 'storeAlert'])->name('dashboard.alert');
    Route::post('/blockchain/register-vehicle', [BlockchainController::class, 'registerVehicle'])->name('blockchain.registerVehicle');
    Route::post('/blockchain/register-maintenance', [BlockchainController::class, 'registerMaintenance'])->name('blockchain.registerMaintenance');
    Route::view('/metamask', 'metamask')->name('metamask');

    // Professional result page after blockchain registration
    Route::get('/blockchain/result', function (Request $request) {
        $vehicleId = $request->query('vehicle_id');
        $proofHash = $request->query('proof_hash');
        $vehicle = $vehicleId ? Vehicle::find($vehicleId) : null;

        return view('blockchain.result', [
            'vehicle' => $vehicle,
            'proof_hash' => $proofHash,
        ]);
    })->name('blockchain.result');

    // Temporary health route for debugging form target
    Route::post('/blockchain/ping', function () {
        return response()->json(['ok' => true]);
    })->name('blockchain.ping');
});

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

require __DIR__.'/auth.php';

// debug routes removed
