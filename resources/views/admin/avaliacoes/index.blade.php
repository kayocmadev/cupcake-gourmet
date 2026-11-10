@extends('layouts.app')

@section('titulo', 'Avaliações · Painel da loja')

@section('conteudo')
    <div class="conteiner pagina">
        <div class="painel-cabecalho">
            <div>
                <h1>Avaliações do teste</h1>
                <p style="margin:0;color:var(--tinta-suave)">
                    {{ $avaliacoes->count() }} {{ $avaliacoes->count() === 1 ? 'avaliação' : 'avaliações' }}
                    @if ($media) · nota média {{ number_format($media, 1, ',', '.') }} de 5 @endif
                </p>
            </div>
            @if ($avaliacoes->isNotEmpty())
                <a class="botao" href="{{ route('admin.avaliacoes.pdf') }}">Baixar PDF com todas</a>
            @endif
        </div>

        @if ($avaliacoes->isEmpty())
            <div class="vazio">
                <h2>Nenhuma avaliação ainda</h2>
                <p>Envie o endereço do site para quem vai testar. A avaliação é feita pelo botão “Avaliar o site”.</p>
                <a class="botao botao-contorno" href="{{ route('avaliacoes.create') }}">Ver o formulário</a>
            </div>
        @else
            <div class="lista-pedidos">
                @foreach ($avaliacoes as $avaliacao)
                    <article class="cartao-avaliacao">
                        <h3>{{ $avaliacao->nome }}</h3>
                        <span style="color:var(--tinta-suave);font-size:.9rem">
                            Teste de {{ $avaliacao->data_teste->format('d/m/Y') }}
                            @if ($avaliacao->aparelho) · {{ $avaliacao->aparelho }} @endif
                            @if ($avaliacao->nota) · nota {{ $avaliacao->nota }}/5 @endif
                        </span>
                        <dl>
                            @if ($avaliacao->funcionou)<dt>Testou e funcionou</dt><dd>{{ $avaliacao->funcionou }}</dd>@endif
                            @if ($avaliacao->nao_funcionou)<dt>Testou e não funcionou</dt><dd>{{ $avaliacao->nao_funcionou }}</dd>@endif
                            @if ($avaliacao->nao_testado)<dt>Não testou</dt><dd>{{ $avaliacao->nao_testado }}</dd>@endif
                            @if ($avaliacao->sugestoes)<dt>Sugestões</dt><dd>{{ $avaliacao->sugestoes }}</dd>@endif
                        </dl>
                        @if ($avaliacao->urlCaptura())
                            <a href="{{ $avaliacao->urlCaptura() }}" target="_blank" rel="noopener"><img src="{{ $avaliacao->urlCaptura() }}" alt="Captura de tela enviada por {{ $avaliacao->nome }}"></a>
                        @endif
                    </article>
                @endforeach
            </div>
        @endif
    </div>
@endsection
