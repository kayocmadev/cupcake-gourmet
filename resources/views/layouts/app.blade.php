<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('titulo', 'Cupcake Gourmet')</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
    <a class="pular" href="#conteudo">Pular para o conteúdo</a>

    <header class="topo">
        <a class="marca" href="{{ route('cardapio.index') }}">🧁 Cupcake Gourmet</a>
        <nav class="menu" aria-label="Menu principal">
            <a href="{{ route('cardapio.index') }}">Cardápio</a>
            @include('layouts.menu-usuario')
            @inject('carrinhoMenu', 'App\Services\Carrinho')
            <a href="{{ route('carrinho.index') }}">Carrinho <span class="contador" aria-label="{{ $carrinhoMenu->quantidadeTotal() }} itens">{{ $carrinhoMenu->quantidadeTotal() }}</span></a>
        </nav>
    </header>

    <main id="conteudo" class="pagina">
        @if (session('sucesso'))
            <div class="aviso aviso-sucesso" role="status">{{ session('sucesso') }}</div>
        @endif
        @if (session('erro'))
            <div class="aviso aviso-erro" role="alert">{{ session('erro') }}</div>
        @endif
        @if ($errors->any())
            <div class="aviso aviso-erro" role="alert">
                <strong>Confira os dados informados:</strong>
                <ul>
                    @foreach ($errors->all() as $erro)
                        <li>{{ $erro }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @yield('conteudo')
    </main>

    <footer class="rodape">
        <p>Cupcake Gourmet · projeto acadêmico (Projeto Integrador Transdisciplinar II)</p>
    </footer>

    <script src="{{ asset('js/app.js') }}" defer></script>
</body>
</html>
