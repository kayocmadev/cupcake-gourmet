@extends('layouts.app')

@section('titulo', 'Carrinho · Cupcake Gourmet')

@section('conteudo')
    <div class="conteiner pagina">
        <ol class="passos-compra" aria-label="Etapas da compra">
            <li class="atual"><span class="numero">1</span> Carrinho</li>
            <li><span class="numero">2</span> Entrega e pagamento</li>
            <li><span class="numero">3</span> Confirmação</li>
        </ol>

        <h1>Seu carrinho</h1>

        @if ($itens->isEmpty())
            <div class="vazio">
                <h2>Seu carrinho está vazio</h2>
                <p>Que tal escolher alguns sabores?</p>
                <a class="botao" href="{{ route('cardapio.index') }}#cardapio">Ver o cardápio</a>
            </div>
        @else
            <div class="duas-colunas">
                <ul class="linhas-carrinho">
                    @foreach ($itens as $item)
                        @php($p = $item['produto'])
                        <li class="linha-carrinho">
                            @if ($p->urlImagem())
                                <img src="{{ $p->urlImagem() }}" alt="" width="84" height="84">
                            @else
                                <div class="foto-vazia">🧁</div>
                            @endif
                            <div class="linha-info">
                                <h3><a href="{{ route('cardapio.show', $p) }}">{{ $p->nome }}</a></h3>
                                <span class="unitario">R$ {{ number_format((float) $p->preco, 2, ',', '.') }} cada</span>
                                <div class="linha-acoes">
                                    <form method="POST" action="{{ route('carrinho.atualizar', $p) }}" class="acoes">
                                        @csrf
                                        @method('PATCH')
                                        <div class="quantidade" data-quantidade>
                                            <button type="button" data-passo="-1" aria-label="Diminuir quantidade"><svg class="icone"><use href="#i-menos"/></svg></button>
                                            <label for="qtd-{{ $p->id }}" class="so-leitor">Quantidade de {{ $p->nome }}</label>
                                            <input type="number" id="qtd-{{ $p->id }}" name="quantidade" value="{{ $item['quantidade'] }}"
                                                   min="{{ config('loja.quantidade_minima') }}" max="{{ config('loja.quantidade_maxima') }}" inputmode="numeric">
                                            <button type="button" data-passo="1" aria-label="Aumentar quantidade"><svg class="icone"><use href="#i-mais"/></svg></button>
                                        </div>
                                        <button type="submit" class="botao botao-contorno botao-pequeno">Atualizar</button>
                                    </form>
                                    <form method="POST" action="{{ route('carrinho.remover', $p) }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="link-discreto" data-confirmar="Remover {{ $p->nome }} do carrinho?">Remover</button>
                                    </form>
                                    <span class="linha-subtotal">R$ {{ number_format($item['subtotal'], 2, ',', '.') }}</span>
                                </div>
                            </div>
                        </li>
                    @endforeach
                </ul>

                <aside class="lateral">
                    <div class="cartao-resumo">
                        <h2>Resumo do pedido</h2>

                        @if ($faltaFreteGratis > 0)
                            <div class="frete-barra">
                                Faltam <strong>R$ {{ number_format($faltaFreteGratis, 2, ',', '.') }}</strong> para ganhar frete grátis.
                                <div class="trilha"><span style="width: {{ min(100, round($subtotal / $freteGratisAcima * 100)) }}%"></span></div>
                            </div>
                        @else
                            <div class="frete-barra completo">
                                <strong>Você ganhou frete grátis!</strong>
                                <div class="trilha"><span style="width:100%"></span></div>
                            </div>
                        @endif

                        <div class="resumo">
                            <dl>
                                <dt>Subtotal</dt><dd>R$ {{ number_format($subtotal, 2, ',', '.') }}</dd>
                                <dt>Entrega</dt><dd @class(['gratis' => $taxa == 0])>{{ $taxa == 0 ? 'Grátis' : 'R$ '.number_format($taxa, 2, ',', '.') }}</dd>
                                <dt class="total">Total</dt><dd class="total">R$ {{ number_format($subtotal + $taxa, 2, ',', '.') }}</dd>
                            </dl>
                        </div>

                        <a class="botao botao-bloco" style="margin-top:20px" href="{{ route('pedidos.create') }}">Finalizar pedido <svg class="icone"><use href="#i-seta"/></svg></a>
                        <a class="botao botao-contorno botao-bloco" style="margin-top:10px" href="{{ route('cardapio.index') }}#cardapio">Continuar comprando</a>
                    </div>
                </aside>
            </div>
        @endif
    </div>
@endsection
