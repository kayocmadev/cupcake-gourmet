@extends('layouts.app')

@section('titulo', 'Algo deu errado · Cupcake Gourmet')

@section('conteudo')
    <section class="vazio">
        <h1>Algo deu errado</h1>
        <p>Tivemos um problema inesperado. Tente novamente em instantes.</p>
        <p><a class="botao" href="{{ route('cardapio.index') }}">Voltar ao cardápio</a></p>
    </section>
@endsection
