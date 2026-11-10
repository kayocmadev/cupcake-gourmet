@extends('layouts.app')

@section('titulo', 'Meus pedidos · Cupcake Gourmet')

@section('conteudo')
    <h1>Meus pedidos</h1>

    @if ($pedidos->isEmpty())
        <p class="vazio">
            Você ainda não fez nenhum pedido.<br>
            <a class="botao" href="{{ route('cardapio.index') }}">Ver o cardápio</a>
        </p>
    @else
        <table class="tabela">
            <thead>
                <tr>
                    <th>Pedido</th>
                    <th>Data</th>
                    <th>Status</th>
                    <th class="num">Total</th>
                    <th><span class="so-leitor">Detalhes</span></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($pedidos as $pedido)
                    <tr>
                        <td data-rotulo="Pedido">#{{ $pedido->id }}</td>
                        <td data-rotulo="Data">{{ $pedido->created_at->format('d/m/Y H:i') }}</td>
                        <td data-rotulo="Status"><span class="selo selo-{{ $pedido->status }}">{{ $pedido->rotuloStatus() }}</span></td>
                        <td class="num" data-rotulo="Total">R$ {{ number_format((float) $pedido->total, 2, ',', '.') }}</td>
                        <td><a href="{{ route('pedidos.show', $pedido) }}">Acompanhar</a></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
@endsection
