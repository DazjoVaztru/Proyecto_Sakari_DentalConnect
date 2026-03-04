@extends('layouts.app')

@section('titulo', 'Pacientes')

@section('contenido')
    <style>
        /* Contenedor Principal Header */
        .header-tools {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 20px;
            margin-bottom: 40px;
            margin-top: 10px;
        }

        /* Buscador Píldora */
        .search-pill-container {
            position: relative;
            width: 450px;
        }

        .search-pill {
            width: 100%;
            padding: 14px 25px;
            padding-right: 50px;
            border-radius: 50px;
            border: none;
            background: #e0fbfc;
            font-size: 1rem;
            outline: none;
            box-shadow: 0 4px 15px rgba(0, 180, 216, 0.1);
            transition: all 0.3s ease;
            font-weight: 500;
        }

        .search-pill:focus {
            box-shadow: 0 4px 20px rgba(0, 180, 216, 0.2);
            background: #fff;
            transform: scale(1.02);
        }

        .search-icon {
            position: absolute;
            right: 22px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--primary-color);
            font-size: 1.1rem;
        }

        /* Botón Píldora */
        .btn-pill {
            background: var(--primary-color);
            color: white;
            border: none;
            border-radius: 50px;
            padding: 14px 28px;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 12px;
            cursor: pointer;
            box-shadow: 0 6px 15px rgba(0, 180, 216, 0.3);
            transition: all 0.3s ease;
            font-size: 0.95rem;
        }

        .btn-pill:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(0, 180, 216, 0.4);
            filter: brightness(1.1);
        }

        /* Grid de Tarjetas */
        .patients-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(400px, 1fr));
            gap: 25px;
            padding: 10px 0;
        }

        .patient-card {
            background: white;
            border-radius: 22px;
            padding: 22px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.04);
            display: flex;
            align-items: center;
            gap: 20px;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            border: 1px solid rgba(0, 180, 216, 0.05);
        }

        .patient-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 15px 35px rgba(0, 180, 216, 0.12);
            border-color: rgba(0, 180, 216, 0.2);
        }

        /* Avatar */
        .avatar-circle {
            width: 75px;
            height: 75px;
            background: linear-gradient(135deg, #e0fbfc 0%, #caf0f8 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            box-shadow: 0 4px 12px rgba(0, 180, 216, 0.15);
        }

        .avatar-circle i {
            color: var(--primary-color);
            font-size: 2.2rem;
        }

        .card-details {
            flex: 1;
            overflow: hidden;
        }

        .card-details .name {
            font-size: 1.15rem;
            font-weight: 800;
            color: #2b2d42;
            margin-bottom: 4px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .card-details .info-row {
            display: flex;
            align-items: center;
            gap: 8px;
            color: #6c757d;
            font-size: 0.88rem;
            margin-bottom: 3px;
        }

        .card-details .info-row i {
            width: 16px;
            color: #9db2bf;
        }

        .card-details .email {
            font-size: 0.75rem;
            color: #adb5bd;
            margin-top: 5px;
            display: block;
        }

        /* Alerta Alergias */
        .allergy-badge {
            position: absolute;
            top: -12px;
            right: 15px;
            background: #ef4444;
            color: white;
            padding: 6px 14px;
            border-radius: 20px;
            font-size: 0.7rem;
            font-weight: 800;
            box-shadow: 0 4px 10px rgba(239, 68, 68, 0.4);
            display: flex;
            align-items: center;
            gap: 5px;
            z-index: 2;
        }

        /* Empty State */
        .empty-state {
            grid-column: 1 / -1;
            text-align: center;
            padding: 80px 20px;
            color: #adb5bd;
        }

        .empty-state i {
            font-size: 4rem;
            margin-bottom: 20px;
            opacity: 0.3;
        }
    </style>

    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h2 class="page-title" style="margin-bottom: 0;">Pacientes</h2>
        <div style="font-size: 0.9rem; color: #6c757d; font-weight: 500;">
            <i class="fa-solid fa-circle-info"></i> {{ count($pacientes) }} pacientes registrados
        </div>
    </div>

    @if(session('success'))
        <div
            style="background: #d1fae5; border: 1px solid #6ee7b7; border-radius: 12px; padding: 15px 20px; margin-bottom: 25px; color: #065f46; font-weight: 600; box-shadow: 0 4px 12px rgba(16, 185, 129, 0.1);">
            <i class="fa-solid fa-circle-check"></i> {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div
            style="background: #fee2e2; border: 1px solid #fca5a5; border-radius: 12px; padding: 15px 20px; margin-bottom: 25px; color: #991b1b; font-weight: 600; box-shadow: 0 4px 12px rgba(239, 68, 68, 0.1);">
            <i class="fa-solid fa-triangle-exclamation"></i> {{ session('error') }}
        </div>
    @endif

    <div class="header-tools">
        <div class="search-pill-container">
            <input type="text" id="patient-search" class="search-pill"
                placeholder="Buscar por nombre, apellidos o teléfono...">
            <i class="fa-solid fa-magnifying-glass search-icon"></i>
        </div>

        <button class="btn-pill" onclick="openModal('modal-new-patient')">
            <i class="fa-solid fa-user-plus"></i> Nuevo Paciente
        </button>
    </div>

    <div class="patients-grid" id="patients-grid">
        @forelse($pacientes as $paciente)
            <div class="patient-card"
                onclick="verPerfil({{ json_encode($paciente->load(['usuario', 'contactoEmergencia', 'archivos'])) }})">
                <div class="avatar-circle">
                    <i class="fa-solid fa-user"></i>
                </div>

                @php
                    $hasAllergies = !empty($paciente->alergias) && strtolower($paciente->alergias) !== 'ninguna';
                @endphp

                @if($hasAllergies)
                    <div class="allergy-badge" title="Paciente con Alergias Críticas">
                        <i class="fa-solid fa-triangle-exclamation"></i> ALERGIAS
                    </div>
                @endif

                <div class="card-details">
                    <div class="name">{{ $paciente->nombre }} {{ $paciente->apellido_paterno }}</div>
                    <div class="info-row">
                        <i class="fa-solid fa-phone"></i> {{ $paciente->telefono }}
                    </div>
                    <div class="info-row">
                        <i class="fa-solid fa-droplet"></i>
                        {{ $paciente->tipo_sangre ?? 'S/D' }} |
                        <i class="fa-solid fa-calendar" style="margin-left: 5px;"></i>
                        {{ $paciente->fecha_nacimiento ? $paciente->fecha_nacimiento->age . ' años' : 'S/D' }}
                    </div>
                    <span class="email">{{ $paciente->correo_electronico }}</span>
                </div>
            </div>
        @empty
            <div class="empty-state">
                <i class="fa-solid fa-users-slash"></i>
                <p style="font-size: 1.2rem; font-weight: 600;">No hay pacientes registrados aún</p>
                <p>Comienza registrando a tu primer paciente usando el botón de arriba.</p>
            </div>
        @endforelse
    </div>

@endsection

@section('modales')
    {{-- Modal de Registro (Extendido) --}}
    <div id="modal-new-patient" class="modal-overlay">
        <div class="modal-glass modal-lg" style="width: 820px; max-width: 95vw; max-height: 90vh; overflow-y: auto;">
            <button class="close-modal" onclick="closeModal('modal-new-patient')">&times;</button>
            <h3
                style="color: var(--secondary-color); margin-bottom: 20px; text-align: left; font-weight: 800; font-size: 1.6rem;">
                <i class="fa-solid fa-user-plus" style="margin-right: 8px;"></i> Registrar Nuevo Paciente
            </h3>

            @if($errors->any())
                <div
                    style="background: #fee2e2; border: 1px solid #fca5a5; border-radius: 8px; padding: 12px 15px; margin-bottom: 15px; color: #991b1b; font-size: 0.85rem;">
                    <i class="fa-solid fa-triangle-exclamation"></i> {{ $errors->first() }}
                </div>
            @endif

            <form action="{{ route('pacientes.store') }}" method="POST">
                @csrf
                <div class="form-grid">
                    {{-- Datos Personales --}}
                    <div class="full-width"
                        style="font-size: 0.78em; font-weight: 700; color: var(--primary-color); text-transform: uppercase; border-bottom: 2px solid #e0fbfc; padding-bottom: 6px; margin-bottom: 5px;">
                        <i class="fa-solid fa-id-card" style="margin-right: 5px;"></i> Datos Personales
                    </div>
                    <input type="text" name="nombre" class="modern-input" placeholder="Nombre(s)*" required
                        value="{{ old('nombre') }}"
                        oninput="this.value=this.value.replace(/[^A-Za-zÀ-ÿÑñ ]/g,'').replace(/  +/g,' ')">
                    <input type="text" name="apellido_paterno" class="modern-input" placeholder="Apellido Paterno*" required
                        value="{{ old('apellido_paterno') }}"
                        oninput="this.value=this.value.replace(/[^A-Za-zÀ-ÿÑñ ]/g,'').replace(/  +/g,' ')">
                    <input type="text" name="apellido_materno" class="modern-input" placeholder="Apellido Materno"
                        value="{{ old('apellido_materno') }}"
                        oninput="this.value=this.value.replace(/[^A-Za-zÀ-ÿÑñ ]/g,'').replace(/  +/g,' ')">
                    <input type="email" name="email" class="modern-input" placeholder="Email (Usuario App)*" required
                        value="{{ old('email') }}">
                    <input type="text" name="telefono" class="modern-input" placeholder="Teléfono (+codigo)*" required
                        maxlength="15" value="{{ old('telefono') }}" oninput="this.value=this.value.replace(/[^0-9+]/g,'')">

                    <div style="position:relative; padding-top: 18px;">
                        <label
                            style="font-size:0.75em; position:absolute; top:0; left:5px; color:#666; font-weight:600;">Fecha
                            Nacimiento *</label>
                        <input type="date" name="fecha_nacimiento" class="modern-input" required
                            value="{{ old('fecha_nacimiento') }}">
                    </div>

                    <select name="sexo" class="modern-input">
                        <option value="O" {{ old('sexo', 'O') == 'O' ? 'selected' : '' }}>Sexo</option>
                        <option value="M" {{ old('sexo') == 'M' ? 'selected' : '' }}>Masculino</option>
                        <option value="F" {{ old('sexo') == 'F' ? 'selected' : '' }}>Femenino</option>
                    </select>

                    <select name="tipo_sangre" class="modern-input">
                        <option value="">Tipo de Sangre</option>
                        @foreach(['O+', 'A+', 'B+', 'AB+', 'O-', 'A-', 'B-', 'AB-'] as $ts)
                            <option value="{{ $ts }}" {{ old('tipo_sangre') == $ts ? 'selected' : '' }}>{{ $ts }}</option>
                        @endforeach
                    </select>

                    <input type="number" name="peso" class="modern-input" placeholder="Peso (kg enteros)" step="1" min="0"
                        max="500" value="{{ old('peso') }}" oninput="this.value=this.value.replace(/[^0-9]/g,'')">

                    <input type="text" name="direccion" class="modern-input" placeholder="Dirección Completa"
                        value="{{ old('direccion') }}" style="grid-column: span 1;">

                    <input type="text" name="ocupacion" class="modern-input" placeholder="Ocupación"
                        value="{{ old('ocupacion') }}">

                    {{-- Contacto de Emergencia --}}
                    <div class="full-width"
                        style="font-size: 0.78em; font-weight: 700; color: #ef4444; text-transform: uppercase; border-bottom: 2px solid #fee2e2; padding-bottom: 6px; margin-top: 12px; margin-bottom: 5px;">
                        <i class="fa-solid fa-phone-volume" style="margin-right: 5px;"></i> Contacto de Emergencia
                    </div>
                    <input type="text" name="emergencia_nombre" class="modern-input" placeholder="Nombre Contacto"
                        value="{{ old('emergencia_nombre') }}"
                        oninput="this.value=this.value.replace(/[^A-Za-zÀ-ÿÑñ ]/g,'').replace(/  +/g,' ')">
                    <input type="text" name="emergencia_apellido_paterno" class="modern-input"
                        placeholder="Apellido Paterno" value="{{ old('emergencia_apellido_paterno') }}"
                        oninput="this.value=this.value.replace(/[^A-Za-zÀ-ÿÑñ ]/g,'').replace(/  +/g,' ')">
                    <input type="text" name="emergencia_apellido_materno" class="modern-input"
                        placeholder="Apellido Materno" value="{{ old('emergencia_apellido_materno') }}"
                        oninput="this.value=this.value.replace(/[^A-Za-zÀ-ÿÑñ ]/g,'').replace(/  +/g,' ')">
                    <input type="text" name="emergencia_telefono" class="modern-input"
                        placeholder="Teléfono Emergencia (+codigo)" maxlength="15" value="{{ old('emergencia_telefono') }}"
                        oninput="this.value=this.value.replace(/[^0-9+]/g,'')">

                    {{-- Salud --}}
                    <div class="full-width"
                        style="font-size: 0.78em; font-weight: 700; color: #f59e0b; text-transform: uppercase; border-bottom: 2px solid #fef3c7; padding-bottom: 6px; margin-top: 12px; margin-bottom: 5px;">
                        <i class="fa-solid fa-notes-medical" style="margin-right: 5px;"></i> Información de Salud
                    </div>
                    <div class="full-width">
                        <textarea name="enfermedades_cronicas" class="modern-input" rows="2"
                            placeholder="Enfermedades Crónicas (Texto libre)">{{ old('enfermedades_cronicas') }}</textarea>
                    </div>
                    <div class="full-width">
                        <textarea name="alergias" class="modern-input" rows="2"
                            placeholder="Alergias a Medicamentos (Texto libre)">{{ old('alergias') }}</textarea>
                    </div>
                </div>

                <div style="text-align: center; margin-top: 25px;">
                    <button type="button" class="ghost-btn" id="btn-registrar-paciente"
                        style="width: 100%; padding: 15px; font-size: 1.1rem; border-radius: 12px; font-weight: 800;">
                        <i class="fa-solid fa-floppy-disk"></i> Registrar Paciente
                    </button>
                    <!-- Actual submit button is hidden -->
                    <button type="submit" id="btn-submit-real-paciente" style="display: none;"></button>
                </div>
            </form>
        </div>
    </div>

    {{-- Perfil del Paciente (Sección de detalle) --}}
    <div id="modal-patient-profile" class="modal-overlay">
        <div class="modal-glass modal-xl" style="max-width: 1000px; padding: 0; overflow: hidden; border-radius: 20px;">
            <button class="close-modal" onclick="closeModal('modal-patient-profile')"
                style="position: absolute; top: 15px; right: 20px; z-index: 10;">&times;</button>

            <div
                style="background: linear-gradient(135deg, #e0fbfc 0%, #caf0f8 100%); padding: 35px 45px; border-bottom: 1px solid #b0e0f5;">
                <div style="display: flex; align-items: center; gap: 25px;">
                    <div style="position: relative; width: 85px; height: 85px;"
                        title="Clic para subir/cambiar foto de progreso">
                        <img id="p-foto" src="" alt="Foto Paciente"
                            style="width: 100%; height: 100%; border-radius: 50%; object-fit: cover; box-shadow: 0 4px 15px rgba(0,180,216,0.25); display: none; cursor: pointer;"
                            onclick="document.getElementById('foto-upload').click()">
                        <div id="p-foto-placeholder"
                            style="background: white; border-radius: 50%; width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; box-shadow: 0 4px 15px rgba(0,180,216,0.25); cursor: pointer;"
                            onclick="document.getElementById('foto-upload').click()">
                            <i class="fa-solid fa-camera" style="font-size: 2.5rem; color: var(--primary-color);"></i>
                        </div>
                        <input type="file" id="foto-upload" style="display: none;"
                            accept="image/jpeg, image/png, image/webp" onchange="uploadFoto(this)">
                    </div>
                    <div>
                        <h2 id="p-name" style="color: #2b2d42; margin: 0; font-size: 1.8em; font-weight: 800;">Nombre</h2>
                        <div style="display: flex; gap: 15px; margin-top: 8px;">
                            <span id="p-email" style="color: #6c757d; font-size: 0.95em;"><i
                                    class="fa-solid fa-envelope"></i> email</span>
                            <span id="p-tel" style="color: #6c757d; font-size: 0.95em;"><i class="fa-solid fa-phone"></i>
                                tel</span>
                        </div>
                    </div>
                </div>
            </div>

            <div style="display: flex; border-bottom: 2px solid #eee; padding: 0 30px; background: white;">
                <button onclick="showTab('tab-general')" id="btn-tab-general" class="tab-btn tab-active">
                    <i class="fa-solid fa-user"></i> Resumen General
                </button>
                <button onclick="showTab('tab-evolucion')" id="btn-tab-evolucion" class="tab-btn">
                    <i class="fa-solid fa-notes-medical"></i> Evolución Clínica
                </button>
                <button onclick="showTab('tab-historial')" id="btn-tab-historial" class="tab-btn">
                    <i class="fa-solid fa-clock-rotate-left"></i> Historial y Tratamientos
                </button>
            </div>

            <style>
                .tab-btn {
                    padding: 16px 25px;
                    border: none;
                    background: none;
                    color: #6c757d;
                    cursor: pointer;
                    font-weight: 600;
                    font-size: 0.95rem;
                    transition: all 0.2s ease;
                    border-bottom: 3px solid transparent;
                    display: flex;
                    align-items: center;
                    gap: 10px;
                }

                .tab-btn.tab-active {
                    border-bottom-color: var(--primary-color);
                    color: var(--primary-color);
                    font-weight: 800;
                }

                .profile-info-card {
                    background: #f8f9fa;
                    padding: 22px;
                    border-radius: 18px;
                    margin-bottom: 15px;
                }

                .profile-info-card h4 {
                    margin: 0 0 15px 0;
                    color: #495057;
                    font-size: 0.85em;
                    text-transform: uppercase;
                    letter-spacing: 1.2px;
                    font-weight: 700;
                }

                .info-item {
                    display: flex;
                    justify-content: space-between;
                    margin-bottom: 10px;
                    font-size: 0.95rem;
                }

                .info-item .label {
                    color: #6c757d;
                }

                .info-item .value {
                    color: #212529;
                    font-weight: 600;
                }
            </style>

            <div style="padding: 35px 45px; overflow-y: auto; max-height: 65vh;">
                <div id="tab-general">
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 40px;">
                        <div>
                            <div class="profile-info-card">
                                <h4>Datos del Paciente</h4>
                                <div class="info-item"><span class="label">Edad:</span> <span class="value"
                                        id="view-edad">...</span></div>
                                <div class="info-item"><span class="label">Sexo:</span> <span class="value"
                                        id="view-sexo">...</span></div>
                                <div class="info-item"><span class="label">Sangre:</span> <span class="value"
                                        id="view-sangre">...</span></div>
                                <div class="info-item"><span class="label">Peso:</span> <span class="value"
                                        id="view-peso">...</span></div>
                                <div class="info-item"><span class="label">Ocupación:</span> <span class="value"
                                        id="view-ocupacion">...</span></div>
                            </div>

                            <div class="profile-info-card" style="background: #fff5f5; border: 1px solid #fed7d7;">
                                <h4 style="color: #c53030;"><i class="fa-solid fa-phone-volume"></i> En Caso de Emergencia
                                </h4>
                                <div class="info-item"><span class="label">Contacto:</span> <span class="value"
                                        id="view-emergencia-nombre" style="color: #c53030;">...</span></div>
                                <div class="info-item"><span class="label">Teléfono:</span> <span class="value"
                                        id="view-emergencia-tel" style="font-weight: 800;">...</span></div>
                            </div>
                        </div>

                        <div>
                            <div
                                style="background: #fff5f5; border: 1px solid #fed7d7; border-radius: 18px; padding: 22px; margin-bottom: 15px;">
                                <h4 style="color: #c53030; margin-bottom:15px;"><i
                                        class="fa-solid fa-triangle-exclamation"></i> Alergias</h4>
                                <div id="view-alergias-badges">
                                    <span style="color: #888; font-size: 0.9em;">Sin alergias registradas</span>
                                </div>
                            </div>

                            <div
                                style="background: #fffbeb; border: 1px solid #fde68a; border-radius: 18px; padding: 22px; margin-bottom: 25px;">
                                <h4 style="color: #b45309; margin-bottom:12px;"><i class="fa-solid fa-notes-medical"></i>
                                    Enfermedades Crónicas</h4>
                                <p id="view-enfermedades"
                                    style="margin: 0; color: #555; font-size: 0.95rem; line-height: 1.5;">Ninguna</p>
                            </div>

                            <button class="btn-pill"
                                style="width: 100%; justify-content: center; padding: 18px; margin-bottom: 12px;"
                                onclick="abrirModalCita()">
                                <i class="fa-solid fa-calendar-plus"></i> AGENDAR NUEVA CITA
                            </button>

                            <div style="display: flex; gap: 10px;">
                                <button onclick="abrirModalEdit()"
                                    style="flex: 1; padding: 12px; border-radius: 12px; background: #f59e0b; color: white; border: none; font-weight: 700; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 8px;">
                                    <i class="fa-solid fa-pen-to-square"></i> Editar Datos
                                </button>
                                <button onclick="eliminarPaciente()"
                                    style="flex: 1; padding: 12px; border-radius: 12px; background: #ef4444; color: white; border: none; font-weight: 700; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 8px;">
                                    <i class="fa-solid fa-trash-can"></i> Eliminar
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <div id="tab-evolucion" style="display: none;">
                    {{-- Formulario nueva evolución --}}
                    <div
                        style="background: #f0f9ff; border: 1px solid #bae6fd; border-radius: 14px; padding: 20px; margin-bottom: 20px;">
                        <h4
                            style="margin: 0 0 12px 0; color: var(--secondary-color); font-size: 0.9em; text-transform: uppercase; letter-spacing: 1px;">
                            <i class="fa-solid fa-pen-to-square"></i> Nueva Nota de Evolución
                        </h4>
                        <textarea id="nueva-evolucion-texto" rows="3" class="modern-input"
                            style="width: 100%; resize: vertical; box-sizing: border-box;"
                            placeholder="Descripción del avance clínico..."></textarea>

                        <div style="margin-top: 10px; display: flex; align-items: center; justify-content: space-between;">
                            <div>
                                <label for="nueva-evolucion-foto"
                                    style="cursor: pointer; color: #00b4d8; font-size: 0.9rem; font-weight: 700; display: flex; align-items: center; gap: 8px;">
                                    <i class="fa-solid fa-camera"></i> <span id="label-ev-foto">Adjuntar Evidencia
                                        (Foto)</span>
                                </label>
                                <input type="file" id="nueva-evolucion-foto"
                                    accept="image/jpeg, image/png, image/jpg, image/webp" style="display: none;" onchange="
                                        const file = this.files[0];
                                        if(file) {
                                            document.getElementById('label-ev-foto').innerText = file.name;
                                            document.getElementById('label-ev-foto').style.color = '#10b981';
                                        } else {
                                            document.getElementById('label-ev-foto').innerText = 'Adjuntar Evidencia (Foto)';
                                            document.getElementById('label-ev-foto').style.color = '#00b4d8';
                                        }">
                            </div>

                            <button onclick="guardarEvolucion()" id="btn-submit-evolucion" class="ghost-btn"
                                style="padding: 10px 25px; font-size: 0.9rem;">
                                <i class="fa-solid fa-floppy-disk"></i> Guardar Nota
                            </button>
                        </div>
                    </div>
                    {{-- Historial --}}
                    <div id="evolucion-list" style="display: flex; flex-direction: column; gap: 12px;">
                        <div style="text-align: center; color: #888; padding: 20px;">
                            <i class="fa-solid fa-spinner fa-spin"></i> Cargando evoluciones...
                        </div>
                    </div>
                </div>

                <div id="tab-historial" style="display: none;">
                    <h4 style="color: var(--secondary-color); font-size: 1.1em; margin-bottom: 15px;"><i
                            class="fa-solid fa-clipboard-list"></i> Historial de Citas y Tratamientos</h4>
                    <div id="historial-citas-list" style="display: flex; flex-direction: column; gap: 15px;">
                        <div style="text-align: center; color: #888; padding: 20px;">
                            <i class="fa-solid fa-spinner fa-spin"></i> Cargando historial...
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- MODAL: AGENDAR CITA (LOCAL) --}}
    <div id="modal-add-cita" class="modal-overlay">
        <div class="modal-glass modal-md" style="width: 500px;">
            <button class="close-modal" onclick="closeModal('modal-add-cita')">&times;</button>
            <h3 style="color: var(--secondary-color); margin-bottom: 20px;">
                <i class="fa-solid fa-calendar-plus"></i> Agendar Nueva Cita
            </h3>

            <form id="form-add-cita" action="{{ route('citas.store') }}" method="POST">
                @csrf
                <input type="hidden" name="id_paciente" id="form-cita-paciente-id">

                <div style="margin-bottom: 15px;">
                    <label style="display: block; margin-bottom: 5px; font-weight: 700;">Paciente:</label>
                    <input type="text" id="form-cita-paciente-nombre" class="modern-input" readonly
                        style="background: #f8f9fa;">
                </div>

                <div style="margin-bottom: 15px;">
                    <label style="display: block; margin-bottom: 5px; font-weight: 700;">Servicio:</label>
                    <select name="id_servicio" class="modern-input" required>
                        @foreach($servicios as $srv)
                            <option value="{{ $srv->id_servicio }}">{{ $srv->nombre_servicio }}
                                (${{ number_format($srv->precio_base, 2) }})</option>
                        @endforeach
                    </select>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-bottom: 20px;">
                    <div>
                        <label style="display: block; margin-bottom: 5px; font-weight: 700;">Fecha:</label>
                        <input type="date" name="fecha" class="modern-input" required value="{{ date('Y-m-d') }}">
                    </div>
                    <div>
                        <label style="display: block; margin-bottom: 5px; font-weight: 700;">Hora:</label>
                        <input type="time" name="hora" class="modern-input" required>
                    </div>
                </div>

                <button type="submit" id="btn-confirmar-cita" class="ghost-btn"
                    style="width: 100%; padding: 15px; border-radius: 12px; font-weight: 800;">
                    <i class="fa-solid fa-floppy-disk"></i> Confirmar Cita
                </button>
            </form>
        </div>
    </div>
    {{-- Modal de Edición (Readonly: Nombre, Apellidos, Fecha Nac, Tipo Sangre) --}}
    <div id="modal-edit-patient" class="modal-overlay">
        <div class="modal-glass modal-lg" style="width: 820px; max-width: 95vw; max-height: 90vh; overflow-y: auto;">
            <button class="close-modal" onclick="closeModal('modal-edit-patient')">&times;</button>
            <h3 style="color: #f59e0b; margin-bottom: 20px; text-align: left; font-weight: 800; font-size: 1.6rem;">
                <i class="fa-solid fa-user-pen" style="margin-right: 8px;"></i> Editar Datos del Paciente
            </h3>

            <form id="form-edit-patient" method="POST">
                @csrf
                @method('PUT')
                <div class="form-grid">
                    <div class="full-width"
                        style="font-size: 0.78em; font-weight: 700; color: #f59e0b; text-transform: uppercase; border-bottom: 2px solid #fef3c7; padding-bottom: 6px; margin-bottom: 5px;">
                        <i class="fa-solid fa-id-card" style="margin-right: 5px;"></i> Datos Personales (Bloqueados)
                    </div>

                    <input type="text" id="edit-nombre" class="modern-input" placeholder="Nombre(s)" readonly
                        style="background: #f3f4f6; color: #6b7280; cursor: not-allowed;">
                    <input type="text" id="edit-apellidos" class="modern-input" placeholder="Apellidos" readonly
                        style="background: #f3f4f6; color: #6b7280; cursor: not-allowed;">
                    <input type="text" id="edit-fecha-nac" class="modern-input" placeholder="Fecha Nacimiento" readonly
                        style="background: #f3f4f6; color: #6b7280; cursor: not-allowed;">
                    <input type="text" id="edit-tipo-sangre" class="modern-input" placeholder="Tipo Sangre" readonly
                        style="background: #f3f4f6; color: #6b7280; cursor: not-allowed;">

                    <div class="full-width"
                        style="font-size: 0.78em; font-weight: 700; color: var(--primary-color); text-transform: uppercase; border-bottom: 2px solid #e0fbfc; padding-bottom: 6px; margin-top: 15px; margin-bottom: 5px;">
                        <i class="fa-solid fa-pen" style="margin-right: 5px;"></i> Datos Editables
                    </div>

                    <input type="email" name="email" id="edit-email" class="modern-input" placeholder="Email (Usuario App)*"
                        required>
                    <input type="text" name="telefono" id="edit-telefono" class="modern-input"
                        placeholder="Teléfono (+codigo)*" required maxlength="15"
                        oninput="this.value=this.value.replace(/[^0-9+]/g,'')">
                    <input type="number" name="peso" id="edit-peso" class="modern-input" placeholder="Peso (kg enteros)"
                        step="1" min="0" max="500" oninput="this.value=this.value.replace(/[^0-9]/g,'')">
                    <input type="text" name="direccion" id="edit-direccion" class="modern-input"
                        placeholder="Dirección Completa" style="grid-column: span 1;">
                    <input type="text" name="ocupacion" id="edit-ocupacion" class="modern-input" placeholder="Ocupación">

                    <div class="full-width"
                        style="font-size: 0.78em; font-weight: 700; color: #ef4444; text-transform: uppercase; border-bottom: 2px solid #fee2e2; padding-bottom: 6px; margin-top: 12px; margin-bottom: 5px;">
                        <i class="fa-solid fa-phone-volume" style="margin-right: 5px;"></i> Contacto de Emergencia
                    </div>
                    <input type="text" name="emergencia_nombre" id="edit-em-nombre" class="modern-input"
                        placeholder="Nombre Contacto"
                        oninput="this.value=this.value.replace(/[^A-Za-zÀ-ÿÑñ ]/g,'').replace(/  +/g,' ')">
                    <input type="text" name="emergencia_apellido_paterno" id="edit-em-paterno" class="modern-input"
                        placeholder="Apellido Paterno"
                        oninput="this.value=this.value.replace(/[^A-Za-zÀ-ÿÑñ ]/g,'').replace(/  +/g,' ')">
                    <input type="text" name="emergencia_apellido_materno" id="edit-em-materno" class="modern-input"
                        placeholder="Apellido Materno"
                        oninput="this.value=this.value.replace(/[^A-Za-zÀ-ÿÑñ ]/g,'').replace(/  +/g,' ')">
                    <input type="text" name="emergencia_telefono" id="edit-em-telefono" class="modern-input"
                        placeholder="Teléfono Emergencia (+codigo)" maxlength="15"
                        oninput="this.value=this.value.replace(/[^0-9+]/g,'')">

                    <div class="full-width"
                        style="font-size: 0.78em; font-weight: 700; color: #f59e0b; text-transform: uppercase; border-bottom: 2px solid #fef3c7; padding-bottom: 6px; margin-top: 12px; margin-bottom: 5px;">
                        <i class="fa-solid fa-notes-medical" style="margin-right: 5px;"></i> Información de Salud
                    </div>
                    <div class="full-width">
                        <textarea name="enfermedades_cronicas" id="edit-enfermedades" class="modern-input" rows="2"
                            placeholder="Enfermedades Crónicas (Texto libre)"></textarea>
                    </div>
                    <div class="full-width">
                        <textarea name="alergias" id="edit-alergias" class="modern-input" rows="2"
                            placeholder="Alergias a Medicamentos (Texto libre)"></textarea>
                    </div>
                </div>

                <div style="text-align: center; margin-top: 25px;">
                    <button type="submit" class="ghost-btn"
                        style="width: 100%; padding: 15px; font-size: 1.1rem; border-radius: 12px; font-weight: 800; background: #f59e0b; color: white; border: none;">
                        <i class="fa-solid fa-floppy-disk"></i> Guardar Cambios
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Formulario para Delete (oculto) --}}
    <form id="form-delete-paciente" method="POST" style="display: none;">
        @csrf
        @method('DELETE')
    </form>

