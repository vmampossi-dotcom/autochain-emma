<?php

require __DIR__ . '/../vendor/autoload.php';

use Illuminate\Database\Capsule\Manager as Capsule;

// Minimal script to check vehicles table schema and counts
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

if (! $schema->hasTable('vehicles')) {
    echo "Table 'vehicles' not found.\n";
    exit(1);
}

$columns = $schema->getColumnListing('vehicles');

echo "vehicles columns: " . implode(', ', $columns) . "\n";

$count = $capsule->table('vehicles')->count();

echo "vehicles rows: $count\n";

if (! in_array('user_id', $columns)) {
    echo "Column 'user_id' is missing.\n";
    exit(2);
}

$nullUser = $capsule->table('vehicles')->whereNull('user_id')->count();

echo "vehicles with null user_id: $nullUser\n";

echo "All checks passed.\n";
