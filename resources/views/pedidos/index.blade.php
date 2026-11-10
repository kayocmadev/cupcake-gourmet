@extends('layouts.app')

@section('titulo', 'Meus pedidos · Cupcake Gourmet')

@section('conteudo')
    <div class="conteiner pagina texto-estreito" style="max-width:860px">
        <h1>Meus pedidos</h1>

        @if ($pedidos->isEmpty())
            <div class="vazio">
                <h2>Você ainda não fez nenhum pedido</h2>
                <p>Quando fizer, ele aparece aqui para você acompanhar.</p>
                <a class="botao" href="{{ route('cardapio.index') }}#cardapio">Ver o cardápio</a>
            </div>
        @else
            <ul class="lista-pedidos">
                @foreach ($pedidos as $pedido)
                    <li class="pedido-cartao">
                        <div>
                            <h3>Pedido #{{ $pedido->id }}</h3>
                            <p class="meta">{{ $pedido->created_at->format('d/m/Y \à\s H:i') }}</p>
                        </div>
                        <div class="valor">R$ {{ number_format((float) $pedido->total, 2, ',', '.') }}</div>
                        <div class="acoes-pedido">
                            <span class="selo selo-{{ $pedido->status }}">{{ $pedido->rotuloStatus() }}</span>
                            <a class="botao botao-contorno botao-pequeno" href="{{ route('pedidos.show', $pedido) }}">Acompanhar <svg class="icone"><use href="#i-seta"/></svg></a>
                        </div>
                    </li>
                @endforeach
            </ul>
        @endif
    </div>
@endsection
