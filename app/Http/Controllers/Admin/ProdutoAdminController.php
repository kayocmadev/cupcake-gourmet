<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Produto;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ProdutoAdminController extends Controller
{
    /** RF10: gerenciamento do cardápio. */
    public function index(): View
    {
        return view('admin.produtos.index', ['produtos' => Produto::orderBy('nome')->get()]);
    }

    public function create(): View
    {
        return view('admin.produtos.form', ['produto' => new Produto(['ativo' => true])]);
    }

    public function store(Request $request): RedirectResponse
    {
        $produto = Produto::create($this->validar($request));

        return redirect()->route('admin.produtos.index')->with('sucesso', "{$produto->nome} cadastrado.");
    }

    public function edit(Produto $produto): View
    {
        return view('admin.produtos.form', ['produto' => $produto]);
    }

    public function update(Request $request, Produto $produto): RedirectResponse
    {
        $produto->update($this->validar($request));

        return redirect()->route('admin.produtos.index')->with('sucesso', "{$produto->nome} atualizado.");
    }

    private function validar(Request $request): array
    {
        $dados = $request->validate([
            'nome' => ['required', 'string', 'max:100'],
            'sabor' => ['required', 'string', 'max:60'],
            'categoria' => ['required', Rule::in(array_keys(Produto::CATEGORIAS))],
            'descricao' => ['required', 'string', 'max:1000'],
            'preco' => ['required', 'numeric', 'between:0.01,999.99'],
            'imagem' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
        ], [
            'imagem.image' => 'A foto deve ser uma imagem.',
            'imagem.mimes' => 'A foto deve ser JPG, PNG ou WebP.',
            'imagem.max' => 'A foto deve ter no máximo 4 MB.',
            'preco.between' => 'O preço deve estar entre R$ 0,01 e R$ 999,99.',
            'preco.numeric' => 'Informe o preço usando apenas números, por exemplo 9.50.',
        ]);

        $dados['ativo'] = $request->boolean('ativo');
        $dados['destaque'] = $request->boolean('destaque');

        if ($request->hasFile('imagem')) {
            $dados['imagem'] = 'uploads/'.$request->file('imagem')->store('produtos', 'uploads');
        } else {
            unset($dados['imagem']);
        }

        return $dados;
    }
}
