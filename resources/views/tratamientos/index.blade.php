@extends('layouts.app')

@section('titulo', 'Tratamientos')

@section('contenido')

<h2 class="page-title" style="margin-bottom: 30px;">
    Gestión de Tratamientos
</h2>

{{-- ÁREA DE MENSAJES DE NOTIFICACIÓN --}}
@if(session('success'))
<div style="background:#dcfce7;color:#166534;padding:15px;border-radius:8px;margin-bottom:20px;border:1px solid #bbf7d0; display: flex; align-items: center; gap: 10px;">
    <i class="fa-solid fa-circle-check"></i> {{ session('success') }}
</div>
@endif

{{-- Aquí aparecerá el error de "No se puede eliminar" sin abrir el modal --}}
@if(session('error'))
<div style="background:#fee2e2;color:#991b1b;padding:15px;border-radius:8px;margin-bottom:20px;border:1px solid #fecaca; display: flex; align-items: center; gap: 10px;">
    <i class="fa-solid fa-triangle-exclamation"></i> {{ session('error') }}
</div>
@endif

{{-- BARRA SUPERIOR (SIN BÚSQUEDA) --}}
<div style="display:flex; justify-content: flex-end; align-items:center; margin-bottom:25px;">
    <button onclick="openModal('modal-new-treatment')" class="ghost-btn" style="border-radius:50px; background:#00D1FF; color:white; border:none; padding:12px 25px; cursor: pointer; font-weight: 600;">
        <i class="fa-solid fa-plus"></i> Nuevo Tratamiento
    </button>
</div>

{{-- TABLA DE TRATAMIENTOS --}}
<div class="dashboard-table" style="background:white; border-radius:15px; padding:20px; box-shadow:0 4px 15px rgba(0,0,0,0.03);">
    <table style="width:100%; border-collapse:collapse;">
        <thead>
            <tr style="border-bottom:2px solid #f0f0f0;">
                <th style="text-align:left; padding:15px; color:#666;">Nombre</th>
                <th style="text-align:left; padding:15px; color:#666;">Categoría</th>
                <th style="text-align:left; padding:15px; color:#666;">Costo</th>
                <th style="text-align:right; padding:15px; color:#666;">Acciones</th>
            </tr>
        </thead>
        <tbody>
            @forelse($tratamientos as $servicio)
            <tr style="border-bottom:1px solid #eee;">
                <td style="padding:15px; font-weight:600;">{{ $servicio->nombre_servicio }}</td>
                <td style="padding:15px; color:#888; font-size:0.9em;">
                    <span style="background:#f3f4f6; padding:4px 10px; border-radius:10px;">
                        {{ $servicio->categoria ?? 'General' }}
                    </span>
                </td>
                <td style="padding:15px; color:var(--primary-color); font-weight:bold;">
                    ${{ number_format($servicio->precio_base, 2) }}
                </td>
                <td style="padding:15px; text-align:right;">
                    <button onclick='editarTratamiento(@json($servicio))' style="background:none; border:none; cursor:pointer; color:#f59e0b; margin-right:10px; font-size: 1.1rem;">
                        <i class="fa-solid fa-pen"></i>
                    </button>

                    <form id="delete-form-{{ $servicio->id_servicio }}" action="{{ url('tratamientos/' . $servicio->id_servicio) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="button" onclick="confirmDelete({{ $servicio->id_servicio }})" style="background:none; border:none; cursor:pointer; color:#ef4444; font-size: 1.1rem;">
                            <i class="fa-solid fa-trash"></i>
                        </button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="4" style="text-align:center; padding:30px; color:#999;">No hay tratamientos registrados</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

{{-- MODALES MANTENIDOS SEGÚN ESTRUCTURA PREVIA --}}
{{-- [Modales de Nuevo y Editar se mantienen igual, pero sin los bloques de error internos] --}}

@endsection

@section('scripts')
{{-- SweetAlert2 para mensajes de confirmación elegantes --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
function confirmDelete(id) {
    Swal.fire({
        title: '¿Eliminar tratamiento?',
        text: "Esta acción no se puede deshacer y fallará si el tratamiento tiene citas vinculadas.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        cancelButtonColor: '#9ca3af',
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('delete-form-' + id).submit();
        }
    })
}

function editarTratamiento(servicio){
    document.getElementById('edit-nombre').value = servicio.nombre_servicio;
    document.getElementById('edit-precio').value = servicio.precio_base;
    document.getElementById('edit-categoria').value = servicio.categoria || 'General';
    const form = document.getElementById('form-edit');
    form.action = "{{ url('tratamientos') }}/" + servicio.id_servicio;
    openModal('modal-edit-treatment');
}

{{-- Solo abre el modal de 'Nuevo' si hay un error que NO sea de eliminación --}}
@if(session('error') && !str_contains(session('error'), 'eliminar'))
    openModal('modal-new-treatment');
@endif
</script>
@endsection