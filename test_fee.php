<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

try {
    $service = app(\App\Services\MemberService::class);
    $reflection = new ReflectionMethod($service, 'calculateFees');
    $reflection->setAccessible(true);
    echo "FEE: " . $reflection->invoke($service, 30, 0);
} catch(Exception $e) {
    echo "CAUGHT EXCEPTION: " . $e->getMessage();
}
