@php($compacto = $compacto ?? false)
<form method="POST" action="{{ route('carrinho.adicionar', $produto) }}" class="form-adicionar">
    @csrf
    @if ($compacto)
        <input type="hidden" name="quantidade" value="1">
        <button type="submit" class="botao botao-pequeno" aria-label="Adicionar {{ $produto->nome }} ao carrinho">
            <svg class="icone"><use href="#i-mais"/></svg> Adicionar
        </button>
    @else
        <div class="quantidade" data-quantidade>
            <button type="button" data-passo="-1" aria-label="Diminuir quantidade"><svg class="icone"><use href="#i-menos"/></svg></button>
            <label for="qtd-{{ $produto->id }}" class="so-leitor">Quantidade de {{ $produto->nome }}</label>
            <input type="number" id="qtd-{{ $produto->id }}" name="quantidade" value="1"
                   min="{{ config('loja.quantidade_minima') }}" max="{{ config('loja.quantidade_maxima') }}" inputmode="numeric">
            <button type="button" data-passo="1" aria-label="Aumentar quantidade"><svg class="icone"><use href="#i-mais"/></svg></button>
        </div>
        <button type="submit" class="botao"><svg class="icone"><use href="#i-carrinho"/></svg> Adicionar ao carrinho</button>
    @endif
</form>
