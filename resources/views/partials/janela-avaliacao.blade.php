{{-- Janela "Avaliar o site": formulário de teste preenchido por quem testa o sistema. --}}
@php($erros = $errors->getBag('avaliacao'))
<dialog class="janela" id="janela-avaliacao" aria-labelledby="titulo-avaliacao" @if ($erros->any()) data-abrir @endif>
    <div class="janela-cabecalho">
        <div>
            <h2 id="titulo-avaliacao">Avalie o Cupcake Gourmet</h2>
            <p>Você está testando um projeto acadêmico: nada é cobrado. Conte o que funcionou e o que deu errado, sem dó.</p>
        </div>
        <button type="button" class="botao-icone" data-fechar-janela aria-label="Fechar"><svg class="icone"><use href="#i-fechar"/></svg></button>
    </div>
    <div class="janela-corpo">
        @if ($erros->any())
            <div class="aviso aviso-erro" role="alert">
                <div>
                    <strong>Confira os dados informados:</strong>
                    <ul>@foreach ($erros->all() as $erro)<li>{{ $erro }}</li>@endforeach</ul>
                </div>
            </div>
        @endif
        @include('partials.form-avaliacao', ['erros' => $erros])
    </div>
</dialog>
