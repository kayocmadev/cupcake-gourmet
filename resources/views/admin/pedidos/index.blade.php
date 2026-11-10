@extends('layouts.app')

@section('titulo', 'Pedidos · Painel da loja')

@section('conteudo')
    <div class="conteiner pagina">
        <div class="painel-cabecalho">
            <h1>Painel da loja: pedidos</h1>
        </div>

        <div class="painel-abas">
            <a href="{{ route('admin.pedidos.index') }}" class="chip" @if (! $status) aria-current="true" @endif>Todos</a>
            @foreach (\App\Models\Pedido::ROTULOS as $chave => $rotulo)
                <a href="{{ route('admin.pedidos.index', ['status' => $chave]) }}" class="chip" @if ($status === $chave) aria-current="true" @endif>{{ $rotulo }}</a>
            @endforeach
        </div>

        @if ($pedidos->isEmpty())
            <div class="vazio"><h2>Nenhum pedido neste filtro</h2></div>
        @else
            <div class="tabela-envolta">
                <table class="tabela tabela-responsiva">
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
                                <td data-rotulo="Cliente">{{ $pedido->user->name }}<br><small style="color:var(--tinta-suave)">{{ $pedido->user->telefone }}</small></td>
                                <td data-rotulo="Data">{{ $pedido->created_at->format('d/m/Y H:i') }}</td>
                                <td data-rotulo="Status"><span class="selo selo-{{ $pedido->status }}">{{ $pedido->rotuloStatus() }}</span></td>
                                <td class="num" data-rotulo="Total">R$ {{ number_format((float) $pedido->total, 2, ',', '.') }}</td>
                                <td>
                                    @if ($pedido->proximoStatus())
                                        <form method="POST" action="{{ route('admin.pedidos.avancar', $pedido) }}">
                                            @csrf
                                            <button type="submit" class="botao botao-pequeno">Avançar para “{{ \App\Models\Pedido::ROTULOS[$pedido->proximoStatus()] }}”</button>
                                        </form>
                                    @else
                                        <span class="ajuda">Sem ações</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
@endsection
