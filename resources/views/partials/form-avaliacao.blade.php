<form method="POST" action="{{ route('avaliacoes.store') }}" enctype="multipart/form-data" novalidate>
    @csrf
    <div class="grade-2">
        <div>
            <label for="av-nome">Seu nome *</label>
            <input type="text" id="av-nome" name="nome" value="{{ old('nome') }}" maxlength="120" required class="@if ($erros->has('nome')) campo-erro @endif">
        </div>
        <div>
            <label for="av-data">Data do teste *</label>
            <input type="date" id="av-data" name="data_teste" value="{{ old('data_teste', now()->format('Y-m-d')) }}" max="{{ now()->format('Y-m-d') }}" required class="@if ($erros->has('data_teste')) campo-erro @endif">
        </div>
    </div>

    <label for="av-aparelho">Aparelho e navegador usados</label>
    <input type="text" id="av-aparelho" name="aparelho" value="{{ old('aparelho') }}" maxlength="120" placeholder="Ex.: celular Android, Chrome">

    <label for="av-funcionou">O que você testou e funcionou</label>
    <textarea id="av-funcionou" name="funcionou" maxlength="2000" placeholder="Ex.: criei a conta, adicionei 3 cupcakes e finalizei o pedido sem problemas.">{{ old('funcionou') }}</textarea>

    <label for="av-nao-funcionou">O que você testou e não funcionou (e o que deve ser corrigido)</label>
    <textarea id="av-nao-funcionou" name="nao_funcionou" maxlength="2000" placeholder="Ex.: no celular, o botão de finalizar ficou atrás do teclado.">{{ old('nao_funcionou') }}</textarea>

    <label for="av-nao-testado">O que você não testou (e por quê)</label>
    <textarea id="av-nao-testado" name="nao_testado" maxlength="2000" placeholder="Ex.: não testei o cancelamento do pedido porque não achei o botão.">{{ old('nao_testado') }}</textarea>

    <label for="av-sugestoes">Sugestões de melhoria</label>
    <textarea id="av-sugestoes" name="sugestoes" maxlength="2000">{{ old('sugestoes') }}</textarea>

    <fieldset style="border:0;padding:0;margin:16px 0 0">
        <legend style="font-weight:600;font-size:.92rem;padding:0">Nota geral de facilidade de uso</legend>
        <div class="estrelas">
            @foreach ([5, 4, 3, 2, 1] as $n)
                <input type="radio" id="av-nota-{{ $n }}" name="nota" value="{{ $n }}" @checked((int) old('nota') === $n)>
                <label for="av-nota-{{ $n }}" title="{{ $n }} de 5"><span class="so-leitor">{{ $n }} de 5</span><svg class="icone"><use href="#i-estrela"/></svg></label>
            @endforeach
        </div>
    </fieldset>

    <label for="av-captura">Captura de tela (opcional)</label>
    <input type="file" id="av-captura" name="captura" accept="image/png,image/jpeg,image/webp">
    <p class="ajuda">Se algo deu errado, anexe um print. Formatos JPG, PNG ou WebP, até 4 MB.</p>

    <button type="submit" class="botao botao-bloco" style="margin-top:20px">Enviar avaliação</button>
</form>
