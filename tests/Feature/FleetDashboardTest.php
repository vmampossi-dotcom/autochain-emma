<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Vehicle;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FleetDashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_dashboard_can_store_a_maintenance_entry(): void
    {
        $user = User::factory()->create();
        $vehicle = Vehicle::factory()->create([
            'user_id' => $user->id,
            'vin' => '1HGCM82633A004352',
            'model' => 'Renault Kangoo',
        ]);

        $response = $this->actingAs($user)->post('/dashboard/maintenance', [
            'vehicle_id' => $vehicle->id,
            'title' => 'Révision moteur',
            'maintenance_type' => 'revision',
            'repairer_name' => 'Garage AutoChain',
            'mileage' => 45000,
            'performed_at' => '2026-07-20',
            'description' => 'Changement filtre et contrôle',
            'cost' => 320.50,
            'critical' => true,
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('maintenance_entries', [
            'vehicle_id' => $vehicle->id,
            'title' => 'Révision moteur',
        ]);
    }
}
