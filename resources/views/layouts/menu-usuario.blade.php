@auth
    <span class="saudacao">Olá, {{ \Illuminate\Support\Str::of(auth()->user()->name)->before(' ') }}</span>
    <form method="POST" action="{{ route('sair') }}">
        @csrf
        <button type="submit" class="link">Sair</button>
    </form>
@else
    <a href="{{ route('entrar') }}">Entrar</a>
    <a href="{{ route('cadastro') }}">Cadastrar</a>
@endauth
