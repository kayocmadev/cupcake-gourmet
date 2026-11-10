<?php

namespace Tests\Unit;

use App\Models\Pedido;
use PHPUnit\Framework\TestCase;

class PedidoTest extends TestCase
{
    private function pedidoComStatus(string $status): Pedido
    {
        return new Pedido(['status' => $status, 'taxa_entrega' => 8, 'total' => 38]);
    }

    public function test_so_pode_cancelar_antes_de_sair_para_entrega(): void
    {
        $this->assertTrue($this->pedidoComStatus(Pedido::RECEBIDO)->podeSerCancelado());
        $this->assertTrue($this->pedidoComStatus(Pedido::EM_PREPARO)->podeSerCancelado());
        $this->assertFalse($this->pedidoComStatus(Pedido::SAIU_PARA_ENTREGA)->podeSerCancelado());
        $this->assertFalse($this->pedidoComStatus(Pedido::ENTREGUE)->podeSerCancelado());
        $this->assertFalse($this->pedidoComStatus(Pedido::CANCELADO)->podeSerCancelado());
    }

    public function test_proximo_status_segue_o_fluxo_normal(): void
    {
        $this->assertSame(Pedido::EM_PREPARO, $this->pedidoComStatus(Pedido::RECEBIDO)->proximoStatus());
        $this->assertSame(Pedido::SAIU_PARA_ENTREGA, $this->pedidoComStatus(Pedido::EM_PREPARO)->proximoStatus());
        $this->assertSame(Pedido::ENTREGUE, $this->pedidoComStatus(Pedido::SAIU_PARA_ENTREGA)->proximoStatus());
        $this->assertNull($this->pedidoComStatus(Pedido::ENTREGUE)->proximoStatus());
        $this->assertNull($this->pedidoComStatus(Pedido::CANCELADO)->proximoStatus());
    }

    public function test_subtotal_desconta_a_taxa_de_entrega(): void
    {
        $this->assertSame(30.0, $this->pedidoComStatus(Pedido::RECEBIDO)->subtotal());
    }

    public function test_rotulo_do_status_e_legivel(): void
    {
        $this->assertSame('Saiu para entrega', $this->pedidoComStatus(Pedido::SAIU_PARA_ENTREGA)->rotuloStatus());
    }
}
