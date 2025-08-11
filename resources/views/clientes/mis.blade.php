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
                        <td class="border px-4 py-2 capitalize">
                            @if($cliente->estado === 'aprobado')
                                <span class="text-green-600 font-semibold">Aprobado</span>
                            @elseif($cliente->estado === 'rechazado')
                                <span class="text-red-600 font-semibold">Rechazado</span>
                            @elseif($cliente->estado === 'en proceso')
                                <span class="text-yellow-600 font-semibold">En Proceso</span>
                            @else
                                <span class="text-gray-600 font-semibold">{{ $cliente->estado }}</span>
                            @endif
                        </td>
                        <td class="border px-4 py-2 space-x-2">
                            <a href="{{ route('clientes.show', $cliente->id) }}"
                               class="inline-block bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded">
                                Ver
                            </a>
                            <a href="#"
                               class="inline-block bg-green-500 hover:bg-green-600 text-white px-3 py-1 rounded">
                                Finalizar
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection
