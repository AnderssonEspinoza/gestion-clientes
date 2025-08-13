@extends('layouts.app')

@section('content')
<div class="container mx-auto p-4 flex justify-center">
    <div class="bg-white shadow-lg rounded-lg max-w-md w-full overflow-hidden">
        <!-- Imagen de perfil -->
        <div class="flex justify-center bg-gray-100 p-6">
            <img 
                src="https://ui-avatars.com/api/?name={{ urlencode($cliente->nombre) }}&size=150&background=random"
                alt="Foto de {{ $cliente->nombre }}"
                class="rounded-full border-4 border-white shadow-md w-32 h-32 object-cover"
            >
        </div>

        <!-- Información del cliente -->
        <div class="p-6">
            <h1 class="text-2xl font-bold text-center mb-4">{{ $cliente->nombre }}</h1>

            <div class="space-y-4">
                <div class="flex items-center">
                    <span class="font-semibold w-28">📧 Email:</span>
                    <span class="text-gray-700 break-all">{{ $cliente->email }}</span>
                </div>
                <div class="flex items-center">
                    <span class="font-semibold w-28">📱 Teléfono:</span>
                    <span class="text-gray-700">{{ $cliente->telefono ?? '-' }}</span>
                </div>
                <div class="flex items-center">
                    <span class="font-semibold w-28">📅 Creado:</span>
                    <span class="text-gray-700">{{ $cliente->created_at->format('d/m/Y H:i') }}</span>
                </div>
            </div>

            <!-- Botones de acción -->
            <div class="mt-6 flex flex-col sm:flex-row gap-3">
                <!-- AQUÍ CAMBIAS LA RUTA DE VOLVER -->
                <a href="{{ route('mis-clientes') }}"
                    class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded w-full text-center">
                   ⬅ Volver a Mis Clientes
                </a>

                <!-- Como es "mis clientes", probablemente no necesites el botón de asignar -->
                <!-- Si lo necesitas, manténlo -->
                <form method="POST" action="{{ route('clientes.assign', $cliente->id) }}" class="w-full">
                    @csrf
                    <button type="submit"
                             class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded w-full">
                        ✅ Asignarme cliente
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection