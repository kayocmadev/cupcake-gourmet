@extends('layouts.app')

@section('titulo', 'Cardápio · Painel da loja')

@section('conteudo')
    <h1>Painel da loja: cardápio</h1>
    <p><a class="botao" href="{{ route('admin.produtos.create') }}">Novo cupcake</a></p>

    <table class="tabela">
        <thead>
            <tr>
                <th>Nome</th>
                <th>Sabor</th>
                <th class="num">Preço</th>
                <th>Situação</th>
                <th><span class="so-leitor">Ações</span></th>
            </tr>
        </thead>
        <tbody>
            @foreach ($produtos as $produto)
                <tr>
                    <td data-rotulo="Nome">{{ $produto->nome }}</td>
                    <td data-rotulo="Sabor">{{ $produto->sabor }}</td>
                    <td class="num" data-rotulo="Preço">R$ {{ number_format((float) $produto->preco, 2, ',', '.') }}</td>
                    <td data-rotulo="Situação">{{ $produto->ativo ? 'No cardápio' : 'Desativado' }}</td>
                    <td><a href="{{ route('admin.produtos.edit', $produto) }}">Editar</a></td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
