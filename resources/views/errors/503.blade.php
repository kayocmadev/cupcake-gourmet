@extends('layouts.app')

@section('titulo', 'Estamos em manutenção · Cupcake Gourmet')

@section('conteudo')
    <div class="conteiner">
        <section class="erro-pagina">
            <div class="codigo">503</div>
            <h1>Estamos em manutenção</h1>
            <p>Voltamos em breve. Obrigado pela paciência.</p>
            <p><a class="botao" href="{{ route('cardapio.index') }}">Voltar ao cardápio</a></p>
        </section>
    </div>
@endsection
