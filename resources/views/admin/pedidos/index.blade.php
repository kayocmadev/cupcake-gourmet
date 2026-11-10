@extends('layouts.app')

@section('titulo', 'Pedidos · Painel da loja')

@section('conteudo')
    <h1>Painel da loja: pedidos</h1>

    <p class="acoes">
        <a href="{{ route('admin.pedidos.index') }}" class="botao {{ $status ? 'botao-secundario' : '' }}">Todos</a>
        @foreach (\App\Models\Pedido::ROTULOS as $chave => $rotulo)
            <a href="{{ route('admin.pedidos.index', ['status' => $chave]) }}"
               class="botao {{ $status === $chave ? '' : 'botao-secundario' }}">{{ $rotulo }}</a>
        @endforeach
    </p>

    @if ($pedidos->isEmpty())
        <p class="vazio">Nenhum pedido neste filtro.</p>
    @else
        <table class="tabela">
            <thead>
                <tr>
                    <th>Pedido</th>
                    <th>Cliente</th>
                    <th>Data</th>
                    <th>Status</th>
                    <th class="num">Total</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($pedidos as $pedido)
                    <tr>
                        <td data-rotulo="Pedido"><a href="{{ route('pedidos.show', $pedido) }}">#{{ $pedido->id }}</a></td>
                        <td data-rotulo="Cliente">{{ $pedido->user->name }}<br><small>{{ $pedido->user->telefone }}</small></td>
                        <td data-rotulo="Data">{{ $pedido->created_at->format('d/m/Y H:i') }}</td>
                        <td data-rotulo="Status"><span class="selo selo-{{ $pedido->status }}">{{ $pedido->rotuloStatus() }}</span></td>
                        <td class="num" data-rotulo="Total">R$ {{ number_format((float) $pedido->total, 2, ',', '.') }}</td>
                        <td>
                            @if ($pedido->proximoStatus())
                                <form method="POST" action="{{ route('admin.pedidos.avancar', $pedido) }}">
                                    @csrf
                                    <button type="submit" class="botao">Avançar para “{{ \App\Models\Pedido::ROTULOS[$pedido->proximoStatus()] }}”</button>
                                </form>
                            @else
                                <span class="ajuda">Sem ações</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
@endsection
