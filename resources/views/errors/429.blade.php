@extends('layouts.app')

@section('titulo', 'Muitas tentativas · Cupcake Gourmet')

@section('conteudo')
    <section class="vazio">
        <h1>Muitas tentativas</h1>
        <p>Você fez muitas tentativas em pouco tempo. Aguarde um minuto e tente de novo.</p>
        <p><a class="botao" href="{{ route('cardapio.index') }}">Voltar ao cardápio</a></p>
    </section>
@endsection
