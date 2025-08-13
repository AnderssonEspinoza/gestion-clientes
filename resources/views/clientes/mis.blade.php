@extends('layouts.app')

@section('content')

<div class="container mx-auto p-4">
    <h1 class="text-2xl font-bold mb-4">Mis Clientes Asignados</h1>
    
    @if(session('success'))
        <div class="bg-green-200 text-green-800 p-2 mb-4 rounded">
            {{ session('success') }}
        </div>
    @endif
    
    @if(session('error'))
        <div class="bg-red-200 text-red-800 p-2 mb-4 rounded">
            {{ session('error') }}
        </div>
    @endif
    
    @if($misClientes->isEmpty())
        <p>No tienes clientes asignados.</p>
    @else
        <div class="overflow-x-auto">
            <table class="min-w-full border text-sm md:text-base">
                <thead>
                    <tr class="bg-gray-200">
                        <th class="border px-4 py-2">Nombre</th>
                        <th class="border px-4 py-2">Email</th>
                        <th class="border px-4 py-2">Teléfono</th>
                        <th class="border px-4 py-2">Estado</th>
                        <th class="border px-4 py-2">Acciones</th>
                    </tr>
                </thead>
                <tbody>
 
                    @foreach($misClientes as $cliente)
                    <tr>
                        <td class="border px-4 py-2">{{ $cliente->nombre }}</td>
                        <td class="border px-4 py-2">{{ $cliente->email }}</td>
                        <td class="border px-4 py-2">{{ $cliente->telefono ?? '-' }}</td>
                        
                        <td>
                            <!-- Aquí cambias tu columna estado actual por esto: -->
                            <select class="form-select form-select-sm estado-select" 
                                    data-cliente-id="{{ $cliente->id }}"
                                    style="min-width: 160px;">
                                @foreach(App\Models\Cliente::ESTADOS as $key => $label)
                                    <option value="{{ $key }}" 
                                            {{ $cliente->estado == $key ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                        </td>



                        <td class="border px-4 py-2 space-x-2">
                            <a href="{{ route('mis-clientes.show', $cliente->id) }}"                                
                                class="inline-block bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded">                                 
                                Ver                             
                            </a>
                            <a href="#"
                               class="inline-block bg-green-500 hover:bg-green-600 text-white px-3 py-1 rounded">
                                Finalizar
                            </a>
                            
                            @if($cliente->telefono)
                                <a href="#"
                                   onclick="abrirWhatsApp('{{ $cliente->telefono }}', '{{ $cliente->nombre }}')"
                                   class="inline-block bg-green-500 hover:bg-green-600 text-white px-3 py-1 rounded">
                                    Contactar WhatsApp
                                </a>
                            @else
                                <span class="inline-block bg-gray-400 text-white px-3 py-1 rounded cursor-not-allowed">
                                    Sin teléfono
                                </span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>

<script>

document.addEventListener('DOMContentLoaded', function() {
    // Agregar evento a todos los selects de estado
    document.querySelectorAll('.estado-select').forEach(function(select) {
        // Aplicar color inicial al cargar la página
        aplicarColorEstado(select, select.value);
        
        select.addEventListener('change', function() {
            cambiarEstado(this);
        });
    });
});

// Definir colores para cada estado
const coloresEstados = {
    'pendiente': '#ffc107',           // Amarillo
    'en_evaluacion': '#0dcaf0',       // Azul
    'califica': '#198754',            // Verde
    'no_califica': '#dc3545',         // Rojo
    'venta_concretada': '#6f42c1'     // Morado
};

// FUNCIÓN PARA APLICAR COLORES (esta faltaba)
function aplicarColorEstado(selectElement, estado) {
    const color = coloresEstados[estado];
    if (color) {
        selectElement.style.backgroundColor = color;
        selectElement.style.color = '#ffffff';
        selectElement.style.fontWeight = 'bold';
        selectElement.style.borderColor = color;
        selectElement.style.borderWidth = '2px';
    } else {
        // Restablecer si no hay color definido
        selectElement.style.backgroundColor = '#f8f9fa';
        selectElement.style.color = '#212529';
        selectElement.style.fontWeight = 'normal';
        selectElement.style.borderColor = '#ced4da';
        selectElement.style.borderWidth = '1px';
    }
}

function cambiarEstado(selectElement) {
    const clienteId = selectElement.dataset.clienteId;
    const nuevoEstado = selectElement.value;
    const estadoOriginal = selectElement.dataset.original || selectElement.value;
    
    if (!selectElement.dataset.original) {
        selectElement.dataset.original = selectElement.value;
    }
    
    selectElement.disabled = true;
    selectElement.style.opacity = '0.6';
    
    fetch(`/mis-clientes/${clienteId}/estado`, {
        method: 'PATCH',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json'
        },
        body: JSON.stringify({
            estado: nuevoEstado
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            selectElement.dataset.original = nuevoEstado;
            
            // APLICAR EL COLOR DEL NUEVO ESTADO
            aplicarColorEstado(selectElement, nuevoEstado);
            
            console.log('Estado actualizado a:', data.nuevo_estado);
            
            // Animación de éxito
            selectElement.style.boxShadow = '0 0 15px rgba(255,255,255,0.9)';
            setTimeout(() => {
                selectElement.style.boxShadow = '';
            }, 1000);
            
        } else {
            throw new Error(data.message || 'Error al actualizar');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        selectElement.value = estadoOriginal;
        aplicarColorEstado(selectElement, estadoOriginal);
        
        selectElement.style.boxShadow = '0 0 10px #dc3545';
        setTimeout(() => {
            selectElement.style.boxShadow = '';
        }, 2000);
        
        alert('Error al actualizar el estado: ' + error.message);
    })
    .finally(() => {
        selectElement.disabled = false;
        selectElement.style.opacity = '1';
    });
}


//FUNCION PARA ABRIR WHATSAPP
function abrirWhatsApp(telefono, nombre) {
    // Limpiar el número de teléfono (remover espacios, guiones, etc.)
    let numeroLimpio = telefono.replace(/\D/g, '');
    
    // Si el número no empieza con código de país, agregar +51 para Perú
    if (!numeroLimpio.startsWith('51') && numeroLimpio.length === 9) {
        numeroLimpio = '51' + numeroLimpio;
    }
    
    // Mensaje predeterminado (opcional)
    const mensaje = `Hola ${nombre}, soy de AKANA TECHNOLOGY. Me pongo en contacto contigo para...`;
    
    // Crear la URL de WhatsApp
    const urlWhatsApp = `https://wa.me/${numeroLimpio}?text=${encodeURIComponent(mensaje)}`;
    
    // Abrir WhatsApp en una nueva ventana/pestaña
    window.open(urlWhatsApp, '_blank');
}


</script>
@endsection