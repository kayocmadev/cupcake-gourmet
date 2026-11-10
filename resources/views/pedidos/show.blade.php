@extends('layouts.app')

@section('titulo', 'Pedido #'.$pedido->id.' · Cupcake Gourmet')

@section('conteudo')
    <p><a href="{{ route('pedidos.index') }}">← Meus pedidos</a></p>

    <h1>Pedido #{{ $pedido->id }}
        <span class="selo selo-{{ $pedido->status }}">{{ $pedido->rotuloStatus() }}</span>
    </h1>
    <p>Feito em {{ $pedido->created_at->format('d/m/Y \à\s H:i') }}</p>

    <h2>Andamento</h2>
    @if ($pedido->status === \App\Models\Pedido::CANCELADO)
        <p class="aviso aviso-erro">Este pedido foi cancelado.</p>
    @else
        @php($posicaoAtual = array_search($pedido->status, \App\Models\Pedido::FLUXO, true))
        <ol class="linha-do-tempo">
            @foreach (\App\Models\Pedido::FLUXO as $indice => $etapa)
                <li class="{{ $indice <= $posicaoAtual ? 'feito' : '' }} {{ $indice === $posicaoAtual ? 'atual' : '' }}"
                    @if ($indice === $posicaoAtual) aria-current="step" @endif>
                    {{ \App\Models\Pedido::ROTULOS[$etapa] }}
                </li>
            @endforeach
        </ol>
    @endif

    <h2>Itens</h2>
    <table class="tabela">
        <thead>
            <tr>
                <th>Cupcake</th>
                <th class="num">Qtd.</th>
                <th class="num">Preço</th>
                <th class="num">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($pedido->itens as $item)
                <tr>
                    <td data-rotulo="Cupcake">{{ $item->produto->nome }}</td>
                    <td class="num" data-rotulo="Qtd.">{{ $item->quantidade }}</td>
                    <td class="num" data-rotulo="Preço">R$ {{ number_format((float) $item->preco_unitario, 2, ',', '.') }}</td>
                    <td class="num" data-rotulo="Subtotal">R$ {{ number_format($item->subtotal(), 2, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="resumo">
        <dl>
            <dt>Subtotal</dt><dd>R$ {{ number_format($pedido->subtotal(), 2, ',', '.') }}</dd>
            <dt>Taxa de entrega</dt><dd>R$ {{ number_format((float) $pedido->taxa_entrega, 2, ',', '.') }}</dd>
            <dt class="total">Total</dt><dd class="total">R$ {{ number_format((float) $pedido->total, 2, ',', '.') }}</dd>
        </dl>
    </div>

    <h2>Entrega</h2>
    <p><strong>Endereço:</strong> {{ $pedido->endereco_entrega }}</p>
    <p><strong>Pagamento:</strong> {{ $formasPagamento[$pedido->forma_pagamento] ?? $pedido->forma_pagamento }}</p>
    @if ($pedido->observacao)
        <p><strong>Observação:</strong> {{ $pedido->observacao }}</p>
    @endif

    @can('cancel', $pedido)
        @if ($pedido->podeSerCancelado())
            <form method="POST" action="{{ route('pedidos.cancelar', $pedido) }}">
                @csrf
                <button type="submit" class="botao botao-perigo" data-confirmar="Cancelar o pedido #{{ $pedido->id }}?">Cancelar pedido</button>
            </form>
        @endif
    @endcan
@endsection
