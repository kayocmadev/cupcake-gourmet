@extends('layouts.app')

@section('titulo', 'Página não encontrada · Cupcake Gourmet')

@section('conteudo')
    <div class="conteiner">
        <section class="erro-pagina">
            <div class="codigo">404</div>
            <h1>Página não encontrada</h1>
            <p>Não encontramos o que você procurava. O endereço pode estar errado ou o cupcake saiu do cardápio.</p>
            <p><a class="botao" href="{{ route('cardapio.index') }}">Voltar ao cardápio</a></p>
        </section>
    </div>
@endsection
