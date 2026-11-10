@extends('layouts.app')

@section('titulo', 'Créditos das imagens · Cupcake Gourmet')

@section('conteudo')
    <div class="conteiner pagina texto-estreito">
        <h1>Créditos das imagens</h1>
        <p>As fotos do cardápio de demonstração vêm do <a href="https://www.pexels.com" rel="noopener">Pexels</a> e são de uso livre, conforme a <a href="https://www.pexels.com/license/" rel="noopener">licença do site</a>. Obrigado aos fotógrafos:</p>
        <ul>
            @foreach ($creditos as $arquivo => [$fotografo, $id])
                <li>{{ $arquivo }}: <a href="https://www.pexels.com/photo/{{ $id }}/" rel="noopener">{{ $fotografo }}</a></li>
            @endforeach
        </ul>
        <p>Em uma loja real, essas fotos devem ser trocadas por imagens dos produtos reais.</p>
    </div>
@endsection
