@extends('layouts.app')

@section('titulo', 'Panel Principal')

@section('contenido')
    <h2 class="page-title">Panel Principal</h2>

    {{-- =====================================================================
    SECCIÓN DE MÉTRICAS RÁPIDAS
    ====================================================================== --}}
    <div
        style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin-bottom: 25px;">

        {{-- Tarjeta: Pacientes totales --}}
        <div
            style="background: white; border-radius: 15px; padding: 22px 25px; box-shadow: var(--shadow); display: flex; align-items: center; gap: 18px; border-left: 5px solid var(--primary-color);">
            <div
                style="background: #e0fbfc; border-radius: 12px; width: 52px; height: 52px; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                <i class="fa-solid fa-users" style="color: var(--primary-color); font-size: 1.4em;"></i>
            </div>
            <div>
                <div style="font-size: 1.8em; font-weight: 800; color: #333; line-height: 1;">{{ $totalPacientes }}</div>
                <div style="color: #888; font-size: 0.85em; margin-top: 3px;">Pacientes activos</div>
            </div>
        </div>

        {{-- Tarjeta: Citas de hoy --}}
        <div
            style="background: white; border-radius: 15px; padding: 22px 25px; box-shadow: var(--shadow); display: flex; align-items: center; gap: 18px; border-left: 5px solid #4CAF50;">
            <div
                style="background: #e8f5e9; border-radius: 12px; width: 52px; height: 52px; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                <i class="fa-solid fa-calendar-check" style="color: #4CAF50; font-size: 1.4em;"></i>
            </div>
            <div>
                <div style="font-size: 1.8em; font-weight: 800; color: #333; line-height: 1;">{{ $citasHoyCount }}</div>
                <div style="color: #888; font-size: 0.85em; margin-top: 3px;">Citas pendientes hoy</div>
            </div>
        </div>

        {{-- Tarjeta: Ingresos del mes --}}
        <div
            style="background: white; border-radius: 15px; padding: 22px 25px; box-shadow: var(--shadow); display: flex; align-items: center; gap: 18px; border-left: 5px solid #FF9800;">
            <div
                style="background: #fff3e0; border-radius: 12px; width: 52px; height: 52px; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                <i class="fa-solid fa-peso-sign" style="color: #FF9800; font-size: 1.4em;"></i>
            </div>
            <div>
                <div id="lbl-ingresos-mes" style="font-size: 1.8em; font-weight: 800; color: #333; line-height: 1;">
                    ${{ number_format($ingresosMes, 0) }}</div>
                <div style="color: #888; font-size: 0.85em; margin-top: 3px;">Ingresos este mes</div>
            </div>
        </div>

        {{-- Tarjeta: Notificaciones --}}
        @if($notificacionesPendientes > 0)
            <div
                style="background: white; border-radius: 15px; padding: 22px 25px; box-shadow: var(--shadow); display: flex; align-items: center; gap: 18px; border-left: 5px solid #9c27b0;">
                <div
                    style="background: #f3e5f5; border-radius: 12px; width: 52px; height: 52px; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                    <i class="fa-solid fa-bell" style="color: #9c27b0; font-size: 1.4em;"></i>
                </div>
                <div>
                    <div style="font-size: 1.8em; font-weight: 800; color: #333; line-height: 1;">
                        {{ $notificacionesPendientes }}
                    </div>
                    <div style="color: #888; font-size: 0.85em; margin-top: 3px;">Notificaciones pendientes</div>
                </div>
            </div>
        @endif

    </div>


    <div style="background: white; padding: 25px; border-radius: 15px; box-shadow: var(--shadow);">
        <h3 style="margin-bottom: 20px;">Próximas Citas Pendientes</h3>
        <div class="appointment-list" id="appointment-list" style="display: flex; flex-direction: column; gap: 15px;">
            @forelse($proximasCitas as $cita)
                @php
                    $esVencida = \Carbon\Carbon::parse($cita->fecha_hora_inicio)->isPast();
                @endphp
                <div class="appointment-card" id="cita-card-{{ $cita->id_cita }}" data-id="{{ $cita->id_cita }}"
                    style="position: relative; border: 1px solid {{ $esVencida ? '#FCD34D' : '#eee' }}; background: {{ $esVencida ? '#FFFBF0' : '#fff' }}; padding: 20px 25px; border-radius: 12px; width: 90%;">

                    {{-- Overlay de checkmark (oculto por default) --}}
                    <div class="check-overlay" id="overlay-{{ $cita->id_cita }}"
                        style="display: none; position: absolute; top: 0; left: 0; right: 0; bottom: 0; background: rgba(240,255,246,0.97); border-radius: 12px; z-index: 10; flex-direction: column; align-items: center; justify-content: center; gap: 8px;">
                        <svg width="60" height="60" viewBox="0 0 60 60">
                            <circle cx="30" cy="30" r="28" fill="#22C55E" />
                            <path id="check-path-{{ $cita->id_cita }}" d="M16 30 L26 42 L44 20" stroke="white" stroke-width="5"
                                fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-dasharray="55"
                                stroke-dashoffset="55" style="transition: stroke-dashoffset 0.6s ease 0.15s;" />
                        </svg>
                        <span style="font-weight: 800; color: #15803D; font-size: 1.05em;">¡Cita completada!</span>
                    </div>

                    <div style="display: flex; align-items: center; gap: 20px;">
                        {{-- Bloque fecha --}}
                        <div
                            style="background: {{ $esVencida ? '#FEF3C7' : '#e0fbfc' }}; padding: 12px 18px; border-radius: 12px; text-align: center; min-width: 70px; flex-shrink: 0;">
                            <span
                                style="display: block; font-weight: 800; color: {{ $esVencida ? '#B45309' : 'var(--primary-color)' }}; font-size: 1.4em;">
                                {{ \Carbon\Carbon::parse($cita->fecha_hora_inicio)->format('d') }}
                            </span>
                            <small style="color: #555; font-weight: 700; text-transform: uppercase; font-size: 0.85em;">
                                {{ \Carbon\Carbon::parse($cita->fecha_hora_inicio)->format('M') }}
                            </small>
                        </div>

                        {{-- Info --}}
                        <div style="flex: 1; overflow: hidden;">
                            <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 6px;">
                                <h4
                                    style="margin: 0; font-size: 1.3em; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; color: #333;">
                                    {{ $cita->paciente->nombre }} {{ $cita->paciente->apellido_paterno }}
                                </h4>
                                @if($esVencida)
                                    <span
                                        style="background: #FEF3C7; color: #B45309; font-size: 0.7em; font-weight: 800; padding: 2px 9px; border-radius: 20px; border: 1px solid #FCD34D; white-space: nowrap; flex-shrink: 0;">
                                        ⚠ VENCIDA
                                    </span>
                                @endif
                            </div>
                            <div style="display: flex; align-items: center; gap: 10px; color: #555; font-size: 1em;">
                                <i class="fa-regular fa-clock"
                                    style="color: {{ $esVencida ? '#D97706' : 'var(--primary-color)' }};"></i>
                                {{ \Carbon\Carbon::parse($cita->fecha_hora_inicio)->format('h:i A') }}
                                <span style="margin: 0 5px; color: #ddd;">|</span>
                                <span style="color: var(--secondary-color); font-weight: 600;">
                                    {{ $cita->servicio ? $cita->servicio->nombre_servicio : 'Consulta General' }}
                                </span>
                            </div>
                        </div>

                        {{-- Botones --}}
                        <div style="display: flex; align-items: center; gap: 10px; flex-shrink: 0;">
                            <button type="button" id="btn-completar-{{ $cita->id_cita }}"
                                onclick="completarCita({{ $cita->id_cita }})"
                                style="background: #22C55E; color: white; border: none; border-radius: 8px; padding: 9px 16px; font-size: 0.85em; font-weight: 700; cursor: pointer; display: flex; align-items: center; gap: 6px; box-shadow: 0 2px 8px rgba(34,197,94,0.25); transition: opacity 0.2s, transform 0.1s;"
                                onmouseover="this.style.opacity='0.85';this.style.transform='scale(1.03)'"
                                onmouseout="this.style.opacity='1';this.style.transform='scale(1)'">
                                <i class="fa-regular fa-circle-check" style="font-size:1em;"></i>
                                Marcar completada
                            </button>
                            <button type="button" onclick="cargarModalCita({{ $cita->id_cita }})"
                                style="background: transparent; border: 1px solid #ddd; border-radius: 8px; padding: 9px 13px; cursor: pointer; color: #666; transition: background 0.2s;"
                                onmouseover="this.style.background='#f5f5f5'" onmouseout="this.style.background='transparent'">
                                <i class="fa-solid fa-chevron-right" style="font-size: 0.9em;"></i>
                            </button>
                        </div>
                    </div>
                </div>
            @empty
                <p style="text-align: center; color: #888; padding: 30px;">No hay citas próximas agendadas.</p>
            @endforelse
        </div>
    </div>

    <div class="modal-overlay" id="modal-detalle-cita">
        <div class="modal-glass modal-xl"
            style="background: #F8FDFF; padding: 0; max-width: 1750px; width: 98vw; height: 95vh; display: flex; overflow: hidden; border-radius: 20px; border: 1px solid #dceeef;">

            <div
                style="width: 30%; background: #E0FBFC; padding: 30px; display: flex; flex-direction: column; border-right: 2px solid #bcebf5; overflow-y: auto;">

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
                    <button class="ghost-btn" onclick="openWidget('widget-seguimiento')"
                        style="background: white; color: black; border: 2px solid #00D1FF; justify-content: center; font-weight: 700; border-radius: 10px; padding: 12px; cursor: pointer;">Seguimiento</button>
                    <button type="button" class="ghost-btn" onclick="openWidget('widget-pago')"
                        style="background: white; color: black; border: 2px solid #00D1FF; justify-content: center; font-weight: 700; border-radius: 10px; padding: 12px; cursor: pointer;">Pago
                        de hoy</button>

                    <button type="button" class="ghost-btn" onclick="switchTab('tab-odontograma')"
                        style="background: white; color: black; border: 2px solid #00D1FF; justify-content: center; font-weight: 700; border-radius: 10px; padding: 12px; cursor: pointer;">
                        Odontograma
                    </button>

                    <button class="ghost-btn" id="btn-actualizar-cita"
                        style="background: #00D1FF; color: white; border: none; font-weight: 800; justify-content: center;
                                                                                                                                                                                    margin-top: 10px; padding: 14px; box-shadow: 0 5px 15px rgba(0, 209, 255, 0.3); border-radius: 10px;">
                        GUARDAR CAMBIOS
                    </button>

                </div>
            </div>

            <!-- Formulario Principal -->
            <form id="form-actualizar-cita" method="POST" onsubmit="return false;"
                style="width: 70%; padding: 40px; position: relative; overflow-y: auto; display: flex; flex-direction: column;">
                @csrf

                <button type="button" class="close-modal" onclick="closeModal('modal-detalle-cita')"
                    style="position: absolute; top: 25px; right: 25px; font-size: 1.5rem; background: #f0f0f0; width: 40px; height: 40px; border-radius: 50%; color: #555; border: none; cursor: pointer; z-index: 5;">&times;</button>

                <!-- OVERLAY PARA WIDGETS INTERNOS -->
                <div id="internal-widget-overlay"
                    style="display: none; position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.4); z-index: 50; border-radius: 20px;">
                </div>

                <!-- TAB 1: RESUMEN (Visible por defecto) -->
                <div id="tab-resumen" class="tab-content active"
                    style="min-height: 100%; display: flex; flex-direction: column; flex: 1 0 auto; padding-bottom: 20px;">
                    <h1 style="font-size: 2.2rem; font-weight: 800; margin: 0 0 30px 0; color: #000; flex-shrink: 0;">
                        Detalles del Paciente
                    </h1>

                    <div
                        style="background: white; padding: 25px; border-radius: 15px; box-shadow: 0 2px 10px rgba(0,0,0,0.03); margin-bottom: 30px; border: 1px solid #eee; flex-shrink: 0;">
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

                    <div
                        style="border: 2px solid #00D1FF; border-radius: 8px; overflow: hidden; margin-bottom: auto; flex-shrink: 0;">
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
                            <tbody id="cita-tabla-body" style="background: white;">
                                {{-- JS renderiza aquí todas las filas del historial --}}
                            </tbody>
                        </table>

                        <!-- Controles de paginación -->
                        <div id="paginacion-controles"
                            style="display: flex; justify-content: center; align-items: center; gap: 15px; padding: 15px; background: #f8f9fa; border-top: 2px solid #00D1FF;">
                            <button type="button" id="btn-pag-anterior" onclick="cambiarPagina(-1)"
                                style="background: var(--primary-color); color: white; border: none; padding: 8px 16px; border-radius: 6px; cursor: pointer; font-weight: 600;">
                                <i class="fa-solid fa-chevron-left"></i> Anterior
                            </button>
                            <span id="info-paginacion" style="font-weight: 600; color: #333;">Página 1 de 1</span>
                            <button type="button" id="btn-pag-siguiente" onclick="cambiarPagina(1)"
                                style="background: var(--primary-color); color: white; border: none; padding: 8px 16px; border-radius: 6px; cursor: pointer; font-weight: 600;">
                                Siguiente <i class="fa-solid fa-chevron-right"></i>
                            </button>
                        </div>
                    </div>

                    <div
                        style="display: flex; gap: 20px; align-items: center; margin-top: 30px; justify-content: flex-end; flex-wrap: wrap; flex-shrink: 0;">
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
                            <span style="font-weight: 700; font-size: 1em;">Restante:</span>
                            <span style="margin-left: 10px; font-weight: 700;">
                                <span id="lbl-restante">0.00</span>
                            </span>
                        </div>
                    </div>

                    <!-- Datos Ocultos para cálculos Matemáticos Crudos -->
                    <input type="hidden" id="raw-costo-total" value="0">
                    <input type="hidden" id="raw-total-abonado" value="0">
                </div>

                <!-- WIDGET 2: HORARIO (Aparece sobre Resumen/Odontograma) -->
                <div id="widget-horario" class="inner-widget"
                    style="display: none; position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); background: white; padding: 40px; border-radius: 16px; box-shadow: 0 10px 30px rgba(0,0,0,0.15); z-index: 100; width: 80%; max-width: 500px;">
                    <h2 style="color: var(--primary-color); font-weight: 800; font-size: 2rem; margin-bottom: 10px;">
                        Reprogramar Cita</h2>
                    <p style="color: #666; margin-bottom: 30px; font-size: 1.1rem;">Selecciona la nueva fecha y hora para la
                        cita.</p>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                        <div style="display: flex; flex-direction: column; gap: 8px;">
                            <label style="font-weight: 700; color: #444;">Nueva Fecha</label>
                            <input type="date" name="nueva_fecha" id="input-nueva-fecha"
                                style="padding: 12px; border: 1px solid #ccc; border-radius: 8px; font-size: 1rem;">
                        </div>
                        <div style="display: flex; flex-direction: column; gap: 8px;">
                            <label style="font-weight: 700; color: #444;">Nueva Hora</label>
                            <input type="time" name="nueva_hora"
                                style="padding: 12px; border: 1px solid #ccc; border-radius: 8px; font-size: 1rem;">
                        </div>
                    </div>
                    <button type="button"
                        style="background: #eee; color: #555; margin-top: 30px; padding: 12px; width: 100%; border: none; border-radius: 8px; font-weight: 700; cursor: pointer; text-align: center;"
                        onclick="closeWidgets()">Confirmar / Volver</button>
                </div>

                <!-- WIDGET 3: SEGUIMIENTO -->
                <div id="widget-seguimiento" class="inner-widget"
                    style="display: none; position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); background: white; padding: 40px; border-radius: 16px; box-shadow: 0 10px 30px rgba(0,0,0,0.15); z-index: 100; width: 80%; max-width: 600px;">
                    <h2 style="color: var(--primary-color); font-weight: 800; font-size: 2rem; margin-bottom: 10px;">
                        Seguimiento Clínico</h2>
                    <p style="color: #666; margin-bottom: 30px; font-size: 1.1rem;">Tratamiento que se realizara la proxima
                        cita.</p>

                    <div style="display: flex; flex-direction: column; gap: 8px;">
                        <label style="font-weight: 700; color: #444;">Notas / Observaciones</label>
                        <textarea name="notas_seguimiento"
                            style="padding: 15px; border: 1px solid #ccc; border-radius: 8px; font-size: 1rem; resize: none; min-height: 150px;"
                            placeholder="Escribe aquí los detalles del tratamiento..."></textarea>
                    </div>
                    <button type="button"
                        style="background: #eee; color: #555; margin-top: 30px; padding: 12px; width: 100%; border: none; border-radius: 8px; font-weight: 700; cursor: pointer; text-align: center;"
                        onclick="closeWidgets()">Confirmar / Volver</button>
                </div>

                <!-- WIDGET 4: PAGO -->
                <div id="widget-pago" class="inner-widget"
                    style="display: none; position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); background: white; padding: 40px; border-radius: 16px; box-shadow: 0 10px 30px rgba(0,0,0,0.15); z-index: 100; width: 80%; max-width: 400px;">
                    <h2 style="color: var(--primary-color); font-weight: 800; font-size: 2rem; margin-bottom: 10px;">
                        Registrar Pago</h2>
                    <p style="color: #666; margin-bottom: 30px; font-size: 1.1rem;">Ingresa el monto abonado hoy por el
                        paciente.</p>

                    <div style="display: flex; flex-direction: column; gap: 8px;">
                        <label style="font-weight: 700; color: #444;">Monto a Abonar en esta Cita ($)</label>
                        <input type="number" name="monto_abono" id="input-monto-abono"
                            style="padding: 15px; border: 1px solid #ccc; border-radius: 8px; font-size: 1.5rem; font-weight: bold;"
                            step="0.01" min="0" placeholder="0.00" oninput="if(this.value < 0) { this.value = 0; }">
                    </div>
                    <button type="button"
                        style="background: #eee; color: #555; margin-top: 30px; padding: 12px; width: 100%; border: none; border-radius: 8px; font-weight: 700; cursor: pointer; text-align: center;"
                        onclick="closeWidgets()">Confirmar / Volver</button>
                </div>

                <!-- TAB O: ODONTOGRAMA -->
                <div id="tab-odontograma" class="tab-content" style="display: none; height: 100%;">
                    <div
                        style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 10px;">
                        <div>
                            <h2 style="color: var(--primary-color); font-weight: 800; font-size: 2rem; margin: 0;">
                                Odontograma Digital</h2>
                            <p style="color: #666; margin-top: 5px; font-size: 1.1rem;">Selecciona una herramienta y haz
                                clic en las piezas dentales.</p>
                        </div>
                        <button type="button" class="ghost-btn" onclick="switchTab('tab-resumen')"
                            style="background: #f0f0f0; border: 1px solid #ccc; color: #333; font-weight: 700; padding: 10px 20px; border-radius: 8px; cursor: pointer;">
                            <i class="fa-solid fa-arrow-left"></i> Volver a Detalles
                        </button>
                    </div>

                    <svg style="display: none;">
                        <defs>
                            <symbol id="tooth-incisor" viewBox="0 0 80 120">
                                <path
                                    d="M20 15 Q40 0 60 15 L65 45 Q60 75 50 85 L45 110 Q40 118 35 110 L30 85 Q20 75 15 45 Z"
                                    fill="#f8f1e4" stroke="#222" stroke-width="2" />
                            </symbol>
                            <symbol id="tooth-canine" viewBox="0 0 80 130">
                                <path
                                    d="M20 25 Q40 0 60 25 L65 50 Q55 80 50 90 L45 120 Q40 128 35 120 L30 90 Q20 80 15 50 Z"
                                    fill="#f8f1e4" stroke="#222" stroke-width="2" />
                            </symbol>
                            <symbol id="tooth-premolar" viewBox="0 0 90 130">
                                <path
                                    d="M20 30 Q45 5 70 30 L75 60 Q70 80 60 90 L55 115 Q45 125 35 115 L30 90 Q20 80 15 60 Z"
                                    fill="#f8f1e4" stroke="#222" stroke-width="2" />
                            </symbol>
                            <symbol id="tooth-molar-upper" viewBox="0 0 110 140">
                                <path d="M20 40 Q55 5 90 40 L85 70 Q80 90 65 100 Q55 105 45 100 Q30 90 25 70 Z"
                                    fill="#f8f1e4" stroke="#222" stroke-width="2" />
                                <path d="M40 100 L30 130 Q45 135 50 110 Z" fill="#f8f1e4" stroke="#222" stroke-width="2" />
                                <path d="M70 100 L80 130 Q65 135 60 110 Z" fill="#f8f1e4" stroke="#222" stroke-width="2" />
                                <path d="M55 100 L50 135 Q60 138 58 110 Z" fill="#f8f1e4" stroke="#222" stroke-width="2" />
                            </symbol>
                            <symbol id="tooth-molar-lower" viewBox="0 0 110 140">
                                <path d="M20 40 Q55 5 90 40 L85 75 Q75 95 55 105 Q35 95 25 75 Z" fill="#f8f1e4"
                                    stroke="#222" stroke-width="2" />
                                <path d="M45 105 L35 135 Q50 140 55 115 Z" fill="#f8f1e4" stroke="#222" stroke-width="2" />
                                <path d="M65 105 L75 135 Q60 140 55 115 Z" fill="#f8f1e4" stroke="#222" stroke-width="2" />
                            </symbol>
                        </defs>
                    </svg>

                    <div
                        style="background: #f8f9fa; padding: 15px; border-radius: 12px; display: flex; gap: 20px; align-items: flex-end; margin-bottom: 20px; border: 1px solid #ddd;">
                        <div style="flex: 1;">
                            <label style="font-weight: 700; color: #444; display: block; margin-bottom: 5px;">Tratamiento a
                                aplicar:</label>
                            <select id="select-servicio"
                                style="width: 100%; padding: 10px; border-radius: 8px; border: 1px solid #ccc;">
                                <option value="">-- Seleccionar --</option>
                                @foreach($servicios as $srv)
                                    <option value="{{ $srv->id_servicio }}">{{ $srv->nombre_servicio }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div style="display: flex; gap: 15px;">
                            <label style="cursor: pointer; display: flex; align-items: center; gap: 5px;">
                                <input type="radio" name="tipo_registro" value="hallazgo" id="tipo-hallazgo" checked>
                                <span style="color: blue; font-weight: 700;">Hallazgo (Azul)</span>
                            </label>
                            <label style="cursor: pointer; display: flex; align-items: center; gap: 5px;">
                                <input type="radio" name="tipo_registro" value="tratamiento" id="tipo-tratamiento">
                                <span style="color: red; font-weight: 700;">Plan/Realizado (Rojo)</span>
                            </label>
                        </div>
                    </div>

                    <style>
                        .odontograma-lienzo {
                            display: flex;
                            flex-direction: column;
                            gap: 30px;
                            padding: 20px;
                            background: #fff;
                            border-radius: 12px;
                            border: 2px dashed #ccc;
                            flex: 1;
                            overflow-x: auto;
                            min-height: 400px;
                        }

                        .fila-dientes {
                            display: flex;
                            justify-content: space-between;
                            gap: 5px;
                            min-width: 600px;
                        }

                        .fila-dientes.centrada {
                            justify-content: center;
                            gap: 15px;
                        }

                        .diente-wrapper {
                            display: flex;
                            flex-direction: column;
                            align-items: center;
                            width: 50px;
                        }

                        .fila-dientes.temporales .diente-wrapper {
                            width: 38px;
                        }

                        .numero-diente {
                            color: #5bc0be;
                            font-size: 13px;
                            font-weight: bold;
                            margin: 4px 0;
                        }

                        .caras-interactivas {
                            width: 40px;
                            height: 40px;
                            border-radius: 50%;
                            overflow: hidden;
                            border: 1px solid #999;
                            cursor: pointer;
                        }

                        .odontograma-svg {
                            width: 100%;
                            height: 100%;
                        }

                        .cara-diente {
                            fill: #ffffff;
                            stroke: #999;
                            stroke-width: 2;
                            transition: fill 0.2s;
                            cursor: pointer;
                        }

                        .cara-diente:hover {
                            fill: #f0f0f0;
                        }

                        .anatomia {
                            width: 100%;
                            height: 55px;
                            display: flex;
                            justify-content: center;
                            align-items: flex-end;
                        }

                        .anatomia svg {
                            height: 100%;
                            width: auto;
                        }

                        .diente-wrapper.superior .anatomia svg {
                            transform: scale(1, -1);
                        }
                    </style>

                    <div id="odontograma-lienzo" class="odontograma-lienzo">
                        <div id="fila-perm-sup" class="fila-dientes superior"></div>
                        <div id="fila-temp-sup" class="fila-dientes centrada temporales superior"></div>
                        <div id="fila-temp-inf" class="fila-dientes centrada temporales inferior"></div>
                        <div id="fila-perm-inf" class="fila-dientes inferior"></div>
                    </div>

                    <input type="hidden" id="odontograma-paciente-id" value="">
                    <input type="hidden" id="odontograma-paciente-edad" value="0">
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
        // Fecha original de la cita actualmente abierta (se llena al cargar la cita)
        let fechaCitaActual = null;
        const monthNames = ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"];

        function cargarCalendarioFuncional(mes, anio) {
            document.getElementById('cal-mes-anio').innerText = `${monthNames[mes - 1]} ${anio}`;
            const grid = document.getElementById('functional-calendar-days');
            grid.innerHTML = '<div style="grid-column:span 7; text-align:center; padding:20px;"><i class="fa-solid fa-spinner fa-spin"></i></div>';

            fetch(`/api/calendario/disponibilidad?mes=${mes}&anio=${anio}`)
                .then(res => res.json())
                .then(disponibilidad => {
                    grid.innerHTML = '';

                    // Calcular la fecha mínima permitida para reagendar:
                    // = max(hoy, fechaCita - 1 día)
                    const hoy = new Date();
                    hoy.setHours(0, 0, 0, 0);

                    let minFechaPermitida = hoy;
                    if (fechaCitaActual) {
                        const unDiaAntes = new Date(fechaCitaActual);
                        unDiaAntes.setDate(unDiaAntes.getDate() - 1);
                        unDiaAntes.setHours(0, 0, 0, 0);
                        // El mínimo es el mayor entre hoy y (cita - 1 día)
                        minFechaPermitida = unDiaAntes > hoy ? unDiaAntes : hoy;
                    }

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

                        // Verificar si este día está por debajo de la fecha mínima permitida
                        const estaFecha = new Date(anio, mes - 1, parseInt(dia));
                        estaFecha.setHours(0, 0, 0, 0);
                        const esBloqueado = estaFecha < minFechaPermitida;

                        if (esBloqueado) {
                            // Día bloqueado por restricción de reagendado
                            div.style.background = '#d1d5db';
                            div.style.color = '#9ca3af';
                            div.style.cursor = 'not-allowed';
                            div.style.opacity = '0.5';
                            div.title = 'No disponible para reagendar';
                        } else if (data.estado === 'verde') {
                            div.style.background = '#32D74B'; div.style.color = 'white';
                        } else if (data.estado === 'amarillo') {
                            div.style.background = '#FFC107'; div.style.color = '#333';
                        } else if (data.estado === 'rojo') {
                            div.style.background = '#EF4444'; div.style.color = 'white';
                        } else {
                            div.style.background = '#f0f0f0'; div.style.color = '#ccc';
                        }

                        if (!esBloqueado && data.clickable) {
                            div.style.cursor = 'pointer';
                            div.onclick = () => abrirModalAgendar(dia, mes, anio);
                            div.onmouseover = () => div.style.transform = 'scale(1.1)';
                            div.onmouseout = () => div.style.transform = 'scale(1)';
                        } else if (!esBloqueado && data.estado === 'rojo') {
                            div.style.cursor = 'not-allowed';
                            div.onclick = () => alert('Este día no tiene horarios disponibles.');
                        } else if (esBloqueado) {
                            div.onclick = () => alert('No puedes reagendar antes de un día anterior a la cita actual.');
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
            // Abre el Widget de Horario, y pre-rellena la fecha seleccionada
            const fechaString = `${anio}-${String(mes).padStart(2, '0')}-${String(dia).padStart(2, '0')}`;
            document.getElementById('input-nueva-fecha').value = fechaString;
            openWidget('widget-horario');
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

                    // 2. Historial completo de citas del paciente → generar todas las filas
                    const tbody = document.getElementById('cita-tabla-body');
                    tbody.innerHTML = ''; // limpiar filas anteriores

                    // Variables globales para paginación
                    window.todasLasFilas = [];
                    window.paginaActual = 1;
                    window.filasPorPagina = 4;

                    if (data.historial_citas && data.historial_citas.length > 0) {
                        // Guardar todas las filas en el array global
                        window.todasLasFilas = data.historial_citas.map(function (fila) {
                            const esActual = fila.es_actual;
                            const bgFila = esActual ? '#E8FFF4' : 'white';
                            const borde = '2px solid #00D1FF';
                            const tdStyle = `padding:14px 15px; border-right:${borde}; font-size:1em; color:#333; background:${bgFila};`;
                            const tdLast = `padding:14px 15px; font-size:1em; font-weight:700; color:var(--primary-color); background:${bgFila};`;
                            const tr = document.createElement('tr');
                            tr.style.borderBottom = borde;
                            if (esActual) tr.style.fontWeight = '700';
                            tr.innerHTML = `
                                            <td style="${tdStyle}">${fila.dia}</td>
                                            <td style="${tdStyle}">${fila.hora}</td>
                                            <td style="${tdStyle} max-width:200px; white-space:normal;">${fila.seguimiento}</td>
                                            <td style="${tdStyle}; font-weight:700; color:var(--primary-color);">$${fila.abono}</td>
                                            <td style="${tdLast}">${fila.estado}</td>
                                        `;
                            return tr;
                        });

                        // Renderizar la primera página
                        renderizarPagina();
                    } else {
                        tbody.innerHTML = '<tr><td colspan="5" style="padding:18px; color:#888; text-align:center;">Sin historial de citas</td></tr>';
                        document.getElementById('paginacion-controles').style.display = 'none';
                    }

                    // 3. Totales Crudos y Display
                    // Replace "$..." with exact integers for raw variables based on what backend returns
                    const rawCosto = parseFloat(data.finanzas.total.replace(/,/g, ''));
                    const rawRestante = parseFloat(data.finanzas.restante.replace(/,/g, ''));
                    const rawPagado = rawCosto - rawRestante; // We calculate pagado so far

                    document.getElementById('raw-costo-total').value = rawCosto;
                    document.getElementById('raw-total-abonado').value = rawPagado;

                    document.getElementById('lbl-total').innerText = '$' + data.finanzas.total;
                    document.getElementById('lbl-restante').innerText = '$' + data.finanzas.restante;

                    // 4. Guardar fecha de la cita actual para el calendario de reagendado
                    if (data.fila_tabla && data.fila_tabla.dia) {
                        // Convertir dd/mm/yyyy a Date
                        const partesFecha = data.fila_tabla.dia.split('/');
                        if (partesFecha.length === 3) {
                            fechaCitaActual = new Date(parseInt(partesFecha[2]), parseInt(partesFecha[1]) - 1, parseInt(partesFecha[0]));
                        }
                    }

                    // 5. Calendario
                   if (data.fecha_cita) {
                    calMesActual = data.fecha_cita.mes + 1;
                    calAnioActual = data.fecha_cita.anio;
                    if (calMesActual > 12) {
                        calMesActual = 1;
                        calAnioActual++;}

    cargarCalendarioFuncional(calMesActual, calAnioActual);
}

                    // 5. Histórico Odontograma
                    document.getElementById('odontograma-paciente-id').value = data.paciente.id_paciente;

                    if (document.getElementById('odontograma-paciente-edad')) {
                        document.getElementById('odontograma-paciente-edad').value = data.paciente.edad_numero;
                        document.dispatchEvent(new CustomEvent('odontograma:edadCargada', { detail: { edad: data.paciente.edad_numero } }));
                    }

                    if (data.odontograma) {
                        console.log("Historial dental del paciente:", data.odontograma);

                        // Limpiar todos los dientes primero
                        document.querySelectorAll('.cara-diente').forEach(c => c.style.fill = 'white');

                        // Pintar los registrados — columnas reales: numero_diente, cara_diente, estado_diente
                        data.odontograma.forEach(registro => {
                            const caraElement = document.querySelector(`.diente[data-diente="${registro.numero_diente}"] .cara-diente[data-cara="${registro.cara_diente}"]`);
                            if (caraElement) {
                                // estado_diente 'hallazgo' = azul; tratamiento/otros = rojo
                                const color = (registro.estado_diente === 'hallazgo') ? 'blue' : 'red';
                                caraElement.style.fill = color;
                            }
                        });
                    }
                })
                .catch(err => {
                    console.error("Error:", err);
                    document.getElementById('lbl-nombre').innerText = 'Error al cargar';
                });
        }

        // ==========================================
        // LÓGICA INTERACTIVA ODONTOGRAMA (Dinámica)
        // ==========================================
        document.addEventListener('DOMContentLoaded', function () {
            // Definición de las piezas según el sistema FDI
            const dientesPermSup = [18, 17, 16, 15, 14, 13, 12, 11, 21, 22, 23, 24, 25, 26, 27, 28];
            const dientesTempSup = [55, 54, 53, 52, 51, 61, 62, 63, 64, 65];
            const dientesTempInf = [85, 84, 83, 82, 81, 71, 72, 73, 74, 75];
            const dientesPermInf = [48, 47, 46, 45, 44, 43, 42, 41, 31, 32, 33, 34, 35, 36, 37, 38];

            const svgCaras = `
                                                                                                            <svg viewBox="0 0 100 100" class="odontograma-svg">
                                                                                                                <polygon class="cara-diente" data-cara="vestibular" points="0,0 100,0 75,25 25,25" />
                                                                                                                <polygon class="cara-diente" data-cara="distal" points="100,0 100,100 75,75 75,25" />
                                                                                                                <polygon class="cara-diente" data-cara="palatina" points="0,100 100,100 75,75 25,75" />
                                                                                                                <polygon class="cara-diente" data-cara="mesial" points="0,0 0,100 25,75 25,25" />
                                                                                                                <circle class="cara-diente" data-cara="oclusal" cx="50" cy="50" r="25" />
                                                                                                            </svg>
                                                                                                        `;
            function obtenerIdAnatomia(numero) {
                const numStr = numero.toString();
                const ultimoDigito = parseInt(numStr[numStr.length - 1]);
                const cuadrante = parseInt(numStr[0]);
                const esSuperior = cuadrante === 1 || cuadrante === 2 || cuadrante === 5 || cuadrante === 6;

                if (numero < 50) { // Permanentes
                    if (ultimoDigito === 1 || ultimoDigito === 2) return '#tooth-incisor';
                    if (ultimoDigito === 3) return '#tooth-canine';
                    if (ultimoDigito === 4 || ultimoDigito === 5) return '#tooth-premolar';
                    if (ultimoDigito >= 6) return esSuperior ? '#tooth-molar-upper' : '#tooth-molar-lower';
                } else { // Temporales
                    if (ultimoDigito === 1 || ultimoDigito === 2) return '#tooth-incisor';
                    if (ultimoDigito === 3) return '#tooth-canine';
                    if (ultimoDigito >= 4) return esSuperior ? '#tooth-molar-upper' : '#tooth-molar-lower';
                }
                return '#tooth-incisor'; // Fallback
            }

            function renderizarFila(contenedorId, arrayDientes, orientacion) {
                const contenedor = document.getElementById(contenedorId);
                contenedor.innerHTML = '';
                const esInferior = orientacion === 'inferior';

                arrayDientes.forEach(numero => {
                    const wrapper = document.createElement('div');
                    wrapper.className = `diente-wrapper diente ${orientacion}`;
                    wrapper.dataset.diente = numero;

                    const svgId = obtenerIdAnatomia(numero);
                    const divAnatomia = `
                                                                                                <div class="anatomia">
                                                                                                    <svg><use href="${svgId}"></use></svg>
                                                                                                </div>`;
                    const divNumero = `<div class="numero-diente">${numero}</div>`;
                    const divCaras = `<div class="caras-interactivas">${svgCaras}</div>`;

                    if (esInferior) {
                        wrapper.innerHTML = divNumero + divCaras + divAnatomia;
                    } else {
                        wrapper.innerHTML = divAnatomia + divCaras + divNumero;
                    }
                    contenedor.appendChild(wrapper);
                });
            }

            renderizarFila('fila-perm-sup', dientesPermSup, 'superior');
            renderizarFila('fila-temp-sup', dientesTempSup, 'superior');
            renderizarFila('fila-temp-inf', dientesTempInf, 'inferior');
            renderizarFila('fila-perm-inf', dientesPermInf, 'inferior');

            // Mostrar/Ocultar filas según la edad informada por AJAX
            document.addEventListener('odontograma:edadCargada', function (e) {
                const edad = parseInt(e.detail.edad);
                const permSup = document.getElementById('fila-perm-sup');
                const permInf = document.getElementById('fila-perm-inf');
                const tempSup = document.getElementById('fila-temp-sup');
                const tempInf = document.getElementById('fila-temp-inf');

                if (edad <= 5) {
                    permSup.style.display = 'none'; permInf.style.display = 'none';
                    tempSup.style.display = 'flex'; tempInf.style.display = 'flex';
                } else if (edad >= 6 && edad <= 12) {
                    permSup.style.display = 'flex'; permInf.style.display = 'flex';
                    tempSup.style.display = 'flex'; tempInf.style.display = 'flex';
                } else {
                    permSup.style.display = 'flex'; permInf.style.display = 'flex';
                    tempSup.style.display = 'none'; tempInf.style.display = 'none';
                }
            });

            // Usamos delegación de eventos ya que los SVG se generan dinámicamente
            document.getElementById('odontograma-lienzo').addEventListener('click', function (e) {
                if (e.target.classList.contains('cara-diente')) {
                    e.preventDefault();
                    const cara = e.target;
                    const dienteWrapper = cara.closest('.diente');
                    const numeroDiente = dienteWrapper.getAttribute('data-diente');
                    const nombreCara = cara.getAttribute('data-cara');
                    const selectElement = document.getElementById('select-servicio');

                    if (!selectElement || !selectElement.value) {
                        alert("Por favor, selecciona un tratamiento primero.");
                        return;
                    }

                    const idServicio = selectElement.value;
                    const tipoRegistro = document.querySelector('input[name="tipo_registro"]:checked').value;
                    const color = (tipoRegistro === 'hallazgo') ? 'blue' : 'red';

                    cara.style.fill = color;
                    const idPaciente = document.getElementById('odontograma-paciente-id').value;

                    fetch('/api/odontograma', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            id_paciente: idPaciente,
                            numero_diente: numeroDiente,
                            cara_diente: nombreCara,
                            id_servicio: idServicio,
                            estado_diente: tipoRegistro,
                            observaciones: 'Creado desde Modal Web Dinámico'
                        })
                    })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                console.log('Odontograma actualizado en BD', data);
                            } else {
                                alert("Error al guardar: " + (data.message || "Desconocido"));
                                cara.style.fill = 'white';
                            }
                        })
                        .catch(error => {
                            console.error('Error FETCH:', error);
                            cara.style.fill = 'white';
                        });
                }
            });
        });

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
                        // --- TABLA DE ABONOS ---
                        const montoAbonado = parseFloat(document.querySelector('input[name="monto_abono"]').value) || 0;

                        if (montoAbonado > 0) {
                            // Agregar una nueva fila de abono a la tabla
                            const tbody = document.getElementById('cita-tabla-body');
                            if (tbody && window.todasLasFilas && window.todasLasFilas.length > 0) {
                                // Buscar la fila activa (con fondo verde) o usar la primera fila disponible
                                let filaReferencia = null;

                                // Buscar en todas las filas (no solo las visibles)
                                for (let i = 0; i < window.todasLasFilas.length; i++) {
                                    const fila = window.todasLasFilas[i];
                                    if (fila.style.fontWeight === '700' || fila.querySelector('td[style*="E8FFF4"]')) {
                                        filaReferencia = fila;
                                        break;
                                    }
                                }

                                // Si no encontramos fila activa, usar la primera
                                if (!filaReferencia) {
                                    filaReferencia = window.todasLasFilas[0];
                                }

                                // Obtener los datos de las celdas
                                const cells = filaReferencia.cells;
                                const diaCita = cells[0] ? cells[0].innerText : '';
                                const horaCita = cells[1] ? cells[1].innerText : '';
                                const seguimientoCita = cells[2] ? cells[2].innerText : '';

                                const borde = '2px solid #00D1FF';
                                const nuevaFila = document.createElement('tr');
                                nuevaFila.style.borderBottom = borde;
                                nuevaFila.innerHTML = `
                                                                        <td style="padding:12px 15px; border-right:${borde}; color:#555;">${diaCita}</td>
                                                                        <td style="padding:12px 15px; border-right:${borde}; color:#555;">${horaCita}</td>
                                                                        <td style="padding:12px 15px; border-right:${borde}; color:#555;">${seguimientoCita}</td>
                                                                        <td style="padding:12px 15px; border-right:${borde}; font-weight:800; color:var(--primary-color);">${data.data.abono_fila}</td>
                                                                        <td style="padding:12px 15px; color:#555;">Abono</td>
                                                                    `;

                                // Agregar al inicio del array global y volver a página 1
                                window.todasLasFilas.unshift(nuevaFila);
                                window.paginaActual = 1;
                                renderizarPagina();
                            }
                        }

                        // --- ACTUALIZAR TOTALES ---
                        document.getElementById('lbl-total').innerText = data.data.costo_total;
                        document.getElementById('lbl-restante').innerText = data.data.restante;

                        // Actualizar datos crudos para el widget en futuras adiciones
                        const nRawCosto = parseFloat(data.data.costo_total.replace('$', '').replace(/,/g, ''));
                        const nRawRestante = parseFloat(data.data.restante.replace('$', '').replace(/,/g, ''));
                        document.getElementById('raw-costo-total').value = nRawCosto;
                        document.getElementById('raw-total-abonado').value = nRawCosto - nRawRestante;

                        // --- ACTUALIZAR TARJETA DE INGRESOS DEL MES EN TIEMPO REAL ---
                        if (montoAbonado > 0) {
                            const lblIngresos = document.getElementById('lbl-ingresos-mes');
                            if (lblIngresos) {
                                // Extraer el valor actual (quitar $ y comas)
                                const valorActual = parseFloat(lblIngresos.innerText.replace(/[$,]/g, '')) || 0;
                                const nuevoTotal = valorActual + montoAbonado;
                                // Formatear con separadores de miles
                                lblIngresos.innerText = '$' + nuevoTotal.toLocaleString('es-MX', { maximumFractionDigits: 0 });
                                // Animación breve para resaltar el cambio
                                lblIngresos.style.transition = 'color 0.4s';
                                lblIngresos.style.color = '#FF9800';
                                setTimeout(() => lblIngresos.style.color = '#333', 1500);
                            }
                        }

                        // Actualizar visualmente la fila activa en la tabla si se reprogramó
                        if (data.data.nueva_fecha || data.data.nueva_hora || data.data.seguimiento) {
                            const tbody2 = document.getElementById('cita-tabla-body');
                            if (tbody2) {
                                const filaActiva = Array.from(tbody2.rows).find(r => r.style.background === 'rgb(232, 255, 244)') || tbody2.lastElementChild;
                                if (filaActiva && filaActiva.cells.length >= 3) {
                                    if (data.data.nueva_fecha) filaActiva.cells[0].innerText = data.data.nueva_fecha;
                                    if (data.data.nueva_hora) filaActiva.cells[1].innerText = data.data.nueva_hora;
                                    if (data.data.seguimiento) filaActiva.cells[2].innerText = data.data.seguimiento;
                                }
                            }
                        }

                        // Show success
                        alert('¡Actualizado correctamente!');

                        // Limpiar inputs
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
            // Ocultar todos los tabs que NO sean inner-widgets
            document.querySelectorAll('.tab-content').forEach(tab => {
                if (!tab.classList.contains('inner-widget')) {
                    tab.style.display = 'none';
                }
            });

            // Mostrar el seleccionado
            const tab = document.getElementById(tabId);
            if (tab) {
                tab.style.display = (tabId === 'tab-resumen') ? 'flex' : 'block';
                if (tabId === 'tab-resumen') tab.style.flexDirection = 'column';
            }
        }

        // --- MANEJO DE WIDGETS EMERGENTES ---
        function openWidget(widgetId) {
            document.getElementById('internal-widget-overlay').style.display = 'block';
            document.querySelectorAll('.inner-widget').forEach(w => w.style.display = 'none'); // Cierra otros posibles widgets abiertos
            document.getElementById(widgetId).style.display = 'block';

            // Focus automatically si es el de pago
            if (widgetId === 'widget-pago') {
                setTimeout(() => document.getElementById('input-monto-abono').focus(), 100);
            }
        }

        function closeWidgets() {
            document.getElementById('internal-widget-overlay').style.display = 'none';
            document.querySelectorAll('.inner-widget').forEach(w => w.style.display = 'none');
        }

        // Cierra los widgets si das clic afuera (en el overlay)
        document.getElementById('internal-widget-overlay').addEventListener('click', closeWidgets);

        // --- CALCULO MATEMATICO EN TIEMPO REAL ---
        function calcularVueltoReal() {
            const costoTotalBase = parseFloat(document.getElementById('raw-costo-total').value) || 0;
            const abonoAnterior = parseFloat(document.getElementById('raw-total-abonado').value) || 0;
            const abonoActual = parseFloat(document.getElementById('input-monto-abono').value) || 0;

            const totalAbonadoAcumulado = abonoAnterior + abonoActual;
            let restanteVirtual = costoTotalBase - totalAbonadoAcumulado;

            if (restanteVirtual < 0) restanteVirtual = 0; // Evitar negativos visibles

            // Update Label
            document.getElementById('lbl-restante').innerText = restanteVirtual.toFixed(2);
        }

        // ==========================================
        // MARCAR CITA COMO COMPLETADA (con animación)
        // ==========================================
        function completarCita(idCita) {
            const card = document.getElementById('cita-card-' + idCita);
            const overlay = document.getElementById('overlay-' + idCita);
            const path = document.getElementById('check-path-' + idCita);
            const btn = document.getElementById('btn-completar-' + idCita);

            if (!card || !overlay) return;

            // Evitar doble clic
            if (btn) { btn.disabled = true; btn.style.opacity = '0.5'; btn.style.cursor = 'default'; }

            // ── 1. Llamada AJAX ───────────────────────────────────────────
            fetch('/api/citas/' + idCita + '/completar', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                }
            })
                .then(res => res.json())
                .then(data => {
                    if (!data.success) {
                        alert('Error: ' + (data.message || 'No se pudo completar la cita.'));
                        if (btn) { btn.disabled = false; btn.style.opacity = '1'; btn.style.cursor = 'pointer'; }
                        return;
                    }

                    // ── 2. Deshabilitar todos los botones ────────────────────
                    card.querySelectorAll('button').forEach(b => {
                        b.disabled = true;
                        b.style.pointerEvents = 'none';
                    });

                    // ── 3. Mostrar overlay ────────────────────────────────────
                    overlay.style.display = 'flex';

                    // ── 4. Animar el trazo del check (doble rAF para forzar reflow) ──
                    requestAnimationFrame(() => {
                        requestAnimationFrame(() => {
                            if (path) path.style.strokeDashoffset = '0';
                        });
                    });

                    // ── 5. Tras 4.5 s → colapsar y desvanecer ────────────────
                    setTimeout(() => {
                        // Medir altura real ANTES de poner overflow:hidden
                        const realH = card.getBoundingClientRect().height;

                        // Fijar altura y poner overflow para que el colapso sea limpio
                        card.style.maxHeight = realH + 'px';
                        card.style.overflow = 'hidden';

                        // Forzar reflow
                        card.getBoundingClientRect();

                        // Activar transición y colapsar todo de golpe
                        card.style.transition = [
                            'opacity 0.7s ease',
                            'max-height 0.7s ease',
                            'padding-top 0.7s ease',
                            'padding-bottom 0.7s ease',
                            'margin-bottom 0.7s ease'
                        ].join(', ');

                        card.style.opacity = '0';
                        card.style.maxHeight = '0';
                        card.style.paddingTop = '0';
                        card.style.paddingBottom = '0';
                        card.style.marginBottom = '0';

                        // ── 6. Eliminar del DOM al terminar la transición ─────
                        setTimeout(() => {
                            card.remove();
                            const list = document.getElementById('appointment-list');
                            if (list && list.querySelectorAll('.appointment-card').length === 0) {
                                list.innerHTML = '<p style="text-align:center;color:#888;padding:30px;">No hay citas próximas agendadas.</p>';
                            }
                        }, 750);

                    }, 4500);
                })
                .catch(err => {
                    console.error('Error al completar cita:', err);
                    if (btn) { btn.disabled = false; btn.style.opacity = '1'; btn.style.cursor = 'pointer'; }
                    alert('Error de conexión. Inténtalo de nuevo.');
                });
        }

        // ==========================================
        // FUNCIONES DE PAGINACIÓN
        // ==========================================
        function renderizarPagina() {
            const tbody = document.getElementById('cita-tabla-body');
            if (!tbody || !window.todasLasFilas) return;

            tbody.innerHTML = '';

            const totalPaginas = Math.ceil(window.todasLasFilas.length / window.filasPorPagina);
            const inicio = (window.paginaActual - 1) * window.filasPorPagina;
            const fin = inicio + window.filasPorPagina;

            const filasPagina = window.todasLasFilas.slice(inicio, fin);

            filasPagina.forEach(fila => {
                tbody.appendChild(fila.cloneNode(true));
            });

            // Actualizar info de paginación
            document.getElementById('info-paginacion').innerText = `Página ${window.paginaActual} de ${totalPaginas}`;

            // Actualizar estado de botones
            document.getElementById('btn-pag-anterior').disabled = window.paginaActual === 1;
            document.getElementById('btn-pag-siguiente').disabled = window.paginaActual >= totalPaginas;

            // Cambiar estilo de botones deshabilitados
            const btnAnterior = document.getElementById('btn-pag-anterior');
            const btnSiguiente = document.getElementById('btn-pag-siguiente');

            btnAnterior.style.opacity = window.paginaActual === 1 ? '0.5' : '1';
            btnAnterior.style.cursor = window.paginaActual === 1 ? 'not-allowed' : 'pointer';
            btnSiguiente.style.opacity = window.paginaActual >= totalPaginas ? '0.5' : '1';
            btnSiguiente.style.cursor = window.paginaActual >= totalPaginas ? 'not-allowed' : 'pointer';

            // Mostrar/ocultar controles si solo hay una página
            if (totalPaginas <= 0) {
                document.getElementById('paginacion-controles').style.display = 'none';
            } else {
                document.getElementById('paginacion-controles').style.display = 'flex';
            }
        }

        function cambiarPagina(direccion) {
            if (!window.todasLasFilas) return;

            const totalPaginas = Math.ceil(window.todasLasFilas.length / window.filasPorPagina);
            window.paginaActual += direccion;

            // Validar límites
            if (window.paginaActual < 1) window.paginaActual = 1;
            if (window.paginaActual > totalPaginas) window.paginaActual = totalPaginas;

            renderizarPagina();
        }
    </script>
@endsection