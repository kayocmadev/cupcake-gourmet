@extends('layouts.app')

@section('titulo', 'Criar conta · Cupcake Gourmet')

@section('conteudo')
    <div class="conteiner pagina">
        <div class="auth">
            <div class="auth-foto">
                <img src="{{ asset('img/site/frutas.jpg') }}" alt="">
                <p>Crie sua conta e faça seu primeiro pedido em minutos.</p>
            </div>
            <div class="auth-formulario">
                <h1 style="font-size:2rem">Criar conta</h1>
                <form class="formulario" method="POST" action="{{ route('cadastro.store') }}" novalidate>
                    @csrf
                    <label for="name">Nome completo</label>
                    <input type="text" id="name" name="name" value="{{ old('name') }}" autocomplete="name" class="@error('name') campo-erro @enderror" required>

                    <label for="email">E-mail</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" autocomplete="email" class="@error('email') campo-erro @enderror" required>

                    <label for="telefone">Telefone com DDD</label>
                    <input type="tel" id="telefone" name="telefone" value="{{ old('telefone') }}" autocomplete="tel" placeholder="(83) 99999-0000" class="@error('telefone') campo-erro @enderror" required>

                    <label for="password">Senha</label>
                    <input type="password" id="password" name="password" autocomplete="new-password" class="@error('password') campo-erro @enderror" required>
                    <p class="ajuda">Mínimo de 8 caracteres.</p>

                    <label for="password_confirmation">Confirmar senha</label>
                    <input type="password" id="password_confirmation" name="password_confirmation" autocomplete="new-password" required>

                    <button type="submit" class="botao botao-bloco">Cadastrar</button>
                </form>
                <p style="margin-top:18px">Já tem conta? <a href="{{ route('entrar') }}">Entrar</a></p>
            </div>
        </div>
    </div>
@endsection
