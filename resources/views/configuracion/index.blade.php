@extends('layouts.app')

@section('titulo', 'Configuración')

@section('contenido')
    <div class="header-section" style="margin-bottom: 30px;">
        <h2 class="page-title">Configuración de la Clínica</h2>
        <p style="color: #666;">Gestiona la información de tu consultorio y equipo de trabajo.</p>
    </div>

    @if(session('success'))
        <div style="background: #d4edda; color: #155724; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
            <i class="fa-solid fa-check-circle"></i> {{ session('success') }}
        </div>
    @endif

    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(400px, 1fr)); gap: 30px;">

        <div class="config-card"
            style="background: white; padding: 25px; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.05);">
            <h3 style="color: #0077b6; border-bottom: 2px solid #f0f0f0; padding-bottom: 10px; margin-top: 0;">
                <i class="fa-solid fa-hospital"></i> Datos de la Clínica
            </h3>
            <form action="{{ route('configuracion.updateClinica') }}" method="POST">
                @csrf
                <div style="margin-bottom: 15px;">
                    <label>Nombre Comercial</label>
                    <input type="text" name="nombre_comercial" value="{{ $clinica->nombre_comercial }}" class="modern-input"
                        required oninput="this.value=this.value.replace(/[^A-Za-zÀ-ÿÑñ ]/g,'').replace(/  +/g,' ')">
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                    <div>
                        <label>Teléfono</label>
                        <input type="text" name="numero_telefono" value="{{ $clinica->numero_telefono }}"
                            class="modern-input" maxlength="12" oninput="this.value=this.value.replace(/[^0-9]/g,'')">
                    </div>
                    <div>
                        <label>Ciudad / Localidad</label>
                        <input type="text" name="localidad" value="{{ $clinica->localidad }}" class="modern-input"
                            oninput="this.value=this.value.replace(/[^A-Za-zÀ-ÿÑñ ]/g,'').replace(/  +/g,' ')">
                    </div>
                </div>

                <div style="margin-top: 15px;">
                    <label>Estado / Provincia</label>
                    <input type="text" name="estado" value="{{ $clinica->estado }}" class="modern-input"
                        oninput="this.value=this.value.replace(/[^A-Za-zÀ-ÿÑñ ]/g,'').replace(/  +/g,' ')">
                </div>

                <button type="submit" class="ghost-btn"
                    style="margin-top: 20px; background: #00b4d8; color: white; width: 100%;">
                    Guardar Cambios Clínica
                </button>
            </form>
        </div>

        @if($doctorUser)
            <div class="config-card"
                style="background: white; padding: 25px; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.05);">
                <h3 style="color: #0077b6; border-bottom: 2px solid #f0f0f0; padding-bottom: 10px; margin-top: 0;">
                    <i class="fa-solid fa-user-doctor"></i> Perfil del Doctor
                </h3>
                <form action="{{ route('configuracion.updateUsuario') }}" method="POST">
                    @csrf
                    <input type="hidden" name="id_usuario" value="{{ $doctorUser->id_usuario }}">

                    <div style="margin-bottom: 15px;">
                        <label>Nombre Completo</label>
                        <input type="text" name="nombre_completo" value="{{ $doctorUser->nombre_completo }}"
                            class="modern-input"
                            oninput="this.value=this.value.replace(/[^A-Za-zÀ-ÿÑñ ]/g,'').replace(/  +/g,' ')" required>
                    </div>

                    <div style="margin-bottom: 15px;">
                        <label>Correo Electrónico (Acceso)</label>
                        <input type="email" name="email" value="{{ $doctorUser->email }}" class="modern-input" required>
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                        <div>
                            <label>Cédula Profesional</label>
                            <input type="text" name="cedula_profesional" value="{{ $doctorPerfil->cedula_profesional ?? '' }}"
                                class="modern-input" placeholder="Ej: 12345678">
                        </div>
                        <div>
                            <label>Cambiar Contraseña</label>
                            <input type="password" name="password" class="modern-input" placeholder="Opcional">
                        </div>
                    </div>

                    <div style="margin-top: 15px;">
                        <label>Horario de Atención (Texto)</label>
                        <input type="text" name="horario_default" value="{{ $doctorPerfil->horario_default ?? '' }}"
                            class="modern-input" placeholder="Ej: Lun-Vie 9am a 6pm">
                    </div>

                    <button type="submit" class="ghost-btn"
                        style="margin-top: 20px; background: #0077b6; color: white; width: 100%;">
                        Actualizar Perfil Doctor
                    </button>
                </form>
            </div>
        @endif

        @if(Auth::user()->rol == 'doctor' || Auth::user()->rol == 'admin')
            <div class="config-card"
                style="grid-column: 1 / -1; background: white; padding: 25px; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.05);">
                <div
                    style="display: flex; justify-content: space-between; align-items: center; border-bottom: 2px solid #f0f0f0; padding-bottom: 10px; margin-bottom: 20px;">
                    <h3 style="color: #0077b6; margin: 0;">
                        <i class="fa-solid fa-users"></i> Equipo de Recepción
                    </h3>
                    <button onclick="document.getElementById('modal-recep').style.display='flex'" class="ghost-btn"
                        style="padding: 5px 15px; font-size: 0.8rem;">
                        + Agregar Recepcionista
                    </button>
                </div>

                @if($recepcionistas->count() > 0)
                    <div style="display: grid; gap: 15px;">
                        @foreach($recepcionistas as $recep)
                            <div
                                style="display: flex; justify-content: space-between; align-items: center; background: #f9f9f9; padding: 15px; border-radius: 8px;">
                                <div>
                                    <strong style="display: block; color: #333;">{{ $recep->nombre_completo }}</strong>
                                    <span style="color: #666; font-size: 0.9rem;">{{ $recep->email }}</span>
                                </div>
                                <button
                                    onclick="editarRecep('{{ $recep->id_usuario }}', '{{ $recep->nombre_completo }}', '{{ $recep->email }}')"
                                    class="ghost-btn" style="background: #e0e0e0; color: #333; padding: 5px 10px;">
                                    <i class="fa-solid fa-pen"></i> Editar
                                </button>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p style="text-align: center; color: #999;">No hay recepcionistas registradas.</p>
                @endif
            </div>
        @endif

    </div>

    <div id="modal-recep"
        style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 1000; justify-content: center; align-items: center;">
        <div style="background: white; padding: 30px; border-radius: 15px; width: 400px;">
            <h3 style="margin-top: 0; color: #00b4d8;">Nueva Recepcionista</h3>
            <form action="{{ route('configuracion.storeRecepcionista') }}" method="POST">
                @csrf
                <label style="display:block; margin-bottom:5px;">Nombre</label>
                <input type="text" name="nombre_completo" class="modern-input" required
                    style="width:100%; margin-bottom:10px;"
                    oninput="this.value=this.value.replace(/[^A-Za-zÀ-ÿÑñ ]/g,'').replace(/  +/g,' ')">

                <label style="display:block; margin-bottom:5px;">Email</label>
                <input type="email" name="email" class="modern-input" required style="width:100%; margin-bottom:10px;">

                <label style="display:block; margin-bottom:5px;">Contraseña</label>
                <input type="password" name="password" class="modern-input" required
                    style="width:100%; margin-bottom:20px;">

                <div style="text-align: right;">
                    <button type="button" onclick="document.getElementById('modal-recep').style.display='none'"
                        style="padding: 10px 20px; border: none; background: #eee; cursor: pointer; border-radius: 5px;">Cancelar</button>
                    <button type="submit"
                        style="padding: 10px 20px; border: none; background: #00b4d8; color: white; cursor: pointer; border-radius: 5px;">Guardar</button>
                </div>
            </form>
        </div>
    </div>

    <div id="modal-edit-recep"
        style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 1000; justify-content: center; align-items: center;">
        <div style="background: white; padding: 30px; border-radius: 15px; width: 400px;">
            <h3 style="margin-top: 0; color: #0077b6;">Editar Recepcionista</h3>
            <form action="{{ route('configuracion.updateUsuario') }}" method="POST">
                @csrf
                <input type="hidden" name="id_usuario" id="edit_id">

                <label style="display:block; margin-bottom:5px;">Nombre</label>
                <input type="text" name="nombre_completo" id="edit_nombre" class="modern-input" required
                    style="width:100%; margin-bottom:10px;"
                    oninput="this.value=this.value.replace(/[^A-Za-zÀ-ÿÑñ ]/g,'').replace(/  +/g,' ')">

                <label style="display:block; margin-bottom:5px;">Email</label>
                <input type="email" name="email" id="edit_email" class="modern-input" required
                    style="width:100%; margin-bottom:10px;">

                <label style="display:block; margin-bottom:5px;">Nueva Contraseña (Opcional)</label>
                <input type="password" name="password" class="modern-input" style="width:100%; margin-bottom:20px;"
                    placeholder="Dejar vacío para no cambiar">

                <div style="text-align: right;">
                    <button type="button" onclick="document.getElementById('modal-edit-recep').style.display='none'"
                        style="padding: 10px 20px; border: none; background: #eee; cursor: pointer; border-radius: 5px;">Cancelar</button>
                    <button type="submit"
                        style="padding: 10px 20px; border: none; background: #0077b6; color: white; cursor: pointer; border-radius: 5px;">Actualizar</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Script simple para abrir el modal de edición con datos
        function editarRecep(id, nombre, email) {
            document.getElementById('edit_id').value = id;
            document.getElementById('edit_nombre').value = nombre;
            document.getElementById('edit_email').value = email;
            document.getElementById('modal-edit-recep').style.display = 'flex';
        }

        // Cerrar modales al hacer clic fuera
        window.onclick = function (event) {
            if (event.target == document.getElementById('modal-recep')) {
                document.getElementById('modal-recep').style.display = "none";
            }
            if (event.target == document.getElementById('modal-edit-recep')) {
                document.getElementById('modal-edit-recep').style.display = "none";
            }
        }
    </script>

    <style>
        .modern-input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 8px;
            background: #fafafa;
        }

        label {
            font-weight: 500;
            font-size: 0.9rem;
            color: #555;
            margin-bottom: 5px;
            display: block;
        }
    </style>
@endsection