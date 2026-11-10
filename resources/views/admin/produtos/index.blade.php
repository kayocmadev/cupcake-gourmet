@extends('layouts.app')

@section('titulo', 'Cardápio · Painel da loja')

@section('conteudo')
    <div class="conteiner pagina">
        <div class="painel-cabecalho">
            <h1>Painel da loja: cardápio</h1>
            <a class="botao" href="{{ route('admin.produtos.create') }}"><svg class="icone"><use href="#i-mais"/></svg> Novo cupcake</a>
        </div>

        <div class="tabela-envolta">
            <table class="tabela tabela-responsiva">
                <thead>
                    <tr>
                        <th><span class="so-leitor">Foto</span></th>
                        <th>Nome</th>
                        <th>Categoria</th>
                        <th class="num">Preço</th>
                        <th>Situação</th>
                        <th><span class="so-leitor">Ações</span></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($produtos as $produto)
                        <tr>
                            <td>@if ($produto->urlImagem())<img class="miniatura" src="{{ $produto->urlImagem() }}" alt="">@endif</td>
                            <td data-rotulo="Nome">{{ $produto->nome }}@if ($produto->destaque) <span class="selo" style="background:#f6e7c8;color:#6b4e00">Mais pedido</span>@endif</td>
                            <td data-rotulo="Categoria">{{ $produto->rotuloCategoria() }}</td>
                            <td class="num" data-rotulo="Preço">R$ {{ number_format((float) $produto->preco, 2, ',', '.') }}</td>
                            <td data-rotulo="Situação">{{ $produto->ativo ? 'No cardápio' : 'Desativado' }}</td>
                            <td><a href="{{ route('admin.produtos.edit', $produto) }}">Editar</a></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
