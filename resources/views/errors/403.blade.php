@extends('layouts.app')

@section('titulo', 'Acesso negado · Cupcake Gourmet')

@section('conteudo')
    <div class="conteiner">
        <section class="erro-pagina">
            <div class="codigo">403</div>
            <h1>Acesso negado</h1>
            <p>Você não tem permissão para ver esta página.</p>
            <p><a class="botao" href="{{ route('cardapio.index') }}">Voltar ao cardápio</a></p>
        </section>
    </div>
@endsection
