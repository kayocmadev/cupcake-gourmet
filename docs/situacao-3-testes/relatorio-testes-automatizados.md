# Relatório dos testes automatizados

Execução de `php artisan test` em 10/11/2026, com PHP 8.3 e Laravel 13.

| Ambiente do banco | Resultado |
|---|---|
| SQLite em memória (padrão do `phpunit.xml`) | 67 testes, 232 verificações, todos passaram |
| MySQL 8 (banco `cupcake_gourmet_test`) | 67 testes, 232 verificações, todos passaram |

Saída detalhada (`vendor/bin/phpunit --testdox`):

```
PHPUnit 12.5.43 by Sebastian Bergmann and contributors.

Runtime:       PHP 8.3.31
Configuration: ./phpunit.xml

................................................................. 65 / 67 ( 97%)
..                                                                67 / 67 (100%)

Time: 00:01.188, Memory: 69.00 MB

Admin (Tests\Feature\Admin)
 ✔ Cliente comum nao acessa o painel
 ✔ Visitante e enviado para o login
 ✔ Admin lista todos os pedidos
 ✔ Admin filtra pedidos por status
 ✔ Admin avanca o status ate a entrega
 ✔ Admin nao avanca pedido entregue ou cancelado
 ✔ Admin cadastra cupcake
 ✔ Cadastro de cupcake valida os dados
 ✔ Admin envia foto ao cadastrar cupcake
 ✔ Arquivo que nao e imagem e rejeitado no cadastro
 ✔ Admin edita e desativa cupcake

Autenticacao (Tests\Feature\Autenticacao)
 ✔ Visitante consegue se cadastrar e fica autenticado
 ✔ Senha e gravada com hash
 ✔ Cadastro rejeita email repetido
 ✔ Cadastro rejeita senha curta ou sem confirmacao
 ✔ Cadastro rejeita telefone invalido e campos vazios
 ✔ Login com dados corretos
 ✔ Login com senha errada mostra mensagem generica
 ✔ Logout encerra a sessao
 ✔ Usuario autenticado nao acessa telas de entrada

Avaliacao (Tests\Feature\Avaliacao)
 ✔ Pagina do formulario abre sem login
 ✔ Qualquer pessoa envia avaliacao sem conta
 ✔ Nome e data sao obrigatorios
 ✔ Data futura e nota invalida sao rejeitadas
 ✔ Captura de tela e gravada e arquivo invalido e rejeitado
 ✔ Admin ve as avaliacoes e baixa o pdf
 ✔ Cliente comum nao ve as avaliacoes

Cardapio (Tests\Feature\Cardapio)
 ✔ Cardapio lista apenas cupcakes ativos
 ✔ Busca filtra por nome ou sabor
 ✔ Busca sem resultado mostra mensagem
 ✔ Detalhe do cupcake mostra descricao e preco
 ✔ Cupcake desativado retorna 404 no detalhe

Carrinho (Tests\Feature\Carrinho)
 ✔ Carrinho comeca vazio
 ✔ Adicionar cupcake ao carrinho
 ✔ Adicionar o mesmo cupcake soma as quantidades
 ✔ Quantidade acima do limite e rejeitada
 ✔ Soma acima do limite fica no maximo
 ✔ Cupcake desativado nao pode ser adicionado
 ✔ Atualizar quantidade do item
 ✔ Remover item do carrinho
 ✔ Produto desativado depois de adicionado sai do carrinho
 ✔ Menu mostra contador de itens

Categorias EFrete (Tests\Feature\CategoriasEFrete)
 ✔ Filtro por categoria mostra so os cupcakes dela
 ✔ Categoria invalida e ignorada
 ✔ Cardapio sem filtro agrupa por categoria
 ✔ Detalhe sugere cupcakes da mesma categoria
 ✔ Taxa de entrega e cobrada abaixo do valor do frete gratis
 ✔ Frete gratis a partir do valor minimo
 ✔ Pedido com frete gratis grava taxa zero e total correto
 ✔ Pagina de creditos lista os fotografos

Erros (Tests\Feature\Erros)
 ✔ Pagina inexistente mostra tela de erro amigavel
 ✔ Cupcake inexistente mostra tela de erro amigavel

Pedido (Tests\Feature\Pedido)
 ✔ Visitante e enviado para o login ao finalizar
 ✔ Finalizar com carrinho vazio volta ao cardapio com aviso
 ✔ Cliente finaliza pedido com itens e total corretos
 ✔ Carrinho e esvaziado depois da finalizacao
 ✔ Preco do item nao muda quando o cardapio muda
 ✔ Dados invalidos nao criam pedido
 ✔ Cliente ve apenas os proprios pedidos na lista
 ✔ Cliente nao acessa pedido de outro cliente
 ✔ Detalhe do pedido mostra status e itens
 ✔ Cliente cancela pedido recebido
 ✔ Cliente nao cancela pedido que ja saiu para entrega

Pedido (Tests\Unit\Pedido)
 ✔ So pode cancelar antes de sair para entrega
 ✔ Proximo status segue o fluxo normal
 ✔ Subtotal desconta a taxa de entrega
 ✔ Rotulo do status e legivel

OK (67 tests, 232 assertions)
```
