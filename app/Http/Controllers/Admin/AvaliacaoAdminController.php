<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Avaliacao;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Response;
use Illuminate\View\View;

class AvaliacaoAdminController extends Controller
{
    public function index(): View
    {
        $avaliacoes = Avaliacao::latest()->get();

        return view('admin.avaliacoes.index', [
            'avaliacoes' => $avaliacoes,
            'media' => $avaliacoes->whereNotNull('nota')->avg('nota'),
        ]);
    }

    /** Gera o PDF com todas as avaliações, para anexar na entrega do projeto. */
    public function pdf(): Response
    {
        $avaliacoes = Avaliacao::oldest()->get();

        return Pdf::loadView('admin.avaliacoes.pdf', [
            'avaliacoes' => $avaliacoes,
            'geradoEm' => now(),
        ])->setPaper('a4')->download('avaliacoes-cupcake-gourmet.pdf');
    }
}
