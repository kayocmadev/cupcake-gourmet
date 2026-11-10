{{-- O formulário de carrinho é ligado na etapa do carrinho. --}}
@if (Route::has('carrinho.adicionar'))
    <form method="POST" action="{{ route('carrinho.adicionar', $produto) }}" class="form-adicionar">
        @csrf
        <label for="qtd-{{ $produto->id }}" class="so-leitor">Quantidade de {{ $produto->nome }}</label>
        <input type="number" id="qtd-{{ $produto->id }}" name="quantidade" value="1"
               min="{{ config('loja.quantidade_minima') }}" max="{{ config('loja.quantidade_maxima') }}">
        <button type="submit" class="botao">Adicionar</button>
    </form>
@endif
