<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="@yield('descricao', 'Cupcakes gourmet feitos todos os dias, com entrega em casa. Escolha seus sabores, monte o pedido e acompanhe a entrega.')">
    <meta name="theme-color" content="#fbf6f1">
    <title>@yield('titulo', 'Cupcake Gourmet')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;700&family=Fraunces:ital,opsz,wght@0,9..144,600;0,9..144,700;1,9..144,600&display=swap">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}?v={{ filemtime(public_path('css/app.css')) }}">
</head>
<body>
    @include('layouts.icones')
    <a class="pular" href="#conteudo">Pular para o conteúdo</a>

    <div class="faixa">Frete grátis em pedidos a partir de <strong>R$ {{ number_format((float) config('loja.frete_gratis_acima'), 0, ',', '.') }}</strong> · pague só na entrega</div>

    @inject('carrinhoMenu', 'App\Services\Carrinho')
    <header class="topo" id="topo">
        <div class="conteiner topo-linha">
            <a class="marca" href="{{ route('cardapio.index') }}">
                @include('partials.logo')
                Cupcake Gourmet
            </a>

            <nav class="menu" aria-label="Menu principal" id="menu-principal">
                <a href="{{ route('cardapio.index') }}">Cardápio</a>
                <a href="{{ route('cardapio.index') }}#como-funciona">Como funciona</a>
                @include('layouts.menu-usuario')
            </nav>

            <div class="topo-acoes">
                <a class="botao-icone" href="{{ route('carrinho.index') }}" aria-label="Carrinho, {{ $carrinhoMenu->quantidadeTotal() }} itens">
                    <svg class="icone"><use href="#i-carrinho"/></svg>
                    <span class="contador" data-zero="{{ $carrinhoMenu->quantidadeTotal() === 0 ? 'true' : 'false' }}" aria-hidden="true">{{ $carrinhoMenu->quantidadeTotal() }}</span>
                </a>
                <button type="button" class="botao-icone alternar-menu" aria-expanded="false" aria-controls="menu-principal" aria-label="Abrir menu">
                    <svg class="icone"><use href="#i-menu"/></svg>
                </button>
            </div>
        </div>
    </header>

    <main id="conteudo">
        @if (session('sucesso') || session('erro') || $errors->any())
            <div class="conteiner" style="padding-top:20px">
                <div class="avisos">
                    @if (session('sucesso'))
                        <div class="aviso aviso-sucesso" role="status"><svg class="icone"><use href="#i-certo"/></svg><div>{{ session('sucesso') }}</div></div>
                    @endif
                    @if (session('erro'))
                        <div class="aviso aviso-erro" role="alert"><svg class="icone"><use href="#i-aviso"/></svg><div>{{ session('erro') }}</div></div>
                    @endif
                    @if ($errors->any())
                        <div class="aviso aviso-erro" role="alert">
                            <svg class="icone"><use href="#i-aviso"/></svg>
                            <div>
                                <strong>Confira os dados informados:</strong>
                                <ul>@foreach ($errors->all() as $erro)<li>{{ $erro }}</li>@endforeach</ul>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        @endif

        @yield('conteudo')
    </main>

    <footer class="rodape">
        <div class="conteiner">
            <div class="rodape-grade">
                <div>
                    <a class="marca" href="{{ route('cardapio.index') }}">@include('partials.logo') Cupcake Gourmet</a>
                    <p>Cupcakes gourmet feitos todos os dias, com ingredientes selecionados e entrega em casa.</p>
                </div>
                <div>
                    <h4>Navegue</h4>
                    <ul>
                        <li><a href="{{ route('cardapio.index') }}">Cardápio</a></li>
                        <li><a href="{{ route('carrinho.index') }}">Carrinho</a></li>
                        <li><a href="{{ route('pedidos.index') }}">Meus pedidos</a></li>
                    </ul>
                </div>
                <div>
                    <h4>Projeto</h4>
                    <ul>
                        <li><a href="{{ route('avaliacoes.create') }}">Avaliar o site</a></li>
                        <li><a href="{{ route('creditos') }}">Créditos das imagens</a></li>
                    </ul>
                </div>
            </div>
            <div class="rodape-fim">
                Projeto acadêmico: Projeto Integrador Transdisciplinar em Engenharia de Software II. Nenhum pedido é real e nada é cobrado.
            </div>
        </div>
    </footer>

    @unless (request()->routeIs('avaliacoes.create'))
        <button type="button" class="botao-avaliar" data-abrir-janela="janela-avaliacao" aria-label="Avaliar o site">
            <svg class="icone"><use href="#i-mensagem"/></svg> <span class="rotulo-botao">Avaliar o site</span>
        </button>
        @include('partials.janela-avaliacao')
    @endunless

    <script src="{{ asset('js/app.js') }}?v={{ filemtime(public_path('js/app.js')) }}" defer></script>
</body>
</html>
