<?php
require __DIR__ . '/vendor/autoload.php';
$app = require __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$finder = view()->getFinder();
$paths = $finder->getPaths();
var_dump($paths);
var_dump(view()->exists('livewire.vehicle-form'));
var_dump(view()->exists('welcome'));
if (view()->exists('livewire.vehicle-form')) {
    echo "found: " . $finder->find('livewire.vehicle-form') . PHP_EOL;
}
if (view()->exists('welcome')) {
    echo "welcome: " . $finder->find('welcome') . PHP_EOL;
}
