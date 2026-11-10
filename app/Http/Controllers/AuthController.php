<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function formCadastro(): View
    {
        return view('auth.cadastro');
    }

    /** Cadastro de cliente (RF01, RN01, RN02). */
    public function cadastrar(Request $request): RedirectResponse
    {
        $dados = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:150', 'unique:users,email'],
            'telefone' => ['required', 'string', 'max:20', 'regex:/^[0-9()\s\-+]{10,20}$/'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ], [
            'telefone.regex' => 'Informe o telefone com DDD, por exemplo (83) 99999-0000.',
        ]);

        $usuario = User::create($dados);

        Auth::login($usuario);
        $request->session()->regenerate();

        return redirect()->route('cardapio.index')
            ->with('sucesso', 'Cadastro realizado! Bem-vindo(a), '.$usuario->name.'.');
    }

    public function formLogin(): View
    {
        return view('auth.entrar');
    }

    /** Entrada no sistema (RF02). A mensagem de erro não revela qual dado está errado. */
    public function entrar(Request $request): RedirectResponse
    {
        $credenciais = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (! Auth::attempt($credenciais, $request->boolean('lembrar'))) {
            return back()
                ->withInput($request->only('email'))
                ->withErrors(['email' => 'E-mail ou senha incorretos.']);
        }

        $request->session()->regenerate();

        return redirect()->intended(route('cardapio.index'))
            ->with('sucesso', 'Você entrou no sistema.');
    }

    public function sair(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('cardapio.index')->with('sucesso', 'Você saiu do sistema.');
    }
}
