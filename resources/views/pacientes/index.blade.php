@extends('layouts.app')

@section('titulo', 'Pacientes')

@section('contenido')
    <style>
        /* Contenedor Principal Header */
        .header-tools {
            display: flex;
            justify-content: center;
            /* Centrado como en tu imagen */
            align-items: center;
            gap: 20px;
            margin-bottom: 40px;
            margin-top: 20px;
        }

        /* Buscador Píldora */
        .search-pill-container {
            position: relative;
            width: 400px;
        }

        .search-pill {
            width: 100%;
            padding: 12px 25px;
            padding-right: 45px;
            border-radius: 50px;
            /* Redondo completo */
            border: none;
            background: #dcfce7;
            /* Un tono verdoso/azulado suave según tu imagen */
            background: #e0fbfc;
            /* Usando tu paleta original */
            font-size: 1rem;
            outline: none;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
        }

        .search-icon {
            position: absolute;
            right: 20px;
            top: 50%;
            transform: translateY(-50%);
            color: #666;
        }

        /* Botón Píldora */
        .btn-pill {
            background: #00b4d8;
            /* Tu color primario */
            color: white;
            border: none;
            border-radius: 50px;
            padding: 12px 25px;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 10px;
            cursor: pointer;
            box-shadow: 0 4px 10px rgba(0, 180, 216, 0.3);
            transition: transform 0.2s;
        }

        .btn-pill:hover {
            transform: translateY(-2px);
            background: #0096c7;
        }

        /* Grid de Tarjetas */
        .patients-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(450px, 1fr));
            /* Tarjetas anchas */
            gap: 30px;
            padding: 0 20px;
        }

        .patient-card {
            background: white;
            border-radius: 20px;
            /* Bordes muy redondeados */
            padding: 25px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.03);
            display: flex;
            align-items: center;
            gap: 20px;
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
        }

        .patient-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }

        /* Avatar Silueta */
        .avatar-silhouette {
            width: 70px;
            height: 70px;
            background: #5c6b7f;
            /* Gris azulado oscuro de tu imagen */
            border-radius: 50%;
            /* Círculo o forma de silueta */
            mask-image: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><path d="M224 256c70.7 0 128-57.3 128-128S294.7 0 224 0 96 57.3 96 128s57.3 128 128 128zm89.6 32h-16.7c-22.2 10.2-46.9 16-72.9 16s-50.6-5.8-72.9-16h-16.7C60.2 288 0 348.2 0 422.4V464c0 26.5 21.5 48 48 48h352c26.5 0 48-21.5 48-48v-41.6c0-74.2-60.2-134.4-134.4-134.4z"/></svg>');
            -webkit-mask-image: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><path d="M224 256c70.7 0 128-57.3 128-128S294.7 0 224 0 96 57.3 96 128s57.3 128 128 128zm89.6 32h-16.7c-22.2 10.2-46.9 16-72.9 16s-50.6-5.8-72.9-16h-16.7C60.2 288 0 348.2 0 422.4V464c0 26.5 21.5 48 48 48h352c26.5 0 48-21.5 48-48v-41.6c0-74.2-60.2-134.4-134.4-134.4z"/></svg>');
            mask-size: 50%;
            mask-repeat: no-repeat;
            mask-position: center;
            background-color: #5c6b7f;
            display: flex;
            /* Fallback si mask no funciona bien */
            align-items: center;
            justify-content: center;
        }

        /* Fallback más simple con FontAwesome si mask falla */
        .avatar-simple {
            width: 60px;
            height: 60px;
            color: #5c6b7f;
            font-size: 3.5rem;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .card-info h3 {
            margin: 0;
            font-size: 1.1rem;
            color: #333;
            font-weight: 700;
        }

        .card-info p {
            margin: 2px 0;
            font-size: 0.9rem;
            color: #666;
        }

        .card-info .email {
            font-size: 0.8rem;
            color: #999;
        }

        .card-labels {
            display: grid;
            grid-template-columns: auto 1fr;
            gap: 10px 20px;
            width: 100%;
        }

        .label-text {
            font-weight: 600;
            color: #00b4d8;
            font-size: 0.9rem;
        }

        .value-text {
            color: #333;
            font-size: 0.9rem;
        }
    </style>

    <h2 class="page-title" style="margin-left: 20px;">Pacientes</h2>

    @if(session('success'))
        <div
            style="background: #d1fae5; color: #065f46; padding: 15px; border-radius: 8px; margin: 0 20px 20px 20px; border: 1px solid #10b981;">
            <i class="fa-solid fa-check-circle"></i> {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div
            style="background: #fee2e2; color: #b91c1c; padding: 15px; border-radius: 8px; margin: 0 20px 20px 20px; border: 1px solid #ef4444;">
            <i class="fa-solid fa-triangle-exclamation"></i> {{ session('error') }}
        </div>
    @endif

    <div class="header-tools">
        <div class="search-pill-container">
            <input type="text" class="search-pill" placeholder="Buscar paciente">
            <i class="fa-solid fa-magnifying-glass search-icon"></i>
        </div>

        <button class="btn-pill" onclick="openModal('modal-new-patient')">
            <i class="fa-solid fa-user-plus"></i> Nuevo Paciente
        </button>
    </div>

    <div class="patients-grid">
        @forelse($pacientes as $paciente)
            <div class="patient-card" onclick="verPerfil({{ $paciente }})">
                <div class="avatar-simple">
                    <i class="fa-solid fa-circle-user"></i>
                </div>

                <div class="card-labels" style="flex: 1;">
                    <span class="label-text">Nombre</span>
                    <span class="value-text" style="font-weight: 700;">{{ $paciente->nombre }}
                        {{ $paciente->apellido_paterno }}</span>

                    <span class="label-text">Teléfono</span>
                    <span class="value-text">{{ $paciente->telefono }}</span>

                    <span style="grid-column: 1 / -1; font-size: 0.8rem; color: #999; margin-top: 5px;">
                        {{ $paciente->correo_electronico }}
                    </span>
                </div>
            </div>
        @empty
            <div style="grid-column: 1 / -1; text-align: center; color: #888; padding: 50px;">
                <p>No hay pacientes registrados.</p>
            </div>
        @endforelse
    </div>


    <div id="modal-new-patient" class="modal-overlay">
        <div class="modal-glass modal-lg">
            <button class="close-modal" onclick="closeModal('modal-new-patient')">&times;</button>
            <h3 style="color: var(--secondary-color); margin-bottom: 25px;">Registrar Nuevo Paciente</h3>

            <form action="{{ route('pacientes.store') }}" method="POST">
                @csrf <div class="form-grid">
                    <input type="text" name="nombre" class="modern-input" placeholder="Nombre(s)" required>
                    <input type="text" name="apellido_paterno" class="modern-input" placeholder="Apellido Paterno" required>
                    <input type="text" name="apellido_materno" class="modern-input" placeholder="Apellido Materno">
                    <input type="email" name="email" class="modern-input" placeholder="Email (Usuario App)" required>
                    <input type="text" name="telefono" class="modern-input" placeholder="Teléfono" required>

                    <div style="position:relative">
                        <label style="font-size:0.75em; position:absolute; top:-18px; left:5px; color:#666;">Fecha
                            Nacimiento</label>
                        <input type="date" name="fecha_nacimiento" class="modern-input" required>
                    </div>

                    <select name="sexo" class="modern-input">
                        <option value="O">Sexo</option>
                        <option value="M">Masculino</option>
                        <option value="F">Femenino</option>
                    </select>

                    <select name="tipo_sangre" class="modern-input">
                        <option value="">Tipo Sangre</option>
                        <option value="O+">O+</option>
                        <option value="A+">A+</option>
                        <option value="B+">B+</option>
                        <option value="AB+">AB+</option>
                        <option value="O-">O-</option>
                    </select>

                    <input type="number" name="peso" class="modern-input" placeholder="Peso (kg)" step="0.1">
                    <input type="text" name="ocupacion" class="modern-input" placeholder="Ocupación">

                    <div class="full-width">
                        <textarea name="enfermedades_cronicas" class="modern-input" rows="2"
                            placeholder="Enfermedades Crónicas"></textarea>
                    </div>
                    <div class="full-width">
                        <textarea name="alergias" class="modern-input" rows="2" placeholder="Alergias"></textarea>
                    </div>
                </div>

                <div style="text-align: center; margin-top: 25px;">
                    <button type="submit" class="ghost-btn"
                        style="width: 100%; padding: 15px; font-size: 1rem; border-radius: 12px; background: var(--primary-color);">
                        Guardar y Generar Token
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div id="modal-patient-profile" class="modal-overlay">
        <div class="modal-glass modal-xl" style="max-width: 900px; padding: 40px;">
            <button class="close-modal" onclick="closeModal('modal-patient-profile')">&times;</button>

            <div style="display: flex; gap: 40px;">
                <div style="flex: 1;">
                    <h2 id="p-name" style="color: #333; margin-top: 0;">Nombre</h2>
                    <p id="p-email" style="color: #888; margin-bottom: 20px;">email</p>

                    <div style="background: #f9f9f9; padding: 20px; border-radius: 15px;">
                        <p><strong>Teléfono:</strong> <span id="view-tel">...</span></p>
                        <p><strong>Edad:</strong> <span id="view-edad">...</span></p>
                        <p><strong>Sexo:</strong> <span id="view-sexo">...</span></p>
                        <p><strong>Sangre:</strong> <span id="view-sangre"
                                style="color: var(--primary-color); font-weight:bold;">...</span></p>
                        <p><strong>Peso:</strong> <span id="view-peso">...</span></p>
                    </div>
                </div>

                <div style="flex: 1;">
                    <h4 style="color: #ef4444; margin-top: 0;"><i class="fa-solid fa-triangle-exclamation"></i> Alergias
                    </h4>
                    <p id="view-alergias" style="border-bottom: 1px solid #eee; padding-bottom: 10px;">Ninguna</p>

                    <h4 style="color: #f59e0b; margin-top: 20px;"><i class="fa-solid fa-notes-medical"></i> Enfermedades
                    </h4>
                    <p id="view-enfermedades" style="border-bottom: 1px solid #eee; padding-bottom: 10px;">Ninguna</p>

                    <div style="margin-top: 30px; display: flex; flex-direction: column; gap: 10px;">
                        <button class="ghost-btn" onclick="openFileExplorer()">Subir Cuidados (PDF)</button>
                        <button class="ghost-btn" style="background: var(--primary-color);" onclick="abrirModalCita()">Nueva
                            Cita</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="modal-add-cita" class="modal-overlay">
        <div class="modal-glass modal-xl">
            <button class="close-modal" onclick="closeModal('modal-add-cita')">&times;</button>
            <h3>Nueva Cita</h3>

            <form action="{{ route('citas.store') }}" method="POST">
                @csrf
                <input type="hidden" name="id_paciente" id="input_cita_paciente_id">

                <div class="form-grid">
                    <div class="full-width">
                        <label style="font-size:0.9rem; color:#666;">Paciente</label>
                        <input type="text" id="visual_paciente_nombre" class="modern-input" readonly
                            style="background: #e0fbfc; color: var(--primary-color); font-weight:bold;">
                    </div>

                    <div class="full-width">
                        <label style="font-size:0.9rem; color:#666;">Fecha y Hora</label>
                        <input type="datetime-local" name="fecha_hora" class="modern-input" required>
                    </div>

                    <div class="full-width">
                        <label style="font-size:0.9rem; color:#666;">Tratamiento / Motivo</label>
                        <select name="id_servicio" class="modern-input" required>
                            <option value="">Seleccione un tratamiento...</option>
                            @foreach($servicios as $servicio)
                                <option value="{{ $servicio->id_servicio }}">
                                    {{ $servicio->nombre_servicio }} - ${{ number_format($servicio->precio_base, 2) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <h4 style="margin-top: 20px;">Odontograma (Referencia)</h4>
                <div class="odontograma-container">
                    <div id="odonto-nueva-cita"></div>
                </div>

                <button type="submit" class="ghost-btn"
                    style="width:100%; margin-top:20px; background: var(--primary-color); color: white;">
                    Confirmar y Agendar
                </button>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        // Variable global para recordar qué paciente estamos viendo
        let currentPaciente = null;

        function verPerfil(paciente) {
            // Guardamos el paciente actual en la variable global
            currentPaciente = paciente;

            // Llenar datos visuales del perfil
            document.getElementById('p-name').innerText = paciente.nombre + ' ' + paciente.apellido_paterno;
            document.getElementById('p-email').innerText = paciente.correo_electronico;
            document.getElementById('view-tel').innerText = paciente.telefono;
            document.getElementById('view-sexo').innerText = paciente.sexo;
            document.getElementById('view-sangre').innerText = paciente.tipo_sangre || 'N/A';
            document.getElementById('view-peso').innerText = (paciente.peso || '0') + ' kg';
            document.getElementById('view-alergias').innerText = paciente.alergias || 'Ninguna';
            document.getElementById('view-enfermedades').innerText = paciente.enfermedades_cronicas || 'Ninguna';

            // Edad
            if (paciente.fecha_nacimiento) {
                const birth = new Date(paciente.fecha_nacimiento);
                const now = new Date();
                const age = now.getFullYear() - birth.getFullYear();
                document.getElementById('view-edad').innerText = age + ' años';
            }

            // Configurar el botón "Nueva Cita" para que use la función correcta
            // Busca el botón que tiene el texto "Nueva Cita" dentro del modal
            const btnAgendar = document.querySelector('#modal-patient-profile button[onclick*="alert"]');
            if (btnAgendar) {
                // Reemplazamos el onclick viejo por el nuevo
                btnAgendar.setAttribute('onclick', 'abrirModalCita()');
            } else {
                // Si no lo encuentra por selector, asegúrate de actualizar el HTML del botón en el modal de perfil a:
                // onclick="abrirModalCita()"
            }

            openModal('modal-patient-profile');
        }

        function abrirModalCita() {
            if (!currentPaciente) return;

            // 1. Cerrar perfil
            closeModal('modal-patient-profile');

            // 2. Preparar el modal de Cita
            // Llenar el ID oculto para que se guarde en la BD
            document.getElementById('input_cita_paciente_id').value = currentPaciente.id_paciente;
            // Mostrar el nombre solo para que el usuario sepa a quién agenda
            document.getElementById('visual_paciente_nombre').value = currentPaciente.nombre + ' ' + currentPaciente
                .apellido_paterno;

            // 3. Inicializar odontograma si existe
            if (typeof initOdontogram === 'function') {
                initOdontogram('odonto-nueva-cita');
            }

            // 4. Abrir modal
            openModal('modal-add-cita');
        }

        function openFileExplorer() {
            let input = document.createElement('input');
            input.type = 'file';
            input.accept = 'application/pdf';
            input.click();
        }
    </script>
@endsection