@extends('layouts.app')

@section('titulo', ($produto->exists ? 'Editar' : 'Novo').' cupcake · Painel da loja')

@section('conteudo')
    <div class="conteiner pagina texto-estreito">
        <nav class="migalhas"><a href="{{ route('admin.produtos.index') }}">← Voltar ao cardápio da loja</a></nav>
        <h1>{{ $produto->exists ? 'Editar cupcake' : 'Novo cupcake' }}</h1>

        <form class="formulario" method="POST" enctype="multipart/form-data" novalidate
              action="{{ $produto->exists ? route('admin.produtos.update', $produto) : route('admin.produtos.store') }}">
            @csrf
            @if ($produto->exists) @method('PUT') @endif

            <label for="nome">Nome</label>
            <input type="text" id="nome" name="nome" value="{{ old('nome', $produto->nome) }}" class="@error('nome') campo-erro @enderror" required>

            <div class="grade-2" style="grid-template-columns:1fr 1fr">
                <div>
                    <label for="sabor">Sabor</label>
                    <input type="text" id="sabor" name="sabor" value="{{ old('sabor', $produto->sabor) }}" class="@error('sabor') campo-erro @enderror" required>
                </div>
                <div>
                    <label for="categoria">Categoria</label>
                    <select id="categoria" name="categoria" class="@error('categoria') campo-erro @enderror" required>
                        @foreach (\App\Models\Produto::CATEGORIAS as $chave => $rotulo)
                            <option value="{{ $chave }}" @selected(old('categoria', $produto->categoria) === $chave)>{{ $rotulo }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <label for="descricao">Descrição</label>
            <textarea id="descricao" name="descricao" class="@error('descricao') campo-erro @enderror" required>{{ old('descricao', $produto->descricao) }}</textarea>

            <label for="preco">Preço (R$)</label>
            <input type="text" inputmode="decimal" id="preco" name="preco" value="{{ old('preco', $produto->preco) }}" class="@error('preco') campo-erro @enderror" required>
            <p class="ajuda">Use ponto para os centavos, por exemplo 9.50.</p>

            <label for="imagem">Foto do cupcake</label>
            @if ($produto->urlImagem())
                <img src="{{ $produto->urlImagem() }}" alt="Foto atual" style="width:120px;height:120px;object-fit:cover;border-radius:14px;margin-bottom:8px">
            @endif
            <input type="file" id="imagem" name="imagem" accept="image/png,image/jpeg,image/webp">
            <p class="ajuda">JPG, PNG ou WebP, até 4 MB. Prefira fotos quadradas. {{ $produto->exists ? 'Deixe em branco para manter a foto atual.' : '' }}</p>

            <label class="opcao"><input type="checkbox" name="destaque" value="1" @checked(old('destaque', $produto->destaque))> Marcar como “Mais pedido”</label>
            <label class="opcao"><input type="checkbox" name="ativo" value="1" @checked(old('ativo', $produto->ativo))> Aparece no cardápio</label>

            <button type="submit" class="botao botao-bloco">Salvar</button>
        </form>
    </div>
@endsection
