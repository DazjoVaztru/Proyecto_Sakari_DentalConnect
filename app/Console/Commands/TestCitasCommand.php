<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Cita;
use App\Models\Paciente;
use App\Models\Servicio;

class TestCitasCommand extends Command
{
    protected $signature = 'test:citas';
    protected $description = 'Prueba las relaciones de citas';

    public function handle()
    {
        $citas = Cita::with(['paciente', 'servicio'])->take(5)->get();
        foreach ($citas as $cita) {
            $this->info("Cita ID: {$cita->id_cita}");
            $this->info("  Paciente: " . ($cita->paciente ? $cita->paciente->nombre : 'NULL'));
            $this->info("  Servicio: " . ($cita->servicio ? $cita->servicio->nombre_servicio : 'NULL'));
        }
    }
}
