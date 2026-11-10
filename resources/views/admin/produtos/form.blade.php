@extends('layouts.app')

@section('titulo', ($produto->exists ? 'Editar' : 'Novo').' cupcake · Painel da loja')

@section('conteudo')
    <h1>{{ $produto->exists ? 'Editar cupcake' : 'Novo cupcake' }}</h1>

    <form class="formulario" method="POST" novalidate
          action="{{ $produto->exists ? route('admin.produtos.update', $produto) : route('admin.produtos.store') }}">
        @csrf
        @if ($produto->exists) @method('PUT') @endif

        <label for="nome">Nome</label>
        <input type="text" id="nome" name="nome" value="{{ old('nome', $produto->nome) }}" class="@error('nome') campo-erro @enderror" required>

        <label for="sabor">Sabor</label>
        <input type="text" id="sabor" name="sabor" value="{{ old('sabor', $produto->sabor) }}" class="@error('sabor') campo-erro @enderror" required>

        <label for="descricao">Descrição</label>
        <textarea id="descricao" name="descricao" class="@error('descricao') campo-erro @enderror" required>{{ old('descricao', $produto->descricao) }}</textarea>

        <label for="preco">Preço (R$)</label>
        <input type="text" inputmode="decimal" id="preco" name="preco" value="{{ old('preco', $produto->preco) }}" class="@error('preco') campo-erro @enderror" required>
        <p class="ajuda">Use ponto para os centavos, por exemplo 9.50.</p>

        <label class="opcao"><input type="checkbox" name="ativo" value="1" @checked(old('ativo', $produto->ativo))> Aparece no cardápio</label>

        <button type="submit" class="botao">Salvar</button>
    </form>

    <p><a href="{{ route('admin.produtos.index') }}">← Voltar ao cardápio da loja</a></p>
@endsection
