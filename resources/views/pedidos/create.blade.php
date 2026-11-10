@extends('layouts.app')

@section('titulo', 'Finalizar pedido · Cupcake Gourmet')

@section('conteudo')
    <h1>Finalizar pedido</h1>

    <h2>Resumo</h2>
    <ul class="resumo-itens">
        @foreach ($itens as $item)
            <li>{{ $item['quantidade'] }} × {{ $item['produto']->nome }} — R$ {{ number_format($item['subtotal'], 2, ',', '.') }}</li>
        @endforeach
    </ul>
    <div class="resumo">
        <dl>
            <dt>Subtotal</dt><dd>R$ {{ number_format($subtotal, 2, ',', '.') }}</dd>
            <dt>Taxa de entrega</dt><dd>R$ {{ number_format($taxa, 2, ',', '.') }}</dd>
            <dt class="total">Total</dt><dd class="total">R$ {{ number_format($subtotal + $taxa, 2, ',', '.') }}</dd>
        </dl>
    </div>

    <h2>Entrega e pagamento</h2>
    <form class="formulario" method="POST" action="{{ route('pedidos.store') }}" novalidate>
        @csrf

        <label for="endereco_entrega">Endereço de entrega</label>
        <input type="text" id="endereco_entrega" name="endereco_entrega" value="{{ old('endereco_entrega') }}"
               placeholder="Rua, número, bairro" autocomplete="street-address"
               class="@error('endereco_entrega') campo-erro @enderror" required>

        <label for="forma_pagamento">Forma de pagamento</label>
        <select id="forma_pagamento" name="forma_pagamento" class="@error('forma_pagamento') campo-erro @enderror" required>
            <option value="">Escolha uma opção</option>
            @foreach ($formasPagamento as $valor => $rotulo)
                <option value="{{ $valor }}" @selected(old('forma_pagamento') === $valor)>{{ $rotulo }}</option>
            @endforeach
        </select>

        <label for="observacao">Observação (opcional)</label>
        <textarea id="observacao" name="observacao" maxlength="255" placeholder="Ex.: tocar o interfone">{{ old('observacao') }}</textarea>

        <button type="submit" class="botao">Confirmar pedido</button>
    </form>

    <p><a href="{{ route('carrinho.index') }}">← Voltar ao carrinho</a></p>
@endsection
