<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        // 1. CLÍNICAS
        Schema::create('clinicas', function (Blueprint $table) {
            $table->id('id_clinica');
            $table->string('nombre_comercial', 150);
            $table->decimal('config_anticipo_pct', 5, 2)->default(0.00);
            $table->string('numero_telefono', 15)->nullable();
            $table->string('localidad', 100)->nullable();
            $table->string('estado', 50)->nullable();
            $table->string('codigo_postal', 10)->nullable();
            $table->timestamps();
        });

        // 2. USUARIOS DEL SISTEMA
        Schema::create('usuarios_sistema', function (Blueprint $table) {
            $table->id('id_usuario');
            $table->unsignedBigInteger('id_clinica')->nullable();
            $table->string('nombre_completo', 255);
            $table->enum('rol', ['administrador', 'doctor', 'recepcionista', 'paciente'])->nullable();
            $table->boolean('is_active')->default(true);
            $table->string('email', 255)->unique();
            $table->string('password', 255);
            $table->string('reset_password_token', 255)->nullable();
            $table->dateTime('reset_password_expires')->nullable();
            $table->timestamps();

            $table->foreign('id_clinica')->references('id_clinica')->on('clinicas')->onDelete('cascade');
        });

        // 3. DOCTORES
        Schema::create('doctores', function (Blueprint $table) {
            $table->id('id_doctor');
            $table->unsignedBigInteger('id_usuario');
            $table->string('cedula_profesional', 50)->nullable();
            $table->text('horario_default')->nullable();
            $table->timestamps();
            $table->foreign('id_usuario')->references('id_usuario')->on('usuarios_sistema');
        });

        // 4. CONTACTO EMERGENCIA
        Schema::create('contacto_emergencia', function (Blueprint $table) {
            $table->id('id_contacto_emergencia');
            $table->string('nombre', 50);
            $table->string('apellido_materno', 50)->nullable();
            $table->string('apellido_paterno', 50);
            $table->string('numero_telefono', 20);
            $table->timestamps();
        });

        // 5. PACIENTES (Incluye foto_perfil y ocupacion nullable)
        Schema::create('pacientes', function (Blueprint $table) {
            $table->id('id_paciente');
            $table->unsignedBigInteger('id_usuario');
            $table->string('nombre', 50)->nullable();
            $table->string('apellido_paterno', 50)->nullable();
            $table->string('apellido_materno', 50)->nullable();
            $table->string('telefono', 20)->nullable();
            $table->string('alergias_criticas', 200)->nullable();
            $table->date('fecha_nacimiento')->nullable();
            $table->enum('sexo', ['M', 'F', 'O'])->nullable();
            $table->string('correo_electronico', 100)->nullable();
            $table->string('tipo_sangre', 5)->nullable();
            $table->decimal('peso', 5, 2)->nullable();
            $table->string('ocupacion', 100)->nullable();
            $table->text('enfermedades_cronicas')->nullable();
            $table->text('alergias')->nullable();
            $table->unsignedBigInteger('id_contacto_emergencia')->nullable();
            $table->boolean('is_active')->default(true);
            $table->string('foto_perfil', 255)->nullable(); // Nuevo campo
            $table->timestamps();

            $table->foreign('id_usuario')->references('id_usuario')->on('usuarios_sistema');
            $table->foreign('id_contacto_emergencia')->references('id_contacto_emergencia')->on('contacto_emergencia');
        });

        // 6. CATÁLOGOS BÁSICOS
        Schema::create('catalogo_alergias', function (Blueprint $table) {
            $table->id('id_alergia');
            $table->string('nombre_alergeno', 50)->unique();
            $table->timestamps();
        });

        Schema::create('catalogo_enfermedades_cronicas', function (Blueprint $table) {
            $table->id('id_enfermedad_cronica');
            $table->string('nombre_enfermedad', 100)->unique();
            $table->timestamps();
        });

        Schema::create('catalogo_tipo_sangre', function (Blueprint $table) {
            $table->id('id_tipo_sangre');
            $table->enum('tipo', ['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'])->unique();
            $table->timestamps();
        });

        Schema::create('catalogo_servicios', function (Blueprint $table) {
            $table->id('id_servicio');
            $table->unsignedBigInteger('id_clinica');
            $table->string('nombre_servicio', 100);
            $table->decimal('precio_base', 10, 2)->nullable();
            $table->string('categoria', 50)->nullable();
            $table->timestamps();
            $table->foreign('id_clinica')->references('id_clinica')->on('clinicas');
        });

        // 7. CITAS
        Schema::create('citas', function (Blueprint $table) {
            $table->id('id_cita');
            $table->unsignedBigInteger('id_clinica')->nullable();
            $table->unsignedBigInteger('id_paciente')->nullable();
            $table->unsignedBigInteger('id_doctor')->nullable();
            $table->unsignedBigInteger('id_servicio')->nullable();
            $table->dateTime('fecha_hora_inicio')->nullable();
            $table->dateTime('fecha_hora_fin')->nullable();
            $table->enum('estado_cita', ['pendiente', 'confirmada', 'cancelada', 'completada'])->nullable();
            $table->string('motivo', 255)->nullable();
            $table->decimal('costo_estimado', 10, 2)->default(0.00);
            $table->timestamps();

            $table->foreign('id_clinica')->references('id_clinica')->on('clinicas');
            $table->foreign('id_paciente')->references('id_paciente')->on('pacientes');
            $table->foreign('id_doctor')->references('id_doctor')->on('doctores');
            $table->foreign('id_servicio')->references('id_servicio')->on('catalogo_servicios');
        });

        // 8. RELACIONES Y DETALLES DEL PACIENTE
        Schema::create('pacientes_alergias', function (Blueprint $table) {
            $table->id('id_registro');
            $table->unsignedBigInteger('id_paciente')->nullable();
            $table->unsignedBigInteger('id_alergia')->nullable();
            $table->unique(['id_paciente', 'id_alergia'], 'uk_pa');
            $table->foreign('id_paciente')->references('id_paciente')->on('pacientes');
            $table->foreign('id_alergia')->references('id_alergia')->on('catalogo_alergias');
        });

        Schema::create('pacientes_enfermedades_cronicas', function (Blueprint $table) {
            $table->id('id_registro');
            $table->unsignedBigInteger('id_paciente')->nullable();
            $table->unsignedBigInteger('id_enfermedad_cronica')->nullable();
            $table->unique(['id_paciente', 'id_enfermedad_cronica'], 'uk_pec');
            $table->foreign('id_paciente')->references('id_paciente')->on('pacientes');
            $table->foreign('id_enfermedad_cronica')->references('id_enfermedad_cronica')->on('catalogo_enfermedades_cronicas');
        });

        Schema::create('paciente_peso', function (Blueprint $table) {
            $table->id('id_paciente_peso');
            $table->unsignedBigInteger('id_paciente')->nullable();
            $table->decimal('peso_kg', 4, 2)->nullable();
            $table->dateTime('fecha_registro')->useCurrent();
            $table->foreign('id_paciente')->references('id_paciente')->on('pacientes');
        });

        Schema::create('paciente_tipo_sangre', function (Blueprint $table) {
            $table->id('id_paciente_tipo_sangre');
            $table->unsignedBigInteger('id_paciente')->nullable();
            $table->unsignedBigInteger('id_tipo_sangre')->nullable();
            $table->dateTime('fecha_registro')->useCurrent();
            $table->foreign('id_paciente')->references('id_paciente')->on('pacientes');
            $table->foreign('id_tipo_sangre')->references('id_tipo_sangre')->on('catalogo_tipo_sangre');
        });

        // 9. TABLAS OPERATIVAS CLÍNICAS (Odontograma, Evolución, Archivos, etc)
        Schema::create('odontograma', function (Blueprint $table) {
            $table->id('id_odontograma');
            $table->unsignedBigInteger('id_paciente')->nullable();
            $table->unsignedBigInteger('id_cita')->nullable();
            $table->string('numero_diente', 5)->nullable();
            $table->string('cara_diente', 50)->nullable();
            $table->string('estado_diente', 50)->nullable();
            $table->text('observaciones')->nullable();
            $table->dateTime('fecha_registro')->useCurrent();
            $table->foreign('id_paciente')->references('id_paciente')->on('pacientes');
            $table->foreign('id_cita')->references('id_cita')->on('citas');
        });

        Schema::create('archivos', function (Blueprint $table) {
            $table->id('id_archivo');
            $table->unsignedBigInteger('id_paciente')->nullable();
            $table->unsignedBigInteger('id_cita')->nullable();
            $table->string('url_archivo', 255)->nullable();
            $table->enum('tipo', ['imagen', 'pdf', 'rayos_x'])->nullable();
            $table->string('descripcion', 200)->nullable();
            $table->foreign('id_paciente')->references('id_paciente')->on('pacientes');
            $table->foreign('id_cita')->references('id_cita')->on('citas');
        });

        Schema::create('evolucion_tratamiento', function (Blueprint $table) {
            $table->id('id_evolucion');
            $table->unsignedBigInteger('id_servicio')->nullable();
            $table->unsignedBigInteger('id_paciente')->nullable();
            $table->dateTime('fecha_evolucion')->nullable();
            $table->string('descripcion_avance', 100)->nullable();
            $table->text('subjetivo_soap')->nullable();
            $table->text('objetivo_soap')->nullable();
            $table->text('plan_tratamiento')->nullable();
            $table->string('estado_paciente', 50)->nullable();
            $table->foreign('id_servicio')->references('id_servicio')->on('catalogo_servicios');
            $table->foreign('id_paciente')->references('id_paciente')->on('pacientes');
        });

        Schema::create('seguimiento_clinico', function (Blueprint $table) {
            $table->id('id_seguimiento');
            $table->unsignedBigInteger('id_cita')->nullable();
            $table->enum('postratamiento', ['si', 'no'])->nullable();
            $table->unsignedBigInteger('id_servicio')->nullable();
            $table->text('observaciones')->nullable();
            $table->foreign('id_cita')->references('id_cita')->on('citas');
            $table->foreign('id_servicio')->references('id_servicio')->on('catalogo_servicios');
        });

        Schema::create('ingresos_caja', function (Blueprint $table) {
            $table->id('id_ingreso');
            $table->unsignedBigInteger('id_cita')->nullable();
            $table->unsignedBigInteger('id_clinica')->nullable();
            $table->decimal('monto', 10, 2)->nullable();
            $table->enum('metodo', ['efectivo', 'tarjeta', 'transferencia', 'otro'])->nullable();
            $table->string('descripcion', 200)->nullable();
            $table->dateTime('fecha_ingreso')->useCurrent();
            $table->timestamps();
            $table->foreign('id_cita')->references('id_cita')->on('citas');
            $table->foreign('id_clinica')->references('id_clinica')->on('clinicas');
        });

        Schema::create('inventario', function (Blueprint $table) {
            $table->id('id_item');
            $table->unsignedBigInteger('id_clinica');
            $table->string('nombre_item', 100);
            $table->integer('stock')->default(0);
            $table->decimal('precio', 10, 2)->nullable();
            $table->timestamps();
            $table->foreign('id_clinica')->references('id_clinica')->on('clinicas');
        });

        Schema::create('horarios_bloqueados', function (Blueprint $table) {
            $table->id('id_bloqueo');
            $table->unsignedBigInteger('id_doctor')->nullable();
            $table->dateTime('fecha_inicio')->nullable();
            $table->dateTime('fecha_fin')->nullable();
            $table->enum('motivo', ['vacaciones', 'enfermedad', 'otro'])->nullable();
            $table->boolean('estatus_horario')->nullable();
            $table->foreign('id_doctor')->references('id_doctor')->on('doctores');
        });

        Schema::create('reviews', function (Blueprint $table) {
            $table->id('id_review');
            $table->unsignedBigInteger('id_cita')->nullable();
            $table->unsignedBigInteger('id_paciente')->nullable();
            $table->integer('calificacion')->nullable();
            $table->text('comentario')->nullable();
            $table->foreign('id_cita')->references('id_cita')->on('citas');
            $table->foreign('id_paciente')->references('id_paciente')->on('pacientes');
        });

        // 10. CONFIGURACIÓN Y NOTIFICACIONES
        Schema::create('config_global', function (Blueprint $table) {
            $table->id('id_config');
            $table->string('clave', 50)->unique();
            $table->text('valor');
            $table->string('descripcion', 200)->nullable();
            $table->timestamps();
        });

        Schema::create('config_recordatorios', function (Blueprint $table) {
            $table->id('id_regla');
            $table->unsignedBigInteger('id_clinica');
            $table->integer('tiempo_anticipacion');
            $table->enum('unidad_tiempo', ['dias', 'horas', 'minutos']);
            $table->text('plantilla_mensaje')->nullable();
            $table->timestamps();
            $table->foreign('id_clinica')->references('id_clinica')->on('clinicas')->onDelete('cascade');
        });

        Schema::create('notificaciones', function (Blueprint $table) {
            $table->id('id_notificacion');
            $table->unsignedBigInteger('id_usuario')->nullable();
            $table->enum('tipo', ['recordatorio', 'confirmacion', 'cancelacion', 'push'])->nullable();
            $table->text('mensaje')->nullable();
            $table->string('device_token', 255)->nullable();
            $table->enum('estado', ['pendiente', 'enviado', 'leido'])->nullable();
            $table->foreign('id_usuario')->references('id_usuario')->on('usuarios_sistema');
        });

        Schema::create('bitacora_notificaciones', function (Blueprint $table) {
            $table->id('id_notificacion');
            $table->unsignedBigInteger('id_cita')->nullable();
            $table->enum('estado_envio', ['enviado', 'fallido', 'pendiente'])->nullable();
            $table->dateTime('fecha_envio')->useCurrent();
            $table->foreign('id_cita')->references('id_cita')->on('citas');
        });

        Schema::create('publicidad', function (Blueprint $table) {
            $table->id('id_publicidad');
            $table->unsignedBigInteger('id_usuario');
            $table->string('titulo', 100);
            $table->text('descripcion')->nullable();
            $table->string('imagen_path', 255)->nullable();
            $table->boolean('activo')->default(true);
            $table->timestamps();
            $table->foreign('id_usuario')->references('id_usuario')->on('usuarios_sistema')->onDelete('cascade');
        });

        Schema::create('permisos', function (Blueprint $table) {
            $table->id('id_permiso');
            $table->enum('rol', ['admin', 'doctor', 'recepcionista', 'paciente']);
            $table->enum('accion', ['read', 'write', 'delete']);
            $table->string('recurso', 50);
            $table->timestamps();
            $table->unique(['rol', 'accion', 'recurso'], 'uk_permiso');
        });

        Schema::create('tokens', function (Blueprint $table) {
            $table->id('id_token');
            $table->unsignedBigInteger('id_usuario')->nullable();
            $table->string('token', 255);
            $table->string('tipo_token', 100);
            $table->dateTime('fecha_creacion')->useCurrent();
            $table->dateTime('fecha_expiracion')->nullable();
            $table->string('estado', 20)->nullable();
            $table->foreign('id_usuario')->references('id_usuario')->on('usuarios_sistema');
        });

        Schema::create('audit_logs', function (Blueprint $table) {
            $table->id('id_log');
            $table->integer('id_usuario')->nullable();
            $table->string('accion', 100)->nullable();
            $table->string('tabla_afectada', 50)->nullable();
            $table->bigInteger('id_registro')->nullable();
            $table->json('detalles')->nullable();
            $table->timestamp('created_at')->useCurrent();
        });

        // 11. TABLAS NATIVAS DE LARAVEL (Caché, Sesiones, Jobs)
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });

        Schema::create('cache', function (Blueprint $table) {
            $table->string('key')->primary();
            $table->mediumText('value');
            $table->integer('expiration');
        });

        Schema::create('cache_locks', function (Blueprint $table) {
            $table->string('key')->primary();
            $table->string('owner');
            $table->integer('expiration');
        });

        Schema::create('jobs', function (Blueprint $table) {
            $table->id();
            $table->string('queue')->index();
            $table->longText('payload');
            $table->unsignedTinyInteger('attempts');
            $table->unsignedInteger('reserved_at')->nullable();
            $table->unsignedInteger('available_at');
            $table->unsignedInteger('created_at');
        });

        Schema::create('job_batches', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('name');
            $table->integer('total_jobs');
            $table->integer('pending_jobs');
            $table->integer('failed_jobs');
            $table->longText('failed_job_ids');
            $table->mediumText('options')->nullable();
            $table->integer('cancelled_at')->nullable();
            $table->integer('created_at');
            $table->integer('finished_at')->nullable();
        });

        Schema::create('failed_jobs', function (Blueprint $table) {
            $table->id();
            $table->string('uuid')->unique();
            $table->text('connection');
            $table->text('queue');
            $table->longText('payload');
            $table->longText('exception');
            $table->timestamp('failed_at')->useCurrent();
        });

        Schema::create('personal_access_tokens', function (Blueprint $table) {
            $table->id();
            $table->morphs('tokenable');
            $table->string('name');
            $table->string('token', 64)->unique();
            $table->text('abilities')->nullable();
            $table->timestamp('last_used_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();
        });


        // 12. TRIGGERS (Sintaxis de 1 sola línea para evitar el bug de PDO en Laravel)
        DB::unprepared("DROP TRIGGER IF EXISTS trg_citas_update;");

        DB::unprepared("
            CREATE TRIGGER trg_citas_update 
            BEFORE UPDATE ON citas 
            FOR EACH ROW
            INSERT INTO audit_logs (id_usuario, accion, tabla_afectada, id_registro, detalles)
            VALUES (NULL, 'update', 'citas', OLD.id_cita, JSON_OBJECT('old_estado', OLD.estado_cita, 'new_estado', NEW.estado_cita));
        ");
    }

    public function down(): void
    {
        // Se borran en orden inverso para no romper las llaves foráneas
        DB::unprepared('DROP TRIGGER IF EXISTS trg_citas_update');
        Schema::dropIfExists('personal_access_tokens');
        Schema::dropIfExists('failed_jobs');
        Schema::dropIfExists('job_batches');
        Schema::dropIfExists('jobs');
        Schema::dropIfExists('cache_locks');
        Schema::dropIfExists('cache');
        Schema::dropIfExists('sessions');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('users');
        Schema::dropIfExists('audit_logs');
        Schema::dropIfExists('tokens');
        Schema::dropIfExists('permisos');
        Schema::dropIfExists('publicidad');
        Schema::dropIfExists('bitacora_notificaciones');
        Schema::dropIfExists('notificaciones');
        Schema::dropIfExists('config_recordatorios');
        Schema::dropIfExists('config_global');
        Schema::dropIfExists('reviews');
        Schema::dropIfExists('horarios_bloqueados');
        Schema::dropIfExists('inventario');
        Schema::dropIfExists('ingresos_caja');
        Schema::dropIfExists('seguimiento_clinico');
        Schema::dropIfExists('evolucion_tratamiento');
        Schema::dropIfExists('archivos');
        Schema::dropIfExists('odontograma');
        Schema::dropIfExists('paciente_tipo_sangre');
        Schema::dropIfExists('paciente_peso');
        Schema::dropIfExists('pacientes_enfermedades_cronicas');
        Schema::dropIfExists('pacientes_alergias');
        Schema::dropIfExists('citas');
        Schema::dropIfExists('catalogo_servicios');
        Schema::dropIfExists('catalogo_tipo_sangre');
        Schema::dropIfExists('catalogo_enfermedades_cronicas');
        Schema::dropIfExists('catalogo_alergias');
        Schema::dropIfExists('pacientes');
        Schema::dropIfExists('contacto_emergencia');
        Schema::dropIfExists('doctores');
        Schema::dropIfExists('usuarios_sistema');
        Schema::dropIfExists('clinicas');
    }
};