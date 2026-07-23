<?php

require __DIR__ . '/../vendor/autoload.php';

use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Support\Str;

$dbPath = __DIR__ . '/../database/database.sqlite';

if (! file_exists($dbPath)) {
    echo "Database file missing: $dbPath\n";
    exit(1);
}

$capsule = new Capsule;
$capsule->addConnection([
    'driver' => 'sqlite',
    'database' => $dbPath,
    'prefix' => '',
]);

$capsule->setAsGlobal();
$capsule->bootEloquent();

$schema = $capsule->schema();

if (! $schema->hasTable('users')) {
    echo "Table 'users' not found. Run migrations first.\n";
    exit(1);
}

if (! $schema->hasTable('vehicles')) {
    echo "Table 'vehicles' not found. Run migrations first.\n";
    exit(1);
}

// Ensure there's a user to attach the vehicle to
$user = $capsule->table('users')->first();

if (! $user) {
    $email = 'test+' . time() . '@example.com';
    $password = password_hash('password', PASSWORD_BCRYPT);
    $id = $capsule->table('users')->insertGetId([
        'name' => 'Test User',
        'email' => $email,
        'password' => $password,
        'created_at' => date('Y-m-d H:i:s'),
        'updated_at' => date('Y-m-d H:i:s'),
    ]);
    echo "Created test user id=$id email=$email\n";
    $userId = $id;
} else {
    $userId = $user->id;
    echo "Using existing user id=$userId email={$user->email}\n";
}

// Create a unique VIN for test
$vin = strtoupper(substr(preg_replace('/[^A-Z0-9]/', '', Str::uuid()->toString()), 0, 17));
$model = 'TestModel ' . rand(100,999);
$owner = 'test-wallet-' . bin2hex(random_bytes(6));

$vehicleId = $capsule->table('vehicles')->insertGetId([
    'user_id' => $userId,
    'vin' => $vin,
    'model' => $model,
    'owner_address' => $owner,
    'created_at' => date('Y-m-d H:i:s'),
    'updated_at' => date('Y-m-d H:i:s'),
]);

echo "Inserted vehicle id=$vehicleId vin=$vin model=$model owner=$owner user_id=$userId\n";

exit(0);
