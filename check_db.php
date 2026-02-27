<?php

$pacientes = \App\Models\Paciente::count();
$usuarios = \App\Models\User::where('rol', 'paciente')->count();
$citas = \App\Models\Cita::count();
$pagos = \App\Models\IngresoCaja::count();

echo "Pacientes: $pacientes\n";
echo "Usuarios (Rol paciente): $usuarios\n";
echo "Citas: $citas\n";
echo "Pagos: $pagos\n";
