@extends('layouts.app')

@section('titulo', 'Carrinho · Cupcake Gourmet')

@section('conteudo')
    <h1>Seu carrinho</h1>

    @if ($itens->isEmpty())
        <p class="vazio">
            Seu carrinho está vazio.<br>
            <a class="botao" href="{{ route('cardapio.index') }}">Ver o cardápio</a>
        </p>
    @else
        <table class="tabela">
            <thead>
                <tr>
                    <th>Cupcake</th>
                    <th class="num">Preço</th>
                    <th>Quantidade</th>
                    <th class="num">Subtotal</th>
                    <th><span class="so-leitor">Ações</span></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($itens as $item)
                    <tr>
                        <td data-rotulo="Cupcake">{{ $item['produto']->nome }}</td>
                        <td class="num" data-rotulo="Preço">R$ {{ number_format((float) $item['produto']->preco, 2, ',', '.') }}</td>
                        <td data-rotulo="Quantidade">
                            <form method="POST" action="{{ route('carrinho.atualizar', $item['produto']) }}" class="acoes">
                                @csrf
                                @method('PATCH')
                                <label for="qtd-{{ $item['produto']->id }}" class="so-leitor">Quantidade de {{ $item['produto']->nome }}</label>
                                <input type="number" id="qtd-{{ $item['produto']->id }}" name="quantidade" value="{{ $item['quantidade'] }}"
                                       min="{{ config('loja.quantidade_minima') }}" max="{{ config('loja.quantidade_maxima') }}">
                                <button type="submit" class="botao botao-secundario">Atualizar</button>
                            </form>
                        </td>
                        <td class="num" data-rotulo="Subtotal">R$ {{ number_format($item['subtotal'], 2, ',', '.') }}</td>
                        <td>
                            <form method="POST" action="{{ route('carrinho.remover', $item['produto']) }}">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="botao botao-secundario" data-confirmar="Remover {{ $item['produto']->nome }} do carrinho?">Remover</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="resumo">
            <dl>
                <dt>Subtotal</dt><dd>R$ {{ number_format($subtotal, 2, ',', '.') }}</dd>
                <dt>Taxa de entrega</dt><dd>R$ {{ number_format($taxa, 2, ',', '.') }}</dd>
                <dt class="total">Total</dt><dd class="total">R$ {{ number_format($subtotal + $taxa, 2, ',', '.') }}</dd>
            </dl>
        </div>

        <p class="acoes" style="justify-content: flex-end; margin-top: 16px;">
            <a class="botao botao-secundario" href="{{ route('cardapio.index') }}">Continuar comprando</a>
            <a class="botao" href="{{ url('/pedidos/novo') }}">Finalizar pedido</a>
        </p>
    @endif
@endsection
