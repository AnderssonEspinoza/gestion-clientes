<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cliente;
use Illuminate\Support\Facades\Auth;


class ClienteController extends Controller
{
    //
    // Proteger para que solo usuarios logueados puedan acceder
    public function __construct()
    {
        $this->middleware('auth');
    }

    // Mostrar clientes disponibles (sin asignar)
    public function index()
    {
        // Clientes no asignados a ningún usuario
        $clientesDisponibles = Cliente::whereNull('user_id')->get();
        return view('clientes.index', compact('clientesDisponibles'));
    }

    // Mostrar detalle de un cliente
    public function show($id)
    {
        $cliente = Cliente::findOrFail($id);
        return view('clientes.show', compact('cliente'));
    }


    // Asignar cliente al usuario logueado y actualizar estado
    public function assign($id)
    {
        $cliente = Cliente::findOrFail($id);
        $cliente->user_id = Auth::id();
        $cliente->save();

        if (request()->expectsJson()) {
            return response()->json(['success' => true]);
        }

        return redirect()->route('clientes.mis')->with('success', 'Cliente asignado correctamente.');
    }



    // Mostrar clientes asignados al usuario
    public function mis()
    {
        $user = Auth::user();
        $misClientes = $user->clientesAsignados; // Usar relación en User

        return view('clientes.mis', compact('misClientes'));
    }

    public function misClientes()
    {
        $user = Auth::user();
        $misClientes = Cliente::where('user_id', $user->id)->get();

        return view('clientes.mis', compact('misClientes'));
    }



}
