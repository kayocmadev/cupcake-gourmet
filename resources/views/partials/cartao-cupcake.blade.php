{{-- Cartão de cupcake: foto, categoria, descrição curta, preço e botão de adicionar. --}}
<li class="cartao">
    <a class="cartao-foto" href="{{ route('cardapio.show', $produto) }}" tabindex="-1" aria-hidden="true">
        @if ($produto->urlImagem())
            <img src="{{ $produto->urlImagem() }}" alt="" loading="lazy" width="600" height="600">
        @else
            <div class="foto-vazia">🧁</div>
        @endif
        <span class="etiqueta">{{ $produto->rotuloCategoria() }}</span>
        @if ($produto->destaque)
            <span class="etiqueta etiqueta-destaque">Mais pedido</span>
        @endif
    </a>
    <div class="cartao-corpo">
        <h3><a href="{{ route('cardapio.show', $produto) }}">{{ $produto->nome }}</a></h3>
        <p class="resumo-texto">{{ $produto->descricao }}</p>
        <div class="cartao-rodape">
            <span class="preco">R$ {{ number_format((float) $produto->preco, 2, ',', '.') }}</span>
            @include('partials.form-adicionar', ['produto' => $produto, 'compacto' => true])
        </div>
    </div>
</li>
