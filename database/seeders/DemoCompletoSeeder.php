<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DemoCompletoSeeder extends Seeder
{
    public function run()
    {
        // 1. Asegurar IDs (Asumimos que ya corriste el primer seeder y existen la clínica 1 y usuario 1)
        // Si no existen, fallará por llave foránea. Asegúrate de tener al menos una clínica y un usuario.
        $idClinica = 1;
        $idUsuario = 1;
        $idDoctor = 1;

        // 2. Crear Tratamiento de Ortodoncia
        // Usamos insertGetId para obtener el ID y usarlo en la cita
        $idServicio = DB::table('catalogo_servicios')->insertGetId([
            'id_clinica' => $idClinica,
            'nombre_servicio' => 'Ortodoncia (Brackets)',
            'precio_base' => 5000.00,
            'categoria' => 'Ortodoncia',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // 3. Crear al Paciente "María Gómez"
        // CORRECCIÓN: Quitamos 'clinica_id' porque no existe en tu tabla pacientes
        $idPaciente = DB::table('pacientes')->insertGetId([
            'id_usuario' => $idUsuario,
            'nombre' => 'María',
            'apellido_paterno' => 'Gómez',
            'apellido_materno' => 'López',
            'telefono' => '555-987-6543',
            'fecha_nacimiento' => '1998-05-20',
            'sexo' => 'F',
            'correo_electronico' => 'maria@demo.com',
            'tipo_sangre' => 'O+',
            'created_at' => now(),
        ]);

        // 4. Crear la Cita (Para mañana a las 10am)
        $idCita = DB::table('citas')->insertGetId([
            'id_clinica' => $idClinica,
            'id_paciente' => $idPaciente,
            'id_doctor' => $idDoctor, // Asegúrate de que exista el doctor con ID 1
            'id_servicio' => $idServicio,
            'fecha_hora_inicio' => Carbon::tomorrow()->setHour(10)->setMinute(0),
            'estado_cita' => 'pendiente',
            'created_at' => now(),
        ]);

        // 5. Registrar un ABONO inicial
        // La cita cuesta 5000, abonó 500. Restan 4500.
        DB::table('ingresos_caja')->insert([
            'id_clinica' => $idClinica,
            'id_cita' => $idCita,
            'monto' => 500.00,
            'metodo' => 'efectivo',
            'descripcion' => 'Anticipo Ortodoncia',
            'fecha_ingreso' => now(),
            'created_at' => now(),
        ]);

        $this->command->info('¡Datos de prueba corregidos insertados correctamente!');
    }
}