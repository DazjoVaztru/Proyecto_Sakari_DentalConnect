<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$user = \App\Models\User::first();
if ($user) {
    echo "Usuario auth: " . $user->nombre_completo . "\n";
    \Illuminate\Support\Facades\Auth::login($user);
} else {
    echo "No hay usuarios.\n";
    exit;
}

$request = \Illuminate\Http\Request::create('/dashboard', 'GET');
$response = $app->handle($request);
echo "Status: " . $response->getStatusCode() . "\n";
echo "Response Length: " . strlen($response->getContent()) . "\n";
