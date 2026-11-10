@extends('layouts.app')

@section('titulo', 'Sessão expirada · Cupcake Gourmet')

@section('conteudo')
    <div class="conteiner">
        <section class="erro-pagina">
            <div class="codigo">419</div>
            <h1>Sessão expirada</h1>
            <p>Sua sessão expirou por inatividade. Volte à página anterior, atualize e tente de novo.</p>
            <p><a class="botao" href="{{ route('cardapio.index') }}">Voltar ao cardápio</a></p>
        </section>
    </div>
@endsection