@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        let currentPaciente = null;

        document.getElementById('btn-registrar-paciente').addEventListener('click', function () {
            Swal.fire({
                title: '¿Estás seguro?',
                text: "Estás a punto de registrar un nuevo paciente. Revisa que los datos médicos e información personal ingresada sean correctos.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#00b4d8',
                cancelButtonColor: '#ef4444',
                confirmButtonText: 'Sí, registrar paciente',
                cancelButtonText: 'Cancelar y revisar'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('btn-submit-real-paciente').click();
                }
            });
        });

        // ─── Buscador en Tiempo Real ───────────────────────────────────────────
        document.getElementById('patient-search').addEventListener('input', function (e) {
            const query = e.target.value.toLowerCase();
            const cards = document.querySelectorAll('.patient-card');

            cards.forEach(card => {
                const text = card.textContent.toLowerCase();
                card.style.display = text.includes(query) ? 'flex' : 'none';
            });
        });

        // ─── Perfil del Paciente ────────────────────────────────────────────────
        function verPerfil(paciente) {
            currentPaciente = paciente;
            showTab('tab-general');

            document.getElementById('p-name').innerText = `${paciente.nombre} ${paciente.apellido_paterno} ${paciente.apellido_materno || ''}`;
            document.getElementById('p-email').innerText = paciente.correo_electronico || 'Sin correo';
            document.getElementById('p-tel').innerText = paciente.telefono || 'Sin teléfono';

            document.getElementById('view-edad').innerText = calcularEdad(paciente.fecha_nacimiento);
            document.getElementById('view-sexo').innerText = paciente.sexo === 'M' ? 'Masculino' : (paciente.sexo === 'F' ? 'Femenino' : 'Otro');
            document.getElementById('view-sangre').innerText = paciente.tipo_sangre || 'S/D';
            document.getElementById('view-peso').innerText = (paciente.peso || '0') + ' kg';
            document.getElementById('view-ocupacion').innerText = paciente.ocupacion || 'S/D';
            document.getElementById('view-enfermedades').innerText = paciente.enfermedades_cronicas || 'Ninguna registrada';

            // Foto
            const fotoUrl = paciente.archivos ? paciente.archivos.find(a => a.tipo === 'imagen') : null;
            if (fotoUrl) {
                document.getElementById('p-foto').src = '/storage/' + fotoUrl.url_archivo.replace('public/', '');
                document.getElementById('p-foto').style.display = 'block';
                document.getElementById('p-foto-placeholder').style.display = 'none';
            } else {
                document.getElementById('p-foto').style.display = 'none';
                document.getElementById('p-foto-placeholder').style.display = 'flex';
            }

            // Contacto de Emergencia
            const ce = paciente.contacto_emergencia;
            if (ce) {
                document.getElementById('view-emergencia-nombre').innerText = `${ce.nombre} ${ce.paterno || ''}`;
                document.getElementById('view-emergencia-tel').innerText = ce.numero_telefono || 'S/D';
            } else {
                document.getElementById('view-emergencia-nombre').innerText = 'No registrado';
                document.getElementById('view-emergencia-tel').innerText = '---';
            }

            // Alergias
            const badges = document.getElementById('view-alergias-badges');
            if (paciente.alergias) {
                badges.innerHTML = `<span style="background:#ef4444; color:white; border-radius:20px; padding:5px 15px; font-size:0.9em; font-weight:700;">
                                                                <i class="fa-solid fa-pills"></i> ${paciente.alergias}</span>`;
            } else {
                badges.innerHTML = '<span style="color: #6c757d; font-style: italic;">Sin alergias registradas</span>';
            }

            openModal('modal-patient-profile');
        }

        function calcularEdad(fecha) {
            if (!fecha) return 'S/D';
            const birth = new Date(fecha);
            const now = new Date();
            let age = now.getFullYear() - birth.getFullYear();
            const month = now.getMonth() - birth.getMonth();
            if (month < 0 || (month === 0 && now.getDate() < birth.getDate())) age--;
            return age + ' años';
        }

        // ─── Tabs ─────────────────────────────────────────────────────────────
        function showTab(id) {
            document.querySelectorAll('[id^="tab-"]').forEach(t => t.style.display = 'none');
            document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('tab-active'));

            document.getElementById(id).style.display = 'block';
            document.getElementById('btn-' + id).classList.add('tab-active');

            if (id === 'tab-evolucion') cargarEvoluciones();
            if (id === 'tab-historial') cargarHistorialCitas();
        }

        async function uploadFoto(input) {
            if (!currentPaciente || !input.files[0]) return;
            const formData = new FormData();
            formData.append('foto', input.files[0]);

            Swal.fire({
                title: 'Subiendo foto...',
                text: 'Por favor espera...',
                allowOutsideClick: false,
                didOpen: () => Swal.showLoading()
            });

            try {
                const res = await fetch(`/api/pacientes/${currentPaciente.id_paciente}/foto`, {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    body: formData
                });
                const json = await res.json();
                if (json.success) {
                    Swal.fire('¡Éxito!', 'Foto actualizada correctamente.', 'success');
                    document.getElementById('p-foto').src = json.url;
                    document.getElementById('p-foto').style.display = 'block';
                    document.getElementById('p-foto-placeholder').style.display = 'none';
                } else {
                    Swal.fire('Error', json.message, 'error');
                }
            } catch (e) {
                console.error(e);
                Swal.fire('Error', 'Ocurrió un problema al subir la foto.', 'error');
            }
        }

        async function cargarHistorialCitas() {
            if (!currentPaciente) return;
            const list = document.getElementById('historial-citas-list');
            list.innerHTML = '<div style="text-align:center;color:#888;padding:20px;"><i class="fa-solid fa-spinner fa-spin"></i> Cargando historial...</div>';

            try {
                const res = await fetch(`/api/pacientes/${currentPaciente.id_paciente}/citas`);
                const json = await res.json();

                if (!json.data || json.data.length === 0) {
                    list.innerHTML = '<p style="text-align:center;color:#888;padding:20px;"><i class="fa-solid fa-calendar-xmark"></i> Sin citas previas.</p>';
                    return;
                }

                list.innerHTML = json.data.map(cita => {
                    const statusClass = cita.estado_cita === 'completada' ? 'color:#10b981;' : (cita.estado_cita === 'cancelada' ? 'color:#ef4444;' : 'color:#f59e0b;');
                    return `<div style="background:white; border:1px solid #e2e8f0; border-radius:12px; padding:16px; display: flex; justify-content: space-between; align-items: center;">
                                    <div>
                                        <h5 style="margin:0 0 5px 0; font-size: 1rem; color: #2b2d42;">${cita.servicio ? cita.servicio.nombre_servicio : 'Consulta General'}</h5>
                                        <span style="font-size: 0.85rem; color: #6c757d;"><i class="fa-solid fa-calendar-day"></i> ${new Date(cita.fecha_hora_inicio).toLocaleString('es-MX', { dateStyle: 'long', timeStyle: 'short' })}</span>
                                        ${cita.notas ? `<p style="margin:5px 0 0 0; font-size:0.85rem; color:#555;"><i>"${cita.notas}"</i></p>` : ''}
                                    </div>
                                    <div style="text-align: right;">
                                        <span style="font-weight: 800; font-size: 0.85em; text-transform: uppercase; ${statusClass}">${cita.estado_cita}</span>
                                    </div>
                                </div>`;
                }).join('');
            } catch (e) {
                list.innerHTML = '<p style="text-align:center;color:#ef4444;"><i class="fa-solid fa-triangle-exclamation"></i> Error al cargar historial.</p>';
            }
        }

        async function cargarEvoluciones() {
            if (!currentPaciente) return;
            const list = document.getElementById('evolucion-list');
            list.innerHTML = '<div style="text-align:center;color:#888;padding:20px;"><i class="fa-solid fa-spinner fa-spin"></i> Cargando...</div>';

            try {
                const res = await fetch(`/api/pacientes/${currentPaciente.id_paciente}/evoluciones`);
                const json = await res.json();

                if (!json.data || json.data.length === 0) {
                    list.innerHTML = '<p style="text-align:center;color:#888;padding:20px;"><i class="fa-solid fa-inbox"></i> Sin evoluciones registradas.</p>';
                    return;
                }

                list.innerHTML = json.data.map(ev => {
                    const fecha = new Date(ev.fecha_evolucion).toLocaleDateString('es-MX', { day: '2-digit', month: 'short', year: 'numeric' });

                    let imageHtml = '';
                    if (ev.imagenes && ev.imagenes.length > 0) {
                        const imgUrl = '/storage/' + ev.imagenes[0].ruta_imagen;
                        imageHtml = `<div style="margin-top: 15px; border-radius: 8px; overflow: hidden; border: 1px solid #e2e8f0; display: inline-block;">
                                            <a href="${imgUrl}" target="_blank" title="Ver imagen completa">
                                                <img src="${imgUrl}" alt="Evidencia" style="max-width: 200px; max-height: 150px; display: block; object-fit: cover;">
                                            </a>
                                         </div>`;
                    }

                    return `<div style="background:white;border:1px solid #e2e8f0;border-radius:14px;padding:18px;border-left:4px solid var(--primary-color);">
                                                    <div style="display:flex;justify-content:space-between;margin-bottom:8px;">
                                                        <span style="font-size:0.8em;font-weight:700;color:var(--primary-color);text-transform:uppercase;"><i class="fa-solid fa-calendar-days"></i> ${fecha}</span>
                                                    </div>
                                                    <p style="margin:0;color:#333;line-height:1.6;">${ev.descripcion_avance || 'Sin descripción.'}</p>
                                                    ${ev.plan_tratamiento ? `<p style="margin:8px 0 0;font-size:0.88em;color:#666;"><strong>Plan:</strong> ${ev.plan_tratamiento}</p>` : ''}
                                                    ${imageHtml}
                                                </div>`;
                }).join('');
            } catch (e) {
                list.innerHTML = '<p style="text-align:center;color:#ef4444;"><i class="fa-solid fa-triangle-exclamation"></i> Error al cargar evoluciones.</p>';
            }
        }

        async function guardarEvolucion() {
            if (!currentPaciente) return;
            const texto = document.getElementById('nueva-evolucion-texto').value.trim();
            const fileInput = document.getElementById('nueva-evolucion-foto');

            if (!texto) {
                Swal.fire('Atención', 'Escribe una descripción antes de guardar.', 'warning');
                return;
            }

            const formData = new FormData();
            formData.append('descripcion_avance', texto);
            if (fileInput.files.length > 0) {
                formData.append('imagen', fileInput.files[0]);
            }

            const btn = document.getElementById('btn-submit-evolucion');
            const originalBtnHtml = btn.innerHTML;
            btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Guardando...';
            btn.disabled = true;

            try {
                const res = await fetch(`/api/pacientes/${currentPaciente.id_paciente}/evoluciones`, {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    body: formData
                });
                const json = await res.json();

                if (json.success) {
                    document.getElementById('nueva-evolucion-texto').value = '';
                    fileInput.value = '';
                    document.getElementById('label-ev-foto').innerText = 'Adjuntar Evidencia (Foto)';
                    document.getElementById('label-ev-foto').style.color = '#00b4d8';
                    cargarEvoluciones();

                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: 'success',
                        title: 'Nota guardada',
                        showConfirmButton: false,
                        timer: 2000
                    });
                } else {
                    Swal.fire('Error al guardar', json.message || 'Intenta de nuevo.', 'error');
                }
            } catch (e) {
                Swal.fire('Error', 'Error de conexión. Intenta de nuevo.', 'error');
            } finally {
                btn.innerHTML = originalBtnHtml;
                btn.disabled = false;
            }
        }

        function abrirModalCita() {
            if (!currentPaciente) return;
            document.getElementById('form-cita-paciente-id').value = currentPaciente.id_paciente;
            document.getElementById('form-cita-paciente-nombre').value = `${currentPaciente.nombre} ${currentPaciente.apellido_paterno}`;
            openModal('modal-add-cita');
        }

        // ─── CRUD (Edit / Delete) ────────────────────────────────────────────────
        function abrirModalEdit() {
            if (!currentPaciente) return;
            closeModal('modal-patient-profile'); // Cierra perfil
            openModal('modal-edit-patient'); // Abre edicion

            document.getElementById('form-edit-patient').action = `/pacientes/${currentPaciente.id_paciente}`;

            // Campos de Solo Lectura
            document.getElementById('edit-nombre').value = currentPaciente.nombre;
            document.getElementById('edit-apellidos').value = `${currentPaciente.apellido_paterno} ${currentPaciente.apellido_materno || ''}`;
            document.getElementById('edit-fecha-nac').value = currentPaciente.fecha_nacimiento || '';
            document.getElementById('edit-tipo-sangre').value = currentPaciente.tipo_sangre || 'N/A';

            // Campos Editables
            document.getElementById('edit-email').value = currentPaciente.correo_electronico || '';
            document.getElementById('edit-telefono').value = currentPaciente.telefono || '';
            document.getElementById('edit-peso').value = currentPaciente.peso || '';
            document.getElementById('edit-direccion').value = currentPaciente.direccion || '';
            document.getElementById('edit-ocupacion').value = currentPaciente.ocupacion || '';
            document.getElementById('edit-enfermedades').value = currentPaciente.enfermedades_cronicas || '';
            document.getElementById('edit-alergias').value = currentPaciente.alergias || '';

            // Contacto Emergencia
            const ce = currentPaciente.contacto_emergencia;
            if (ce) {
                document.getElementById('edit-em-nombre').value = ce.nombre || '';
                document.getElementById('edit-em-paterno').value = ce.apellido_paterno || '';
                document.getElementById('edit-em-materno').value = ce.apellido_materno || '';
                document.getElementById('edit-em-telefono').value = ce.numero_telefono || '';
            }
        }

        function eliminarPaciente() {
            if (!currentPaciente) return;

            Swal.fire({
                title: '¡ALERTA ROJA!',
                text: `Estás a punto de ELIMINAR por completo al paciente ${currentPaciente.nombre}. Esta acción es destructiva y limitará el acceso a su historial. ¿Deseas proceder?`,
                icon: 'error',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Sí, ELIMINAR PACIENTE',
                cancelButtonText: 'Cancelar',
                background: '#fee2e2',
                color: '#991b1b',
                iconColor: '#dc2626'
            }).then((result) => {
                if (result.isConfirmed) {
                    const form = document.getElementById('form-delete-paciente');
                    form.action = `/pacientes/${currentPaciente.id_paciente}`;
                    form.submit();
                }
            });
        }

        // ─── Protección anti doble submit en el formulario de cita ───────────
        document.getElementById('form-add-cita').addEventListener('submit', function () {
            const btn = document.getElementById('btn-confirmar-cita');
            if (btn) {
                btn.disabled = true;
                btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Guardando...';
                btn.style.opacity = '0.7';
                btn.style.cursor = 'not-allowed';
            }
        });

        // ─── Auto-abrir modal si hay errores de validación ──────────────
        @if($errors->any())
            openModal('modal-new-patient');
        @endif
    </script>
@endsection