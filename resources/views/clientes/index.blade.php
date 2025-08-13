@extends('layouts.app')

@section('content')
<div class="container mx-auto p-4 bg-white min-h-screen">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-800">
            📋 Clientes Disponibles
        </h1>
        <div class="flex items-center space-x-4">
            <span id="ultimo-refresh" class="text-sm text-gray-500"></span>
            <button onclick="refreshClientes()" 
                    class="bg-blue-500 text-white px-3 py-2 rounded text-sm hover:bg-blue-600 transition">
                🔄 Actualizar
            </button>
            <label class="flex items-center">
                <input type="checkbox" id="auto-refresh" checked class="mr-2">
                <span class="text-sm text-gray-600">Auto-actualizar (10s)</span>
            </label>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4" id="success-message">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4" id="error-message">
            {{ session('error') }}
        </div>
    @endif

    @if($clientesDisponibles->isEmpty())
        <div class="text-center py-12">
            <div class="text-gray-400 text-6xl mb-4">📋</div>
            <h3 class="text-lg font-medium text-gray-800 mb-2">No hay clientes disponibles</h3>
            <p class="text-gray-600">Todos los clientes han sido asignados o no hay clientes nuevos.</p>
            <button onclick="refreshClientes()" 
                    class="mt-4 bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 transition">
                🔄 Verificar nuevamente
            </button>
        </div>
    @else
    <div class="mb-4">
        <p class="text-gray-700">
            <strong>Total disponibles:</strong> 
            <span class="text-blue-600 font-semibold">{{ $clientesDisponibles->total() }}</span> clientes
        </p>
    </div>

    <div class="overflow-x-auto bg-white rounded-lg shadow-md">
        <table class="min-w-full text-sm text-left text-gray-700">
            <thead class="bg-gray-50 border-b">
                <tr>
                    <th class="px-6 py-3">Nombre</th>
                    <th class="px-6 py-3">Email</th>
                    <th class="px-6 py-3">Teléfono</th>
                    <th class="px-6 py-3">Estado</th>
                    <th class="px-6 py-3">Creado</th>
                    <th class="px-6 py-3 text-center">Acciones</th>
                </tr>
            </thead>
            <tbody class="bg-white" id="clientes-tbody">
                @foreach($clientesDisponibles as $cliente)
                <tr id="cliente-{{ $cliente->id }}" class="border-b hover:bg-gray-50 transition cliente-row">
                    <td class="px-6 py-4 font-medium">{{ $cliente->nombre }}</td>
                    <td class="px-6 py-4">{{ $cliente->email }}</td>
                    <td class="px-6 py-4">{{ $cliente->telefono ?? '-' }}</td>
                    <td class="px-6 py-4">
                        @php
                            $estilos = [
                                'sin_asignar' => 'bg-blue-100 text-blue-800',
                                'pendiente' => 'bg-yellow-100 text-yellow-800',
                                'aprobado' => 'bg-green-100 text-green-800',
                                'rechazado' => 'bg-red-100 text-red-800',
                                'activo' => 'bg-green-100 text-green-800',
                                'inactivo' => 'bg-gray-100 text-gray-800',
                            ];
                            $iconos = [
                                'sin_asignar' => '📋',
                                'pendiente' => '⏳',
                                'aprobado' => '✅',
                                'rechazado' => '❌',
                                'activo' => '✅',
                                'inactivo' => '⚫',
                            ];
                        @endphp
                        <span class="px-3 py-1 rounded-full text-xs font-semibold flex items-center gap-1 w-fit {{ $estilos[$cliente->estado] ?? 'bg-gray-100 text-gray-800' }}">
                            {{ $iconos[$cliente->estado] ?? 'ℹ️' }} {{ ucfirst($cliente->estado) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-xs text-gray-500">
                        {{ $cliente->created_at->format('d/m/Y H:i') }}
                    </td>
                    <td class="px-6 py-4 flex flex-wrap gap-3 justify-center">
                        <!-- Botón Ver -->
                        <a href="{{ route('clientes.show', $cliente->id) }}" 
                           class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg shadow-md transition">
                           🔍 Ver
                        </a>

                        <!-- Botón Asignarme mejorado -->
                        <button 
                            onclick="asignarCliente({{ $cliente->id }}, '{{ $cliente->nombre }}')"
                            class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-lg shadow-md transition asignar-btn"
                            data-cliente-id="{{ $cliente->id }}">
                            ✋ Asignarme
                        </button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-6">
        {{ $clientesDisponibles->links() }}
    </div>
    @endif
</div>

<script>
    let autoRefreshInterval;
    let isAssigning = false; // Flag para evitar múltiples asignaciones

    function actualizarUltimoRefresh() {
        const ahora = new Date();
        document.getElementById('ultimo-refresh').textContent = 
            `Actualizado: ${ahora.toLocaleTimeString()}`;
    }

    async function asignarCliente(clienteId, nombreCliente) {
        if (isAssigning) {
            alert('Ya hay una asignación en proceso, espera un momento.');
            return;
        }

        if (!confirm(`¿Estás seguro de que quieres asignarte el cliente "${nombreCliente}"?`)) {
            return;
        }

        isAssigning = true;
        const boton = document.querySelector(`[data-cliente-id="${clienteId}"]`);
        const textoOriginal = boton.innerHTML;
        
        // Deshabilitar botón y cambiar texto
        boton.disabled = true;
        boton.innerHTML = '⏳ Asignando...';
        boton.classList.remove('hover:bg-green-700');
        boton.classList.add('bg-gray-400', 'cursor-not-allowed');

        try {
            const response = await fetch(`/clientes/${clienteId}/assign`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({})
            });

            const data = await response.json();

            if (data.success) {
                // Mostrar mensaje de éxito
                mostrarMensaje('Cliente asignado correctamente.', 'success');
                
                // Quitar la fila del cliente asignado con animación
                const fila = document.getElementById(`cliente-${clienteId}`);
                if (fila) {
                    fila.style.transition = 'all 0.3s ease-out';
                    fila.style.backgroundColor = '#dcfce7'; // Verde claro
                    setTimeout(() => {
                        fila.style.opacity = '0';
                        fila.style.transform = 'translateX(-100%)';
                        setTimeout(() => {
                            fila.remove();
                            verificarTablaVacia();
                        }, 300);
                    }, 500);
                }
                
                // Actualizar contador
                actualizarContador();
                
            } else {
                // Mostrar error específico
                mostrarMensaje(data.message || 'Este cliente ya fue asignado a otro asesor.', 'error');
                
                // Restaurar botón
                boton.disabled = false;
                boton.innerHTML = textoOriginal;
                boton.classList.add('hover:bg-green-700');
                boton.classList.remove('bg-gray-400', 'cursor-not-allowed');
            }
        } catch (error) {
            console.error('Error:', error);
            mostrarMensaje('Error de conexión. Intenta nuevamente.', 'error');
            
            // Restaurar botón en caso de error
            boton.disabled = false;
            boton.innerHTML = textoOriginal;
            boton.classList.add('hover:bg-green-700');
            boton.classList.remove('bg-gray-400', 'cursor-not-allowed');
        } finally {
            isAssigning = false;
        }
    }

    function mostrarMensaje(texto, tipo) {
        // Remover mensajes anteriores
        const mensajesAnteriores = document.querySelectorAll('#success-message, #error-message, .mensaje-dinamico');
        mensajesAnteriores.forEach(msg => msg.remove());

        // Crear nuevo mensaje
        const mensaje = document.createElement('div');
        mensaje.className = `px-4 py-3 rounded mb-4 mensaje-dinamico ${
            tipo === 'success' 
                ? 'bg-green-100 border border-green-400 text-green-700' 
                : 'bg-red-100 border border-red-400 text-red-700'
        }`;
        mensaje.textContent = texto;

        // Insertar al inicio del container
        const container = document.querySelector('.container');
        const titulo = container.querySelector('h1');
        titulo.insertAdjacentElement('afterend', mensaje);

        // Auto-remover después de 5 segundos
        setTimeout(() => {
            if (mensaje.parentNode) {
                mensaje.style.transition = 'opacity 0.3s ease-out';
                mensaje.style.opacity = '0';
                setTimeout(() => mensaje.remove(), 300);
            }
        }, 5000);
    }

    function verificarTablaVacia() {
        const filas = document.querySelectorAll('.cliente-row');
        if (filas.length === 0) {
            const tbody = document.getElementById('clientes-tbody');
            tbody.innerHTML = `
                <tr>
                    <td colspan="6" class="px-6 py-12 text-center">
                        <div class="text-gray-400 text-4xl mb-4">🎉</div>
                        <h3 class="text-lg font-medium text-gray-800 mb-2">¡No hay más clientes disponibles!</h3>
                        <p class="text-gray-600">Todos los clientes han sido asignados.</p>
                        <button onclick="refreshClientes()" 
                                class="mt-4 bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 transition">
                            🔄 Verificar nuevamente
                        </button>
                    </td>
                </tr>
            `;
        }
    }

    function actualizarContador() {
        // Actualizar contador de clientes disponibles si existe
        const contador = document.querySelector('.text-blue-600');
        if (contador) {
            const filas = document.querySelectorAll('.cliente-row');
            contador.textContent = filas.length;
        }
    }

    async function refreshClientes() {
        try {
            const botonRefresh = document.querySelector('button[onclick="refreshClientes()"]');
            const textoOriginal = botonRefresh.innerHTML;
            
            botonRefresh.disabled = true;
            botonRefresh.innerHTML = '⏳ Actualizando...';
            
            // Simple reload para obtener datos frescos
            window.location.reload();
            
        } catch (error) {
            console.error('Error al actualizar:', error);
            mostrarMensaje('Error al actualizar la lista', 'error');
            
            // Restaurar botón
            botonRefresh.disabled = false;
            botonRefresh.innerHTML = textoOriginal;
        }
    }

    function configurarAutoRefresh() {
        const checkbox = document.getElementById('auto-refresh');
        
        // Limpiar intervalo anterior
        if (autoRefreshInterval) {
            clearInterval(autoRefreshInterval);
        }
        
        if (checkbox.checked) {
            autoRefreshInterval = setInterval(() => {
                // Solo refrescar si no hay asignación en proceso
                if (!isAssigning) {
                    refreshClientes();
                }
            }, 10000); // 10 segundos
        }
    }

    // Configurar al cargar la página
    document.addEventListener('DOMContentLoaded', function() {
        actualizarUltimoRefresh();
        configurarAutoRefresh();
        
        // Escuchar cambios en el checkbox
        document.getElementById('auto-refresh').addEventListener('change', configurarAutoRefresh);
        
        // Deshabilitar auto-refresh cuando el usuario está inactivo
        let inactivityTimer;
        function resetInactivityTimer() {
            clearTimeout(inactivityTimer);
            inactivityTimer = setTimeout(() => {
                const checkbox = document.getElementById('auto-refresh');
                if (checkbox.checked) {
                    checkbox.checked = false;
                    configurarAutoRefresh();
                    mostrarMensaje('Auto-actualización pausada por inactividad', 'info');
                }
            }, 300000); // 5 minutos de inactividad
        }
        
        // Detectar actividad del usuario
        document.addEventListener('click', resetInactivityTimer);
        document.addEventListener('keypress', resetInactivityTimer);
        resetInactivityTimer();
    });

    // Limpiar interval al salir de la página
    window.addEventListener('beforeunload', function() {
        if (autoRefreshInterval) {
            clearInterval(autoRefreshInterval);
        }
    });

    // Detectar cuando la página pierde/gana foco
    document.addEventListener('visibilitychange', function() {
        if (document.hidden) {
            // Página no visible - pausar auto-refresh
            if (autoRefreshInterval) {
                clearInterval(autoRefreshInterval);
            }
        } else {
            // Página visible - reanudar auto-refresh si está activado
            const checkbox = document.getElementById('auto-refresh');
            if (checkbox.checked) {
                configurarAutoRefresh();
                // Refrescar inmediatamente al volver a la pestaña
                setTimeout(refreshClientes, 1000);
            }
        }
    });
</script>

@endsection