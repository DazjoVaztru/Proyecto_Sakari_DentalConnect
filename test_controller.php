<?php
// Script to simulate a call to index and output variables directly
// To bypass view errors
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

try {
    $proximasCitas = \App\Models\Cita::with(['paciente', 'servicio'])
        ->where('estado_cita', 'pendiente')
        ->orderBy('fecha_hora_inicio', 'asc')
        ->take(10)
        ->get();

    echo "Count: " . $proximasCitas->count() . "\n";
    foreach ($proximasCitas as $cita) {
        echo "Cita ID: " . $cita->id_cita . "\n";
        if ($cita->paciente) {
            echo "Paciente: " . $cita->paciente->nombre . "\n";
        } else {
            echo "Paciente: NULL\n";
        }
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
