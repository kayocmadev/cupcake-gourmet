<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pedido;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PedidoAdminController extends Controller
{
    /** RF09: todos os pedidos, com filtro por status. */
    public function index(Request $request): View
    {
        $status = $request->query('status');

        $pedidos = Pedido::with('user')
            ->when(
                array_key_exists((string) $status, Pedido::ROTULOS),
                fn ($consulta) => $consulta->where('status', $status),
            )
            ->latest()
            ->get();

        return view('admin.pedidos.index', ['pedidos' => $pedidos, 'status' => $status]);
    }

    /** Avança o pedido para a próxima etapa do fluxo (RN07). */
    public function avancar(Pedido $pedido): RedirectResponse
    {
        if ($pedido->proximoStatus() === null) {
            return back()->with('erro', "O pedido #{$pedido->id} não pode mais avançar de status.");
        }

        $pedido->avancarStatus();

        return back()->with('sucesso', "Pedido #{$pedido->id} agora está: {$pedido->rotuloStatus()}.");
    }
}
