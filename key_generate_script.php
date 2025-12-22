<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);

$kernel->bootstrap();

$key = 'base64:' . base64_encode(random_bytes(32));

// echo "Generated Key: " . $key . PHP_EOL; // Silent output
echo $key; // Just echo the key strictly
exit(0);

echo "Key successfully appended to .env" . PHP_EOL;

// Print .env content to verify
echo "Current .env content:" . PHP_EOL;
echo file_get_contents(__DIR__ . '/.env') . PHP_EOL;
