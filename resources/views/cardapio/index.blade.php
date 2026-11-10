@extends('layouts.app')

@section('titulo', 'Cardápio · Cupcake Gourmet')

@section('conteudo')
    <section class="apresentacao">
        <h1>Cupcakes gourmet feitos com carinho</h1>
        <p>Escolha seus sabores favoritos e receba em casa.</p>
    </section>

    <form class="busca" method="GET" action="{{ route('cardapio.index') }}" role="search">
        <label for="busca">Buscar por nome ou sabor</label>
        <div class="busca-linha">
            <input type="search" id="busca" name="busca" value="{{ $busca }}" placeholder="Ex.: chocolate">
            <button type="submit" class="botao">Buscar</button>
        </div>
    </form>

    @if ($produtos->isEmpty())
        <p class="vazio">
            @if ($busca)
                Nenhum cupcake encontrado para “{{ $busca }}”.
                <a href="{{ route('cardapio.index') }}">Ver todo o cardápio</a>
            @else
                Nenhum cupcake encontrado.
            @endif
        </p>
    @else
        <ul class="grade">
            @foreach ($produtos as $produto)
                <li class="cartao">
                    <div class="cartao-imagem" aria-hidden="true">🧁</div>
                    <h2><a href="{{ route('cardapio.show', $produto) }}">{{ $produto->nome }}</a></h2>
                    <p class="sabor">{{ $produto->sabor }}</p>
                    <p class="preco">R$ {{ number_format((float) $produto->preco, 2, ',', '.') }}</p>
                    @include('cardapio.form-adicionar', ['produto' => $produto])
                </li>
            @endforeach
        </ul>
    @endif
@endsection
