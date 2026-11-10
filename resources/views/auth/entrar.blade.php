@extends('layouts.app')

@section('titulo', 'Entrar · Cupcake Gourmet')

@section('conteudo')
    <div class="conteiner pagina">
        <div class="auth">
            <div class="auth-foto">
                <img src="{{ asset('img/site/presente.jpg') }}" alt="">
                <p>Bem-vindo(a) de volta. Seus pedidos estão esperando.</p>
            </div>
            <div class="auth-formulario">
                <h1 style="font-size:2rem">Entrar</h1>
                <form class="formulario" method="POST" action="{{ route('entrar.store') }}" novalidate>
                    @csrf
                    <label for="email">E-mail</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" autocomplete="email" class="@error('email') campo-erro @enderror" required autofocus>

                    <label for="password">Senha</label>
                    <input type="password" id="password" name="password" autocomplete="current-password" required>

                    <label class="opcao"><input type="checkbox" name="lembrar" value="1"> Manter conectado</label>

                    <button type="submit" class="botao botao-bloco">Entrar</button>
                </form>
                <p style="margin-top:18px">Ainda não tem conta? <a href="{{ route('cadastro') }}">Cadastre-se</a></p>
            </div>
        </div>
    </div>
@endsection
