@extends('layouts.app')

@section('content')
<div class="container mx-auto p-4">
    <h1 class="text-3xl font-bold mb-6 text-gray-800 dark:text-gray-100">
        📋 Clientes Disponibles
    </h1>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4 dark:bg-green-800 dark:text-green-200">
            {{ session('success') }}
        </div>
    @endif

    @if($clientesDisponibles->isEmpty())
        <p class="text-gray-600 dark:text-gray-300">No hay clientes disponibles.</p>
    @else
    <div class="overflow-x-auto bg-white dark:bg-gray-800 rounded-lg shadow-md">
        <table class="min-w-full text-sm text-left text-gray-700 dark:text-gray-200">
            <thead class="bg-gray-100 dark:bg-gray-700 border-b">
                <tr>
                    <th class="px-6 py-3">Nombre</th>
                    <th class="px-6 py-3">Email</th>
                    <th class="px-6 py-3">Teléfono</th>
                    <th class="px-6 py-3">Estado</th>
                    <th class="px-6 py-3 text-center">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($clientesDisponibles as $cliente)
                <tr id="cliente-{{ $cliente->id }}" class="border-b hover:bg-gray-50 dark:hover:bg-gray-600 transition">
                    <td class="px-6 py-4 font-medium">{{ $cliente->nombre }}</td>
                    <td class="px-6 py-4">{{ $cliente->email }}</td>
                    <td class="px-6 py-4">{{ $cliente->telefono ?? '-' }}</td>
                    <td class="px-6 py-4">
                        @php
                            $estilos = [
                                'pendiente' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-700 dark:text-yellow-200',
                                'aprobado' => 'bg-green-100 text-green-800 dark:bg-green-700 dark:text-green-200',
                                'rechazado' => 'bg-red-100 text-red-800 dark:bg-red-700 dark:text-red-200',
                            ];
                            $iconos = [
                                'pendiente' => '⏳',
                                'aprobado' => '✅',
                                'rechazado' => '❌',
                            ];
                        @endphp
                        <span class="px-3 py-1 rounded-full text-xs font-semibold flex items-center gap-1 w-fit {{ $estilos[$cliente->estado] ?? 'bg-gray-100 text-gray-800 dark:bg-gray-600 dark:text-gray-200' }}">
                            {{ $iconos[$cliente->estado] ?? 'ℹ️' }} {{ ucfirst($cliente->estado) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 flex flex-wrap gap-3 justify-center">
                        <!-- Botón Ver -->
                        <a href="{{ route('clientes.show', $cliente->id) }}" 
                           class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-green text-sm font-medium rounded-lg shadow-md transition">
                           🔍 Ver
                        </a>

                        <!-- Botón Asignarme -->
                        <button 
                            onclick="asignarCliente({{ $cliente->id }})"
                            @if($cliente->usuario_asignado_id) disabled @endif
                            class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-green text-sm font-medium rounded-lg shadow-md transition disabled:bg-gray-400 disabled:cursor-not-allowed">
                            📌 Asignarme
                        </button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif
</div>

<script>
    function asignarCliente(clienteId) {
        if (!confirm('¿Asignarte este cliente?')) return;

        fetch(`/clientes/${clienteId}/assign`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({})
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                alert('Cliente asignado correctamente.');
                // Quitar la fila del cliente asignado
                const fila = document.getElementById(`cliente-${clienteId}`);
                if (fila) fila.remove();
            } else {
                alert('Error al asignar cliente.');
            }
        })
        .catch(() => alert('Error en la conexión.'));
    }
</script>

@endsection
