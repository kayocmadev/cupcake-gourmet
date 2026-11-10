@extends('layouts.app')

@section('titulo', $produto->nome.' · Cupcake Gourmet')
@section('descricao', \Illuminate\Support\Str::limit($produto->descricao, 150))

@section('conteudo')
    <div class="conteiner pagina">
        <nav class="migalhas" aria-label="Você está em">
            <a href="{{ route('cardapio.index') }}">Cardápio</a> ›
            <a href="{{ route('cardapio.index', ['categoria' => $produto->categoria]) }}#cardapio">{{ $produto->rotuloCategoria() }}</a> ›
            {{ $produto->nome }}
        </nav>

        <article class="detalhe">
            <div class="detalhe-foto">
                @if ($produto->urlImagem())
                    <img src="{{ $produto->urlImagem() }}" alt="{{ $produto->nome }}" width="900" height="900">
                @else
                    <div class="foto-vazia">🧁</div>
                @endif
            </div>
            <div class="detalhe-info">
                <span class="sobrancelha">{{ $produto->rotuloCategoria() }}</span>
                <h1>{{ $produto->nome }}</h1>
                <p class="descricao">{{ $produto->descricao }}</p>
                <span class="preco">R$ {{ number_format((float) $produto->preco, 2, ',', '.') }} <small>cada</small></span>

                @include('partials.form-adicionar', ['produto' => $produto])

                <ul class="ficha">
                    <li><svg class="icone"><use href="#i-certo"/></svg> Sabor principal: {{ $produto->sabor }}</li>
                    <li><svg class="icone"><use href="#i-entrega"/></svg> Frete grátis em pedidos a partir de R$ {{ number_format((float) config('loja.frete_gratis_acima'), 0, ',', '.') }}</li>
                    <li><svg class="icone"><use href="#i-pagamento"/></svg> Pagamento na entrega</li>
                </ul>
            </div>
        </article>

        @if ($parecidos->isNotEmpty())
            <section style="margin-top:64px">
                <h2>Você também pode gostar</h2>
                <ul class="grade">
                    @foreach ($parecidos as $parecido)
                        @include('partials.cartao-cupcake', ['produto' => $parecido])
                    @endforeach
                </ul>
            </section>
        @endif
    </div>
@endsection
