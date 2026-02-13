@extends('layouts.app')

@section('titulo', 'Panel Principal')

@section('contenido')
    <h2 class="page-title">Panel Principal</h2>

    <div style="background: white; padding: 25px; border-radius: 15px; box-shadow: var(--shadow);">
        <h3 style="margin-bottom: 20px;">Próximas Citas Pendientes</h3>
        <div class="appointment-list" style="display: flex; flex-direction: column; gap: 15px;">
            @forelse($proximasCitas as $cita)
                <div class="appointment-card clickable" onclick="cargarModalCita({{ $cita->id_cita }})"
                    style="cursor: pointer; border: 1px solid #eee; transition: 0.2s; padding: 25px; border-radius: 12px; width: 90%;">

                    <div style="display: flex; align-items: center; gap: 20px;">
                        <div
                            style="background: #e0fbfc; padding: 12px 18px; border-radius: 12px; text-align: center; min-width: 70px;">
                            <span style="display: block; font-weight: 800; color: var(--primary-color); font-size: 1.4em;">
                                {{ \Carbon\Carbon::parse($cita->fecha_hora_inicio)->format('d') }}
                            </span>
                            <small style="color: #555; font-weight: 700; text-transform: uppercase; font-size: 0.85em;">
                                {{ \Carbon\Carbon::parse($cita->fecha_hora_inicio)->format('M') }}
                            </small>
                        </div>

                        <div style="flex: 1; overflow: hidden;">
                            <h4
                                style="margin: 0 0 8px 0; font-size: 1.4em; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; color: #333;">
                                {{ $cita->paciente->nombre }} {{ $cita->paciente->apellido_paterno }}
                            </h4>
                            <div style="display: flex; align-items: center; gap: 10px; color: #555; font-size: 1.1em;">
                                <i class="fa-regular fa-clock" style="color: var(--primary-color);"></i>
                                {{ \Carbon\Carbon::parse($cita->fecha_hora_inicio)->format('h:i A') }}
                                <span style="margin: 0 5px; color: #ddd;">|</span>
                                <span style="color: var(--secondary-color); font-weight: 600;">
                                    {{ $cita->servicio ? $cita->servicio->nombre_servicio : 'Consulta General' }}
                                </span>
                            </div>
                        </div>

                        <div style="text-align: right; padding-right: 15px;">
                            <i class="fa-solid fa-chevron-right" style="color: #ccc; font-size: 1.2em;"></i>
                        </div>
                    </div>
                </div>
            @empty
                <p style="grid-column: 1 / -1; text-align: center; color: #888; padding: 30px;">No hay citas próximas agendadas.
                </p>
            @endforelse
        </div>
    </div>

    <div class="modal-overlay" id="modal-detalle-cita">
        <div class="modal-glass modal-xl"
            style="background: #F8FDFF; padding: 0; max-width: 1600px; width: 95vw; height: 90vh; display: flex; overflow: hidden; border-radius: 20px; border: 1px solid #dceeef;">

            <div
                style="width: 35%; background: #E0FBFC; padding: 30px; display: flex; flex-direction: column; border-right: 2px solid #bcebf5; overflow-y: auto;">

                <h2 style="margin-top: 0; color: #000; margin-bottom: 20px; font-weight: 800;">Calendario</h2>

                <div
                    style="background: white; padding: 20px; border-radius: 16px; width: 100%; box-shadow: 0 4px 15px rgba(0,0,0,0.05); margin-bottom: auto; box-sizing: border-box;">

                    <div
                        style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px; background: #F8F9FA; padding: 8px; border-radius: 10px;">
                        <button class="ghost-btn"
                            style="padding: 5px 10px; background: transparent; color: #666; min-width: 30px;"
                            onclick="cambiarMes(-1)">
                            <i class="fa-solid fa-chevron-left"></i>
                        </button>

                        <span id="cal-mes-anio"
                            style="font-weight: 700; color: var(--primary-color); font-size: 0.95em;">Cargando...</span>

                        <button class="ghost-btn"
                            style="padding: 5px 10px; background: transparent; color: #666; min-width: 30px;"
                            onclick="cambiarMes(1)">
                            <i class="fa-solid fa-chevron-right"></i>
                        </button>
                    </div>

                    <div class="calendar-grid-functional"
                        style="display: grid; grid-template-columns: repeat(7, 1fr); gap: 8px; text-align: center; font-size: 0.85em;">
                        <span style="color:#aaa; font-weight:600; font-size: 0.8em; margin-bottom: 5px;">D</span>
                        <span style="color:#aaa; font-weight:600; font-size: 0.8em; margin-bottom: 5px;">L</span>
                        <span style="color:#aaa; font-weight:600; font-size: 0.8em; margin-bottom: 5px;">M</span>
                        <span style="color:#aaa; font-weight:600; font-size: 0.8em; margin-bottom: 5px;">M</span>
                        <span style="color:#aaa; font-weight:600; font-size: 0.8em; margin-bottom: 5px;">J</span>
                        <span style="color:#aaa; font-weight:600; font-size: 0.8em; margin-bottom: 5px;">V</span>
                        <span style="color:#aaa; font-weight:600; font-size: 0.8em; margin-bottom: 5px;">S</span>

                        <div id="functional-calendar-days" style="display: contents;"></div>
                    </div>

                    <div
                        style="margin-top: 20px; display: flex; gap: 12px; justify-content: center; font-size: 0.7em; color: #666;">
                        <div style="display:flex; align-items:center;"><span
                                style="width:8px;height:8px;background:#32D74B;border-radius:50%;margin-right:4px;"></span>Libre
                        </div>
                        <div style="display:flex; align-items:center;"><span
                                style="width:8px;height:8px;background:#FFC107;border-radius:50%;margin-right:4px;"></span>Ocupado
                        </div>
                        <div style="display:flex; align-items:center;"><span
                                style="width:8px;height:8px;background:#EF4444;border-radius:50%;margin-right:4px;"></span>Lleno
                        </div>
                    </div>
                </div>

                <div style="width: 100%; display: flex; flex-direction: column; gap: 12px; margin-top: 25px;">
                    <button class="ghost-btn" onclick="switchTab('tab-horario')"
                        style="background: white; color: black; border: 2px solid #00D1FF; justify-content: center; font-weight: 700; border-radius: 10px; padding: 12px; cursor: pointer;">Horario</button>
                    <button class="ghost-btn" onclick="switchTab('tab-seguimiento')"
                        style="background: white; color: black; border: 2px solid #00D1FF; justify-content: center; font-weight: 700; border-radius: 10px; padding: 12px; cursor: pointer;">Seguimiento</button>
                    <button class="ghost-btn" onclick="switchTab('tab-pago')"
                        style="background: white; color: black; border: 2px solid #00D1FF; justify-content: center; font-weight: 700; border-radius: 10px; padding: 12px; cursor: pointer;">Pago
                        de hoy</button>

                    <button class="ghost-btn" id="btn-actualizar-cita"
                        style="background: #00D1FF; color: white; border: none; font-weight: 800; justify-content: center;
                                                                        margin-top: 10px; padding: 14px; box-shadow: 0 5px 15px rgba(0, 209, 255, 0.3); border-radius: 10px;">
                        GUARDAR CAMBIOS
                    </button>

                </div>
            </div>

            <!-- Formulario Principal -->
            <form id="form-actualizar-cita" method="POST"
                style="width: 68%; padding: 40px; position: relative; overflow-y: auto; display: flex; flex-direction: column;">
                @csrf

                <button type="button" class="close-modal" onclick="closeModal('modal-detalle-cita')"
                    style="position: absolute; top: 25px; right: 25px; font-size: 1.5rem; background: #f0f0f0; width: 40px; height: 40px; border-radius: 50%; color: #555; border: none; cursor: pointer;">&times;</button>

                <!-- TAB 1: RESUMEN (Visible por defecto) -->
                <div id="tab-resumen" class="tab-content active"
                    style="height: 100%; display: flex; flex-direction: column;">
                    <h1 style="font-size: 2.2rem; font-weight: 800; margin: 0 0 30px 0; color: #000;">Detalles del Paciente
                    </h1>

                    <div
                        style="background: white; padding: 25px; border-radius: 15px; box-shadow: 0 2px 10px rgba(0,0,0,0.03); margin-bottom: 30px; border: 1px solid #eee;">
                        <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px;">
                            <div><small style="font-weight:700; color:#555;">Nombre(s):</small>
                                <div id="lbl-nombre" style="font-size:1.1em;">...</div>
                            </div>
                            <div><small style="font-weight:700; color:#555;">Apellido Paterno:</small>
                                <div id="lbl-paterno" style="font-size:1.1em;">...</div>
                            </div>
                            <div><small style="font-weight:700; color:#555;">Apellido Materno:</small>
                                <div id="lbl-materno" style="font-size:1.1em;">...</div>
                            </div>
                            <div><small style="font-weight:700; color:#555;">Edad:</small>
                                <div id="lbl-edad" style="font-size:1.1em;">...</div>
                            </div>

                            <div><small style="font-weight:700; color:#555;">Sexo:</small>
                                <div id="lbl-sexo" style="font-size:1.1em;">...</div>
                            </div>
                            <div><small style="font-weight:700; color:#555;">Teléfono:</small>
                                <div id="lbl-telefono" style="font-size:1.1em;">...</div>
                            </div>
                            <div><small style="font-weight:700; color:#555;">Tipo Sangre:</small>
                                <div id="lbl-sangre" style="font-weight:800; color: var(--primary-color);">...</div>
                            </div>
                            <div><small style="font-weight:700; color:#555;">Peso:</small>
                                <div id="lbl-peso">...</div>
                            </div>

                            <div style="grid-column: span 2;">
                                <small style="font-weight:700; color:#ef4444;"><i
                                        class="fa-solid fa-triangle-exclamation"></i>
                                    Alergias:</small>
                                <div id="lbl-alergias" style="color:#ef4444;">...</div>
                            </div>
                            <div style="grid-column: span 2;">
                                <small style="font-weight:700; color:#ef4444;"><i class="fa-solid fa-notes-medical"></i>
                                    Enfermedades Crónicas:</small>
                                <div id="lbl-enfermedades" style="color:#ef4444;">...</div>
                            </div>
                        </div>
                    </div>

                    <div style="border: 2px solid #00D1FF; border-radius: 8px; overflow: hidden; margin-bottom: auto;">
                        <table style="width: 100%; border-collapse: collapse; text-align: center;">
                            <thead style="background: #CCFBFD;">
                                <tr>
                                    <th
                                        style="padding: 15px; border-right: 2px solid #00D1FF; color:#000; font-weight: 700;">
                                        Día</th>
                                    <th
                                        style="padding: 15px; border-right: 2px solid #00D1FF; color:#000; font-weight: 700;">
                                        Hora</th>
                                    <th
                                        style="padding: 15px; border-right: 2px solid #00D1FF; color:#000; font-weight: 700;">
                                        Seguimiento</th>
                                    <th style="padding: 15px; color:#000; font-weight: 700;">Abono</th>
                                </tr>
                            </thead>
                            <tbody style="background: white;">
                                <tr>
                                    <td style="padding: 18px; border-right: 2px solid #00D1FF; border-bottom: 2px solid #00D1FF; font-size:1.1em;"
                                        id="td-dia">...</td>
                                    <td style="padding: 18px; border-right: 2px solid #00D1FF; border-bottom: 2px solid #00D1FF; font-size:1.1em;"
                                        id="td-hora">...</td>
                                    <td style="padding: 18px; border-right: 2px solid #00D1FF; border-bottom: 2px solid #00D1FF; font-size:1.1em;"
                                        id="td-seguimiento">...</td>
                                    <td style="padding: 18px; border-bottom: 2px solid #00D1FF; font-size:1.1em;"
                                        id="td-abono">
                                        ...</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div
                        style="display: flex; gap: 20px; align-items: center; margin-top: 30px; justify-content: flex-end; flex-wrap: wrap;">
                        <div
                            style="font-size: 1.5rem; font-weight: 700; color: #000; display: flex; align-items: center; white-space: nowrap;">
                            Total:
                            <span
                                style="background: #FFFFFF; padding: 8px 20px; border-radius: 8px; border: 2px solid #00D1FF; margin-left: 10px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); min-width: 120px; text-align: center;">
                                <span id="lbl-total">0.00</span>
                            </span>
                        </div>

                        <div
                            style="font-size: 1.5rem; font-weight: 700; color: #000; display: flex; align-items: center; white-space: nowrap;">
                            Restante:
                            <span style="margin-left: 10px; font-weight: 700;">
                                <span id="lbl-restante">0.00</span>
                            </span>
                        </div>
                    </div>
                </div>

                <!-- TAB 2: HORARIO -->
                <div id="tab-horario" class="tab-content" style="display: none; height: 100%;">
                    <h2 style="color: var(--primary-color); font-weight: 800; font-size: 2rem; margin-bottom: 10px;">
                        Reprogramar Cita</h2>
                    <p style="color: #666; margin-bottom: 30px; font-size: 1.1rem;">Selecciona la nueva fecha y hora para la
                        cita.</p>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                        <div style="display: flex; flex-direction: column; gap: 8px;">
                            <label style="font-weight: 700; color: #444;">Nueva Fecha</label>
                            <input type="date" name="nueva_fecha"
                                style="padding: 12px; border: 1px solid #ccc; border-radius: 8px; font-size: 1rem;">
                        </div>
                        <div style="display: flex; flex-direction: column; gap: 8px;">
                            <label style="font-weight: 700; color: #444;">Nueva Hora</label>
                            <input type="time" name="nueva_hora"
                                style="padding: 12px; border: 1px solid #ccc; border-radius: 8px; font-size: 1rem;">
                        </div>
                    </div>
                    <button type="button"
                        style="background: #eee; color: #555; margin-top: auto; padding: 12px; border: none; border-radius: 8px; font-weight: 700; cursor: pointer; align-self: flex-start;"
                        onclick="switchTab('tab-resumen')">Cancelar / Volver</button>
                </div>

                <!-- TAB 3: SEGUIMIENTO -->
                <div id="tab-seguimiento" class="tab-content" style="display: none; height: 100%;">
                    <h2 style="color: var(--primary-color); font-weight: 800; font-size: 2rem; margin-bottom: 10px;">
                        Seguimiento Clínico</h2>
                    <p style="color: #666; margin-bottom: 30px; font-size: 1.1rem;">Registra avances o notas importantes
                        sobre esta cita.</p>

                    <div style="display: flex; flex-direction: column; gap: 8px; flex: 1;">
                        <label style="font-weight: 700; color: #444;">Notas / Observaciones</label>
                        <textarea name="notas_seguimiento"
                            style="padding: 15px; border: 1px solid #ccc; border-radius: 8px; font-size: 1rem; resize: none; flex: 1;"
                            placeholder="Escribe aquí los detalles del tratamiento..."></textarea>
                    </div>
                    <button type="button"
                        style="background: #eee; color: #555; margin-top: 20px; padding: 12px; border: none; border-radius: 8px; font-weight: 700; cursor: pointer; align-self: flex-start;"
                        onclick="switchTab('tab-resumen')">Cancelar / Volver</button>
                </div>

                <!-- TAB 4: PAGO -->
                <div id="tab-pago" class="tab-content" style="display: none; height: 100%;">
                    <h2 style="color: var(--primary-color); font-weight: 800; font-size: 2rem; margin-bottom: 10px;">
                        Registrar Pago</h2>
                    <p style="color: #666; margin-bottom: 30px; font-size: 1.1rem;">Ingresa el monto abonado hoy por el
                        paciente.</p>

                    <div style="display: flex; flex-direction: column; gap: 8px;">
                        <label style="font-weight: 700; color: #444;">Monto a Abonar ($)</label>
                        <input type="number" name="monto_abono"
                            style="padding: 15px; border: 1px solid #ccc; border-radius: 8px; font-size: 1.5rem; font-weight: bold;"
                            step="0.01" min="0" placeholder="0.00">
                    </div>
                    <button type="button"
                        style="background: #eee; color: #555; margin-top: auto; padding: 12px; border: none; border-radius: 8px; font-weight: 700; cursor: pointer; align-self: flex-start;"
                        onclick="switchTab('tab-resumen')">Cancelar / Volver</button>
                </div>

            </form>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        // ==========================================
        // aqui va ek calendario
        // ==========================================
        let calMesActual = new Date().getMonth() + 1;
        let calAnioActual = new Date().getFullYear();
        const monthNames = ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"];

        function cargarCalendarioFuncional(mes, anio) {
            document.getElementById('cal-mes-anio').innerText = `${monthNames[mes - 1]} ${anio}`;
            const grid = document.getElementById('functional-calendar-days');
            grid.innerHTML = '<div style="grid-column:span 7; text-align:center; padding:20px;"><i class="fa-solid fa-spinner fa-spin"></i></div>';

            fetch(`/api/calendario/disponibilidad?mes=${mes}&anio=${anio}`)
                .then(res => res.json())
                .then(disponibilidad => {
                    grid.innerHTML = '';

                    const primerDiaSemana = new Date(anio, mes - 1, 1).getDay();
                    for (let i = 0; i < primerDiaSemana; i++) grid.appendChild(document.createElement('div'));

                    for (const [dia, data] of Object.entries(disponibilidad)) {
                        let div = document.createElement('div');
                        div.innerText = dia;
                        div.style.padding = '8px 5px';
                        div.style.borderRadius = '8px';
                        div.style.fontWeight = '600';
                        div.style.fontSize = '0.9em';
                        div.style.transition = '0.2s';

                        if (data.estado === 'verde') {
                            div.style.background = '#32D74B'; div.style.color = 'white';
                        } else if (data.estado === 'amarillo') {
                            div.style.background = '#FFC107'; div.style.color = '#333';
                        } else if (data.estado === 'rojo') {
                            div.style.background = '#EF4444'; div.style.color = 'white';
                        } else {
                            div.style.background = '#f0f0f0'; div.style.color = '#ccc';
                        }

                        if (data.clickable) {
                            div.style.cursor = 'pointer';
                            div.onclick = () => abrirModalAgendar(dia, mes, anio);
                            div.onmouseover = () => div.style.transform = 'scale(1.1)';
                            div.onmouseout = () => div.style.transform = 'scale(1)';
                        } else {
                            div.style.cursor = 'not-allowed';
                        }
                        grid.appendChild(div);
                    }
                });
        }

        function cambiarMes(delta) {
            calMesActual += delta;
            if (calMesActual > 12) { calMesActual = 1; calAnioActual++; }
            if (calMesActual < 1) { calMesActual = 12; calAnioActual--; }
            cargarCalendarioFuncional(calMesActual, calAnioActual);
        }

        function abrirModalAgendar(dia, mes, anio) {
            alert(`Seleccionar fecha: ${dia}/${mes}/${anio}`);
        }

        // ==========================================
        // LÓGICA DE CARGA DE DATOS DE LA CITA
        // ==========================================
        function cargarModalCita(idCita) {
            openModal('modal-detalle-cita');
            document.getElementById('form-actualizar-cita').action = `/citas/${idCita}/actualizar`;

            // Loader visual
            document.getElementById('lbl-nombre').innerText = 'Cargando...';

            fetch(`/api/citas/${idCita}/modal-detalles`)
                .then(res => res.json())
                .then(data => {
                    // 1. Datos Paciente
                    document.getElementById('lbl-nombre').innerText = data.paciente.nombres;
                    document.getElementById('lbl-paterno').innerText = data.paciente.paterno;
                    document.getElementById('lbl-materno').innerText = data.paciente.materno;
                    document.getElementById('lbl-edad').innerText = data.paciente.edad;
                    document.getElementById('lbl-sexo').innerText = data.paciente.sexo;
                    document.getElementById('lbl-telefono').innerText = data.paciente.telefono;

                    if (document.getElementById('lbl-sangre')) document.getElementById('lbl-sangre').innerText = data.paciente.tipo_sangre;
                    if (document.getElementById('lbl-peso')) document.getElementById('lbl-peso').innerText = data.paciente.peso;
                    if (document.getElementById('lbl-alergias')) document.getElementById('lbl-alergias').innerText = data.paciente.alergias;
                    if (document.getElementById('lbl-enfermedades')) document.getElementById('lbl-enfermedades').innerText = data.paciente.enfermedades;

                    // 2. Tabla
                    document.getElementById('td-dia').innerText = data.fila_tabla.dia;
                    document.getElementById('td-hora').innerText = data.fila_tabla.hora;
                    document.getElementById('td-seguimiento').innerText = data.fila_tabla.seguimiento;
                    document.getElementById('td-abono').innerText = '$' + data.fila_tabla.abono;

                    // 3. Totales
                    document.getElementById('lbl-total').innerText = '$' + data.finanzas.total;
                    document.getElementById('lbl-restante').innerText = '$' + data.finanzas.restante;

                    // 4. Calendario
                    if (data.fecha_cita) {
                        calMesActual = data.fecha_cita.mes;
                        calAnioActual = data.fecha_cita.anio;
                        cargarCalendarioFuncional(calMesActual, calAnioActual);
                    }
                })
                .catch(err => {
                    console.error("Error:", err);
                    document.getElementById('lbl-nombre').innerText = 'Error al cargar';
                });
        }

        // ==========================================
        // AJAX FORM SUBMISSION
        // ==========================================
        // Trigger submit when clicking the external button
        document.getElementById('btn-actualizar-cita').addEventListener('click', function () {
            document.getElementById('form-actualizar-cita').requestSubmit();
        });

        document.getElementById('form-actualizar-cita').addEventListener('submit', function (e) {
            e.preventDefault();

            const form = e.target;
            const formData = new FormData(form);
            const actionUrl = form.action;

            // Visual feedback (optional)
            const btn = document.getElementById('btn-actualizar-cita'); // ID assigned to "GUARDAR CAMBIOS"
            const originalText = btn.innerText;
            btn.innerText = 'Guardando...';
            btn.disabled = true;

            fetch(actionUrl, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Update UI Elements
                        document.getElementById('lbl-total').innerText = data.data.total_abonado;
                        document.getElementById('lbl-restante').innerText = data.data.restante;
                        document.getElementById('td-abono').innerText = data.data.abono_fila;

                        if (data.data.nueva_fecha) document.getElementById('td-dia').innerText = data.data.nueva_fecha;
                        if (data.data.nueva_hora) document.getElementById('td-hora').innerText = data.data.nueva_hora;
                        if (data.data.seguimiento) document.getElementById('td-seguimiento').innerText = data.data.seguimiento;

                        // Show success
                        alert('¡Actualizado correctamente!');

                        // Clear inputs
                        document.querySelector('input[name="monto_abono"]').value = '';
                        document.querySelector('textarea[name="notas_seguimiento"]').value = '';
                    } else {
                        alert('Error: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Ocurrió un error inesperado.');
                })
                .finally(() => {
                    btn.innerText = originalText;
                    btn.disabled = false;
                });
        });



        function switchTab(tabId) {
            // Ocultar todos los tabs
            document.querySelectorAll('.tab-content').forEach(tab => {
                tab.style.display = 'none';
            });

            // Mostrar el seleccionado
            const tab = document.getElementById(tabId);
            if (tab) {
                tab.style.display = (tabId === 'tab-resumen') ? 'flex' : 'block';
                if (tabId === 'tab-resumen') tab.style.flexDirection = 'column';
            }
        }
    </script>
@endsection