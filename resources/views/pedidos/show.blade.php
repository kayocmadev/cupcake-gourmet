@extends('layouts.app')

@section('titulo', 'Pedido #'.$pedido->id.' · Cupcake Gourmet')

@section('conteudo')
    <div class="conteiner pagina">
        <nav class="migalhas" aria-label="Você está em"><a href="{{ route('pedidos.index') }}">Meus pedidos</a> › Pedido #{{ $pedido->id }}</nav>

        <div class="painel-cabecalho">
            <div>
                <h1 style="margin-bottom:4px">Pedido #{{ $pedido->id }}</h1>
                <span class="meta" style="color:var(--tinta-suave)">Feito em {{ $pedido->created_at->format('d/m/Y \à\s H:i') }}</span>
            </div>
            <span class="selo selo-{{ $pedido->status }}" style="font-size:.95rem;padding:8px 16px">{{ $pedido->rotuloStatus() }}</span>
        </div>

        <div class="cartao-resumo" style="margin-bottom:28px">
            <h2>Andamento</h2>
            @if ($pedido->status === \App\Models\Pedido::CANCELADO)
                <div class="aviso aviso-erro"><svg class="icone"><use href="#i-aviso"/></svg><div>Este pedido foi cancelado.</div></div>
            @else
                @php($posicaoAtual = array_search($pedido->status, \App\Models\Pedido::FLUXO, true))
                <ol class="etapas">
                    @foreach (\App\Models\Pedido::FLUXO as $indice => $etapa)
                        <li class="{{ $indice < $posicaoAtual ? 'feito' : '' }} {{ $indice === $posicaoAtual ? 'atual' : '' }} {{ $etapa === 'entregue' && $indice === $posicaoAtual ? 'feito' : '' }}"
                            @if ($indice === $posicaoAtual) aria-current="step" @endif>
                            <span class="ponto"><svg class="icone" style="width:18px;height:18px"><use href="#i-{{ $indice < $posicaoAtual || ($etapa === 'entregue' && $indice === $posicaoAtual) ? 'certo' : ['recebido' => 'relogio', 'em_preparo' => 'caixa', 'saiu_para_entrega' => 'entrega', 'entregue' => 'certo'][$etapa] }}"/></svg></span>
                            <div>
                                <strong>{{ \App\Models\Pedido::ROTULOS[$etapa] }}</strong>
                                @if ($indice === $posicaoAtual)<small>etapa atual</small>@endif
                            </div>
                        </li>
                    @endforeach
                </ol>
            @endif
        </div>

        <div class="duas-colunas">
            <div>
                <h2>Itens do pedido</h2>
                <ul class="linhas-carrinho">
                    @foreach ($pedido->itens as $item)
                        <li class="linha-carrinho">
                            @if ($item->produto->urlImagem())
                                <img src="{{ $item->produto->urlImagem() }}" alt="" width="84" height="84">
                            @else
                                <div class="foto-vazia">🧁</div>
                            @endif
                            <div class="linha-info">
                                <h3>{{ $item->produto->nome }}</h3>
                                <span class="unitario">{{ $item->quantidade }} × R$ {{ number_format((float) $item->preco_unitario, 2, ',', '.') }}</span>
                                <span class="linha-subtotal" style="margin-left:0">R$ {{ number_format($item->subtotal(), 2, ',', '.') }}</span>
                            </div>
                        </li>
                    @endforeach
                </ul>

                <h2 style="margin-top:32px">Entrega e pagamento</h2>
                <p><strong>Endereço:</strong> {{ $pedido->endereco_entrega }}</p>
                <p><strong>Pagamento:</strong> {{ $formasPagamento[$pedido->forma_pagamento] ?? $pedido->forma_pagamento }}</p>
                @if ($pedido->observacao)
                    <p><strong>Observação:</strong> {{ $pedido->observacao }}</p>
                @endif
            </div>

            <aside class="lateral">
                <div class="cartao-resumo">
                    <h2>Valores</h2>
                    <div class="resumo">
                        <dl>
                            <dt>Subtotal</dt><dd>R$ {{ number_format($pedido->subtotal(), 2, ',', '.') }}</dd>
                            <dt>Entrega</dt><dd @class(['gratis' => (float) $pedido->taxa_entrega == 0])>{{ (float) $pedido->taxa_entrega == 0 ? 'Grátis' : 'R$ '.number_format((float) $pedido->taxa_entrega, 2, ',', '.') }}</dd>
                            <dt class="total">Total</dt><dd class="total">R$ {{ number_format((float) $pedido->total, 2, ',', '.') }}</dd>
                        </dl>
                    </div>

                    @can('cancel', $pedido)
                        @if ($pedido->podeSerCancelado())
                            <form method="POST" action="{{ route('pedidos.cancelar', $pedido) }}" style="margin-top:20px">
                                @csrf
                                <button type="submit" class="botao botao-perigo botao-bloco" data-confirmar="Cancelar o pedido #{{ $pedido->id }}?">Cancelar pedido</button>
                                <p class="ajuda" style="text-align:center">Você pode cancelar enquanto o pedido não sair para entrega.</p>
                            </form>
                        @endif
                    @endcan
                </div>
            </aside>
        </div>
    </div>
@endsection
