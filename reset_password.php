<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$email = 'doctor_default@test.com';
$user = App\Models\User::where('email', $email)->first();

if ($user) {
    file_put_contents('reset_log.txt', "Found user with email: " . $email . "\n");
    $user->password_hash = \Illuminate\Support\Facades\Hash::make('password123');
    $user->save();
    file_put_contents('reset_log.txt', "Password updated successfully to: password123\n", FILE_APPEND);
} else {
    file_put_contents('reset_log.txt', "User not found with email: " . $email . "\nListing all users:\n");
    foreach (App\Models\User::all() as $u) {
        file_put_contents('reset_log.txt', " - " . $u->email . " (ID: " . $u->id_usuario . ")\n", FILE_APPEND);
    }
}
