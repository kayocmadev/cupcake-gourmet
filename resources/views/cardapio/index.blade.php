@extends('layouts.app')

@section('titulo', 'Cupcake Gourmet · cupcakes artesanais com entrega em casa')

@section('conteudo')
    <section class="destaque">
        <div class="conteiner destaque-grade">
            <div class="destaque-texto">
                <span class="sobrancelha">Feitos todos os dias</span>
                <h1>Cupcakes gourmet que viram <em>festa</em> em qualquer dia.</h1>
                <p>Massas macias, coberturas generosas e frutas frescas. Escolha seus sabores, monte o pedido em poucos toques e acompanhe a entrega até a sua porta.</p>
                <div class="destaque-acoes">
                    <a class="botao" href="#cardapio">Ver o cardápio <svg class="icone"><use href="#i-seta"/></svg></a>
                    <a class="botao botao-contorno" href="#como-funciona">Como funciona</a>
                </div>
                <ul class="destaque-selos">
                    <li><svg class="icone"><use href="#i-entrega"/></svg> Entrega em casa</li>
                    <li><svg class="icone"><use href="#i-pagamento"/></svg> Pague na entrega</li>
                </ul>
            </div>

            @if ($destaques->count() >= 3)
                <div class="colagem">
                    @foreach ($destaques->take(3) as $d)
                        <figure><img src="{{ $d->urlImagem() }}" alt="{{ $d->nome }}" @if ($loop->first) fetchpriority="high" @endif></figure>
                    @endforeach
                    <div class="selo-flutuante">
                        <svg class="icone" style="color:var(--dourado);width:28px;height:28px"><use href="#i-estrela"/></svg>
                        <div><strong>{{ $destaques->first()->nome }}</strong><span style="color:var(--tinta-suave)">um dos mais pedidos</span></div>
                    </div>
                </div>
            @endif
        </div>
    </section>

    <section class="beneficios" aria-label="Vantagens">
        <div class="conteiner">
            <ul>
                <li>
                    <span class="icone-caixa"><svg class="icone"><use href="#i-entrega"/></svg></span>
                    <div><strong>Frete grátis a partir de R$ {{ number_format((float) config('loja.frete_gratis_acima'), 0, ',', '.') }}</strong><span>Abaixo disso, taxa fixa de R$ {{ number_format((float) config('loja.taxa_entrega'), 2, ',', '.') }}</span></div>
                </li>
                <li>
                    <span class="icone-caixa"><svg class="icone"><use href="#i-pagamento"/></svg></span>
                    <div><strong>Pague só na entrega</strong><span>Pix, dinheiro ou cartão</span></div>
                </li>
                <li>
                    <span class="icone-caixa"><svg class="icone"><use href="#i-relogio"/></svg></span>
                    <div><strong>Acompanhe cada etapa</strong><span>Do preparo até a entrega</span></div>
                </li>
            </ul>
        </div>
    </section>

    <section class="secao" id="cardapio">
        <div class="conteiner">
            <div class="secao-titulo">
                <div>
                    <span class="sobrancelha">Cardápio</span>
                    <h2>Escolha o seu sabor</h2>
                </div>
                <p>{{ $produtos->count() }} {{ $produtos->count() === 1 ? 'cupcake' : 'cupcakes' }}{{ $filtrando ? ' encontrados' : ' no cardápio' }}.</p>
            </div>
        </div>

        <div class="filtros">
            <div class="conteiner filtros-linha">
                <nav class="chips" aria-label="Categorias">
                    <a class="chip" href="{{ route('cardapio.index', array_filter(['busca' => $busca])) }}#cardapio" @if (! $categoria) aria-current="true" @endif>Todos</a>
                    @foreach (\App\Models\Produto::CATEGORIAS as $chave => $rotulo)
                        <a class="chip" href="{{ route('cardapio.index', array_filter(['categoria' => $chave, 'busca' => $busca])) }}#cardapio" @if ($categoria === $chave) aria-current="true" @endif>{{ $rotulo }}</a>
                    @endforeach
                </nav>
                <form class="busca" method="GET" action="{{ route('cardapio.index') }}#cardapio" role="search">
                    @if ($categoria) <input type="hidden" name="categoria" value="{{ $categoria }}"> @endif
                    <label for="busca" class="so-leitor">Buscar por nome ou sabor</label>
                    <svg class="icone"><use href="#i-busca"/></svg>
                    <input type="search" id="busca" name="busca" value="{{ $busca }}" placeholder="Buscar por nome ou sabor">
                </form>
            </div>
        </div>

        <div class="conteiner">
            @if ($produtos->isEmpty())
                <div class="vazio">
                    <h3>{{ $busca ? 'Nenhum cupcake encontrado para “'.$busca.'”.' : 'Nenhum cupcake encontrado.' }}</h3>
                    <p>Tente outro nome ou sabor, ou volte para todo o cardápio.</p>
                    <a class="botao botao-contorno" href="{{ route('cardapio.index') }}#cardapio">Ver todo o cardápio</a>
                </div>
            @elseif ($filtrando)
                <ul class="grade">
                    @foreach ($produtos as $produto)
                        @include('partials.cartao-cupcake', ['produto' => $produto])
                    @endforeach
                </ul>
            @else
                @foreach (\App\Models\Produto::CATEGORIAS as $chave => $rotulo)
                    @if ($porCategoria->has($chave))
                        <div class="grupo-categoria">
                            <h2>{{ $rotulo }} <small>{{ $porCategoria[$chave]->count() }} sabores</small></h2>
                            <ul class="grade">
                                @foreach ($porCategoria[$chave] as $produto)
                                    @include('partials.cartao-cupcake', ['produto' => $produto])
                                @endforeach
                            </ul>
                        </div>
                    @endif
                @endforeach
            @endif
        </div>
    </section>

    <section class="secao" id="como-funciona" style="padding-top:0">
        <div class="conteiner">
            <div class="secao-titulo">
                <div>
                    <span class="sobrancelha">Passo a passo</span>
                    <h2>Do cardápio à sua porta em 3 passos</h2>
                </div>
            </div>
            <ol class="passos">
                <li>
                    <span class="icone-caixa"><svg class="icone"><use href="#i-busca"/></svg></span>
                    <h3>Escolha</h3>
                    <p>Navegue pelo cardápio, filtre por categoria ou busque um sabor e adicione os cupcakes ao carrinho.</p>
                </li>
                <li>
                    <span class="icone-caixa"><svg class="icone"><use href="#i-carrinho"/></svg></span>
                    <h3>Finalize</h3>
                    <p>Informe o endereço, escolha como vai pagar (na entrega) e confirme o pedido.</p>
                </li>
                <li>
                    <span class="icone-caixa"><svg class="icone"><use href="#i-caixa"/></svg></span>
                    <h3>Acompanhe</h3>
                    <p>Veja o pedido avançar: recebido, em preparo, saiu para entrega e entregue.</p>
                </li>
            </ol>
        </div>
    </section>

    <section class="conteiner" style="padding-bottom:24px">
        <div class="faixa-foto">
            <img src="{{ asset('img/site/caixa.jpg') }}" alt="" loading="lazy">
            <div class="faixa-foto-texto">
                <span class="sobrancelha" style="color:#f4c8d2">Entrega cuidadosa</span>
                <h2>Cada pedido chega em caixa, fresquinho.</h2>
                <p>Montamos o seu pedido na hora e acompanhamos a entrega até você receber.</p>
                <a class="botao" href="#cardapio">Fazer meu pedido</a>
            </div>
        </div>
    </section>
@endsection
