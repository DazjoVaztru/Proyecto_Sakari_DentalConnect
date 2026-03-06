@extends('layouts.app')

@section('titulo', 'Tratamientos')

@section('contenido')

<h2 class="page-title" style="margin-bottom: 30px;">
    Gestión de Tratamientos
</h2>

{{-- MENSAJES --}}
@if(session('success'))
<div style="background:#dcfce7;color:#166534;padding:15px;border-radius:8px;margin-bottom:20px;border:1px solid #bbf7d0;">
    <i class="fa-solid fa-check"></i> {{ session('success') }}
</div>
@endif


{{-- BARRA SUPERIOR --}}
<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:25px;">

    <div style="position:relative;width:350px;">
        <input type="text"
            placeholder="Buscar..."
            style="width:100%;padding:12px 25px;border-radius:50px;border:1px solid #bcebf5;background:#e0fbfc;outline:none;">
        <i class="fa-solid fa-magnifying-glass"
            style="position:absolute;right:20px;top:12px;color:#00b4d8;"></i>
    </div>

    <button onclick="openModal('modal-new-treatment')"
        class="ghost-btn"
        style="border-radius:50px;background:#00D1FF;color:white;border:none;padding:12px 25px;">
        <i class="fa-solid fa-plus"></i> Nuevo Tratamiento
    </button>

</div>


{{-- TABLA DE TRATAMIENTOS --}}
<div class="dashboard-table"
    style="background:white;border-radius:15px;padding:20px;box-shadow:0 4px 15px rgba(0,0,0,0.03);">

<table style="width:100%;border-collapse:collapse;">

<thead>
<tr style="border-bottom:2px solid #f0f0f0;">
<th style="text-align:left;padding:15px;color:#666;">Nombre</th>
<th style="text-align:left;padding:15px;color:#666;">Categoría</th>
<th style="text-align:left;padding:15px;color:#666;">Costo</th>
<th style="text-align:right;padding:15px;color:#666;">Acciones</th>
</tr>
</thead>

<tbody>

@forelse($servicios as $servicio)

<tr style="border-bottom:1px solid #eee;">

<td style="padding:15px;font-weight:600;">
{{ $servicio->nombre_servicio }}
</td>

<td style="padding:15px;color:#888;font-size:0.9em;">
<span style="background:#f3f4f6;padding:4px 10px;border-radius:10px;">
{{ $servicio->categoria ?? 'General' }}
</span>
</td>

<td style="padding:15px;color:var(--primary-color);font-weight:bold;">
${{ number_format($servicio->precio_base,2) }}
</td>

<td style="padding:15px;text-align:right;">

<button onclick='editarTratamiento(@json($servicio))'
style="background:none;border:none;cursor:pointer;color:#f59e0b;margin-right:10px;">
<i class="fa-solid fa-pen"></i>
</button>

<form action="{{ route('tratamientos.destroy',$servicio->id_servicio) }}"
method="POST"
style="display:inline;"
onsubmit="return confirm('¿Borrar tratamiento?');">

@csrf
@method('DELETE')

<button type="submit"
style="background:none;border:none;cursor:pointer;color:#ef4444;">
<i class="fa-solid fa-trash"></i>
</button>

</form>

</td>

</tr>

@empty

<tr>
<td colspan="4" style="text-align:center;padding:30px;color:#999;">
No hay tratamientos registrados.
</td>
</tr>

@endforelse

</tbody>
</table>

</div>



{{-- MODAL NUEVO TRATAMIENTO --}}
<div id="modal-new-treatment" class="modal-overlay">

<div class="modal-glass" style="max-width:500px;">

<button class="close-modal"
onclick="closeModal('modal-new-treatment')">&times;</button>

<h3>Nuevo Tratamiento</h3>

@if(session('error'))
<div style="
    background:#fee2e2;
    color:#991b1b;
    padding:10px;
    border-radius:8px;
    margin-bottom:15px;
    border:1px solid #fecaca;
    font-size:14px;">
    <i class="fa-solid fa-triangle-exclamation"></i>
    {{ session('error') }}
</div>
@endif

<form action="{{ route('tratamientos.store') }}" method="POST">

@csrf

{{-- NOMBRE --}}
<div style="margin-bottom:15px;">

<input
type="text"
name="nombre"
class="modern-input"
placeholder="Nombre Servicio"
value="{{ old('nombre') }}"
oninput="this.value=this.value.replace(/[^A-Za-zÁÉÍÓÚÜáéíóúüÑñ\s]/g,'')"
required
style="width:100%;">

@error('nombre')
<small style="color:red;">{{ $message }}</small>
@enderror

</div>


{{-- PRECIO --}}
<div style="margin-bottom:15px;">

<input
type="number"
name="precio"
step="0.01"
class="modern-input"
placeholder="Precio Base"
value="{{ old('precio') }}"
required
style="width:100%;">

@error('precio')
<small style="color:red;">{{ $message }}</small>
@enderror

</div>


{{-- CATEGORIA --}}
<div style="margin-bottom:15px;">

<select name="categoria" class="modern-input" style="width:100%;">

<option value="General">General</option>
<option value="Ortodoncia">Ortodoncia</option>
<option value="Limpieza">Limpieza</option>
<option value="Cirugía">Cirugía</option>
<option value="Estética">Estética</option>
<option value="Endodoncia">Endodoncia</option>

</select>

</div>


<button type="submit"
class="ghost-btn"
style="width:100%;background:var(--primary-color);color:white;">
Guardar
</button>

</form>

</div>
</div>



{{-- MODAL EDITAR --}}
<div id="modal-edit-treatment" class="modal-overlay">

<div class="modal-glass" style="max-width:500px;">

<button class="close-modal"
onclick="closeModal('modal-edit-treatment')">&times;</button>

<h3 style="color:#f59e0b;">Editar Tratamiento</h3>

<form id="form-edit" method="POST">

@csrf
@method('PUT')

<div style="margin-bottom:15px;">
<label style="font-size:0.8em;">Nombre</label>

<input
type="text"
id="edit-nombre"
name="nombre"
class="modern-input"
required
style="width:100%;">
</div>


<div style="margin-bottom:15px;">
<label style="font-size:0.8em;">Precio</label>

<input
type="number"
id="edit-precio"
name="precio"
step="0.01"
class="modern-input"
required
style="width:100%;">
</div>


<div style="margin-bottom:15px;">
<label style="font-size:0.8em;">Categoría</label>

<select
id="edit-categoria"
name="categoria"
class="modern-input"
style="width:100%;">

<option value="General">General</option>
<option value="Ortodoncia">Ortodoncia</option>
<option value="Limpieza">Limpieza</option>
<option value="Cirugía">Cirugía</option>
<option value="Estética">Estética</option>
<option value="Endodoncia">Endodoncia</option>

</select>

</div>

<button type="submit"
class="ghost-btn"
style="width:100%;background:#f59e0b;color:white;">
Actualizar
</button>

</form>

</div>
</div>

@endsection



@section('scripts')

<script>

function editarTratamiento(servicio){

document.getElementById('edit-nombre').value = servicio.nombre_servicio
document.getElementById('edit-precio').value = servicio.precio_base
document.getElementById('edit-categoria').value = servicio.categoria || 'General'

const form = document.getElementById('form-edit')
form.action = `/tratamientos/${servicio.id_servicio}`

openModal('modal-edit-treatment')

}


// abrir modal si hay error
@if(session('error'))
openModal('modal-new-treatment')
@endif

</script>

@endsection