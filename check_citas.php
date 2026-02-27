<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$output = "";
$count = \App\Models\Cita::count();
$output .= "Total citas: $count\n";

$proximas = \App\Models\Cita::where('fecha_hora_inicio', '>=', now())
    ->where('estado_cita', 'pendiente')
    ->count();
$output .= "Proximas pendientes: $proximas\n";

$citas = \App\Models\Cita::orderBy('fecha_hora_inicio', 'desc')->take(10)->get();
foreach ($citas as $c) {
    $output .= $c->id_cita . " - " . $c->estado_cita . " - " . $c->fecha_hora_inicio . "\n";
}

file_put_contents(__DIR__ . '/citas_dump.txt', $output);
echo "Terminado\n";
