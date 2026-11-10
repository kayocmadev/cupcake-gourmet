<?php

namespace App\Http\Controllers;

use App\Models\Avaliacao;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AvaliacaoController extends Controller
{
    /** Página com o formulário de avaliação (mesmo formulário da janela "Avaliar o site"). */
    public function create(): View
    {
        return view('avaliacoes.create');
    }

    /** RF11: qualquer pessoa pode enviar a avaliação do teste, sem precisar de conta. */
    public function store(Request $request): RedirectResponse
    {
        $dados = $request->validateWithBag('avaliacao', [
            'nome' => ['required', 'string', 'max:120'],
            'data_teste' => ['required', 'date', 'before_or_equal:today'],
            'aparelho' => ['nullable', 'string', 'max:120'],
            'funcionou' => ['nullable', 'string', 'max:2000'],
            'nao_funcionou' => ['nullable', 'string', 'max:2000'],
            'nao_testado' => ['nullable', 'string', 'max:2000'],
            'sugestoes' => ['nullable', 'string', 'max:2000'],
            'nota' => ['nullable', 'integer', 'between:1,5'],
            'captura' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
        ], [
            'captura.image' => 'A captura de tela deve ser uma imagem.',
            'captura.mimes' => 'A captura de tela deve ser JPG, PNG ou WebP.',
            'captura.max' => 'A captura de tela deve ter no máximo 4 MB.',
            'data_teste.before_or_equal' => 'A data do teste não pode ser no futuro.',
            'data_teste.date' => 'Informe uma data válida.',
        ]);

        if ($request->hasFile('captura')) {
            $dados['captura'] = $request->file('captura')->store('avaliacoes', 'uploads');
        }

        Avaliacao::create($dados);

        return redirect()->back()->with('sucesso', 'Obrigado! Sua avaliação foi enviada.');
    }
}
