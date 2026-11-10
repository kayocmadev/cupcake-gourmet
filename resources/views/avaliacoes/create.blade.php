@extends('layouts.app')

@section('titulo', 'Avaliar o site · Cupcake Gourmet')

@section('conteudo')
    <div class="conteiner pagina texto-estreito">
        <h1>Avalie o Cupcake Gourmet</h1>
        <p>Você está testando um projeto acadêmico: nada é cobrado nem entregue. Percorra o site como um cliente de verdade e conte aqui o que funcionou, o que deu errado e o que poderia melhorar.</p>

        @php($erros = $errors->getBag('avaliacao'))
        @if ($erros->any())
            <div class="aviso aviso-erro" role="alert">
                <svg class="icone"><use href="#i-aviso"/></svg>
                <div><strong>Confira os dados informados:</strong><ul>@foreach ($erros->all() as $erro)<li>{{ $erro }}</li>@endforeach</ul></div>
            </div>
        @endif

        <div class="formulario">
            @include('partials.form-avaliacao', ['erros' => $erros])
        </div>
    </div>
@endsection
