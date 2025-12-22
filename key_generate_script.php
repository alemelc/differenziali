<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);

$kernel->bootstrap();

$key = 'base64:' . base64_encode(random_bytes(32));

echo "Generated Key: " . $key . PHP_EOL;

// Force write even if .env doesn't exist (it should)
file_put_contents(__DIR__ . '/.env', 'APP_KEY=' . $key . PHP_EOL, FILE_APPEND);

echo "Key successfully appended to .env" . PHP_EOL;

// Print .env content to verify
echo "Current .env content:" . PHP_EOL;
echo file_get_contents(__DIR__ . '/.env') . PHP_EOL;
