@auth
    <a href="{{ route('pedidos.index') }}">Meus pedidos</a>
    @if (auth()->user()->is_admin)
        <a href="{{ route('admin.pedidos.index') }}">Painel da loja</a>
        <a href="{{ route('admin.produtos.index') }}">Gerenciar cardápio</a>
    @endif
    <span class="saudacao">Olá, {{ \Illuminate\Support\Str::of(auth()->user()->name)->before(' ') }}</span>
    <form method="POST" action="{{ route('sair') }}">
        @csrf
        <button type="submit" class="link">Sair</button>
    </form>
@else
    <a href="{{ route('entrar') }}">Entrar</a>
    <a href="{{ route('cadastro') }}">Cadastrar</a>
@endauth
