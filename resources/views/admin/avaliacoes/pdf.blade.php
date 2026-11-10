<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <title>Avaliações do Cupcake Gourmet</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 11px; color: #2b1b1a; line-height: 1.5; }
        h1 { font-size: 20px; margin: 0 0 4px; }
        .sub { color: #6d5a57; margin-bottom: 18px; }
        .avaliacao { border: 1px solid #eadfd6; border-radius: 6px; padding: 12px 14px; margin-bottom: 14px; page-break-inside: avoid; }
        .avaliacao h2 { font-size: 14px; margin: 0 0 2px; }
        .meta { color: #6d5a57; margin-bottom: 8px; }
        .rotulo { font-weight: bold; margin-top: 8px; }
        .texto { white-space: pre-line; }
        img { max-width: 100%; max-height: 260px; margin-top: 8px; border: 1px solid #eadfd6; }
    </style>
</head>
<body>
    <h1>Avaliações do Cupcake Gourmet</h1>
    <div class="sub">
        Projeto Integrador Transdisciplinar em Engenharia de Software II · {{ $avaliacoes->count() }} avaliações · gerado em {{ $geradoEm->format('d/m/Y H:i') }}
    </div>

    @foreach ($avaliacoes as $avaliacao)
        <div class="avaliacao">
            <h2>{{ $loop->iteration }}. {{ $avaliacao->nome }}</h2>
            <div class="meta">
                Data do teste: {{ $avaliacao->data_teste->format('d/m/Y') }}
                @if ($avaliacao->aparelho) · Aparelho: {{ $avaliacao->aparelho }} @endif
                @if ($avaliacao->nota) · Nota geral: {{ $avaliacao->nota }} de 5 @endif
            </div>

            <div class="rotulo">O que testou e funcionou</div>
            <div class="texto">{{ $avaliacao->funcionou ?: 'Não informado.' }}</div>

            <div class="rotulo">O que testou e não funcionou (e o que deve ser corrigido)</div>
            <div class="texto">{{ $avaliacao->nao_funcionou ?: 'Não informado.' }}</div>

            <div class="rotulo">O que não foi testado (e por quê)</div>
            <div class="texto">{{ $avaliacao->nao_testado ?: 'Não informado.' }}</div>

            @if ($avaliacao->sugestoes)
                <div class="rotulo">Sugestões de melhoria</div>
                <div class="texto">{{ $avaliacao->sugestoes }}</div>
            @endif

            @if ($avaliacao->captura && file_exists(public_path('uploads/'.$avaliacao->captura)))
                <img src="{{ public_path('uploads/'.$avaliacao->captura) }}" alt="Captura de tela">
            @endif
        </div>
    @endforeach
</body>
</html>
