<?php

namespace App\Policies;

use App\Models\Pedido;
use App\Models\User;

class PedidoPolicy
{
    /** Cliente só vê os próprios pedidos (RNF06); o administrador vê todos. */
    public function view(User $usuario, Pedido $pedido): bool
    {
        return $usuario->is_admin || $pedido->user_id === $usuario->id;
    }

    /** Só o dono do pedido pode cancelá-lo (RF08). */
    public function cancel(User $usuario, Pedido $pedido): bool
    {
        return $pedido->user_id === $usuario->id;
    }
}
