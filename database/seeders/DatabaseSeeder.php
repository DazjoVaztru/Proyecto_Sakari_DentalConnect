<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Eliminamos el User::factory() que venía por defecto
        // Y en su lugar llamamos a tu Seeder real

        $this->call([
            DatosInicialesSeeder::class,
        ]);
    }
}