@extends('layouts.app')

@section('titulo', 'Finalizar pedido · Cupcake Gourmet')

@section('conteudo')
    <div class="conteiner pagina">
        <ol class="passos-compra" aria-label="Etapas da compra">
            <li class="feito"><span class="numero"><svg class="icone" style="width:14px;height:14px"><use href="#i-certo"/></svg></span> Carrinho</li>
            <li class="atual"><span class="numero">2</span> Entrega e pagamento</li>
            <li><span class="numero">3</span> Confirmação</li>
        </ol>

        <h1>Finalizar pedido</h1>

        <div class="duas-colunas">
            <form class="formulario" method="POST" action="{{ route('pedidos.store') }}" novalidate>
                @csrf

                <h2>Endereço de entrega</h2>
                <label for="endereco_entrega">Rua, número e bairro</label>
                <input type="text" id="endereco_entrega" name="endereco_entrega" value="{{ old('endereco_entrega') }}"
                       placeholder="Ex.: Rua das Flores, 100, Centro" autocomplete="street-address"
                       class="@error('endereco_entrega') campo-erro @enderror" required>

                <h2>Forma de pagamento</h2>
                <p class="ajuda">O pagamento é feito na entrega.</p>
                <div class="opcoes-pagamento" role="radiogroup" aria-label="Forma de pagamento">
                    @foreach ($formasPagamento as $valor => $rotulo)
                        <label class="opcao-pagamento">
                            <input type="radio" name="forma_pagamento" value="{{ $valor }}" @checked(old('forma_pagamento') === $valor) required>
                            <span><svg class="icone"><use href="#i-pagamento"/></svg> {{ $rotulo }}</span>
                        </label>
                    @endforeach
                </div>

                <label for="observacao">Observação (opcional)</label>
                <textarea id="observacao" name="observacao" maxlength="255" placeholder="Ex.: tocar o interfone">{{ old('observacao') }}</textarea>

                <button type="submit" class="botao botao-bloco">Confirmar pedido</button>
                <p style="text-align:center;margin:14px 0 0"><a href="{{ route('carrinho.index') }}">← Voltar ao carrinho</a></p>
            </form>

            <aside class="lateral">
                <div class="cartao-resumo">
                    <h2>Seu pedido</h2>
                    <ul class="resumo-itens">
                        @foreach ($itens as $item)
                            <li>
                                @if ($item['produto']->urlImagem())
                                    <img src="{{ $item['produto']->urlImagem() }}" alt="" width="48" height="48">
                                @else
                                    <div class="foto-vazia">🧁</div>
                                @endif
                                <span>{{ $item['quantidade'] }} × {{ $item['produto']->nome }}</span>
                                <strong>R$ {{ number_format($item['subtotal'], 2, ',', '.') }}</strong>
                            </li>
                        @endforeach
                    </ul>
                    <div class="resumo">
                        <dl>
                            <dt>Subtotal</dt><dd>R$ {{ number_format($subtotal, 2, ',', '.') }}</dd>
                            <dt>Entrega</dt><dd @class(['gratis' => $taxa == 0])>{{ $taxa == 0 ? 'Grátis' : 'R$ '.number_format($taxa, 2, ',', '.') }}</dd>
                            <dt class="total">Total</dt><dd class="total">R$ {{ number_format($subtotal + $taxa, 2, ',', '.') }}</dd>
                        </dl>
                    </div>
                </div>
            </aside>
        </div>
    </div>
@endsection
