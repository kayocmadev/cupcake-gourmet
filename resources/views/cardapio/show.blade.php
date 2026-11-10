@extends('layouts.app')

@section('titulo', $produto->nome.' · Cupcake Gourmet')

@section('conteudo')
    <p><a href="{{ route('cardapio.index') }}">← Voltar ao cardápio</a></p>

    <article class="detalhe">
        <div class="cartao-imagem grande" aria-hidden="true">🧁</div>
        <h1>{{ $produto->nome }}</h1>
        <p class="sabor">Sabor: {{ $produto->sabor }}</p>
        <p>{{ $produto->descricao }}</p>
        <p class="preco">R$ {{ number_format((float) $produto->preco, 2, ',', '.') }}</p>
        @include('cardapio.form-adicionar', ['produto' => $produto])
    </article>
@endsection
