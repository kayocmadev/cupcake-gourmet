# Plano de testes

Este plano cobre a **verificação** (o sistema foi construído conforme os requisitos?) e a
**validação** (o sistema serve a quem vai usá-lo?).

## 1. Testes automatizados (verificação)

Executados com `php artisan test` (PHPUnit). Cada teste está ligado a um requisito ou regra.

| Arquivo | O que verifica | Requisitos / regras |
|---|---|---|
| `tests/Feature/CardapioTest.php` | Cardápio mostra só cupcakes ativos, busca por nome e sabor, detalhe, 404 para desativado | RF03, RF04, RN09 |
| `tests/Feature/AutenticacaoTest.php` | Cadastro, e-mail único, senha mínima e com hash, telefone, login, mensagem genérica de erro, logout | RF01, RF02, RN01, RN02, RNF03 |
| `tests/Feature/CarrinhoTest.php` | Adicionar, somar, limite de 1 a 20, atualizar, remover, desativado fora do carrinho, contador no menu | RF05, RN04, RN09 |
| `tests/Feature/PedidoTest.php` | Finalização com total correto, carrinho vazio, validação, preço congelado, acesso só ao próprio pedido, cancelamento | RF06, RF07, RF08, RN03, RN05, RN06, RN08, RNF06 |
| `tests/Feature/AdminTest.php` | Acesso restrito, lista e filtro de pedidos, avanço de status, cadastro e edição de cupcake | RF09, RF10, RN07 |
| `tests/Feature/ErrosTest.php` | Telas de erro amigáveis | RNF04 |
| `tests/Unit/PedidoTest.php` | Regras de status do pedido sem banco de dados | RN07, RN08 |

O resultado da última execução está em `relatorio-testes-automatizados.md`.

## 2. Testes com usuários (validação)

Cinco pessoas (colegas de curso ou profissionais da área) usam a aplicação publicada e registram
o resultado no **formulário de teste** (`formulario-de-teste.md`). Cada pessoa percorre o roteiro
abaixo e anota o que funcionou, o que não funcionou e o que não foi possível testar.

### Roteiro de teste

| # | Cenário | Resultado esperado |
|---|---|---|
| 1 | Abrir o cardápio e buscar um sabor | Lista filtrada com os cupcakes do sabor |
| 2 | Abrir os detalhes de um cupcake | Descrição e preço aparecem |
| 3 | Adicionar dois cupcakes diferentes ao carrinho | Contador do menu soma as quantidades |
| 4 | Alterar uma quantidade e remover um item | Subtotal e total acompanham as mudanças |
| 5 | Tentar finalizar sem ter conta | Sistema pede para entrar ou cadastrar |
| 6 | Cadastrar-se com dados válidos | Conta criada e usuário já autenticado |
| 7 | Tentar cadastrar com e-mail repetido ou senha curta | Mensagem de erro clara, sem criar a conta |
| 8 | Finalizar o pedido com endereço e forma de pagamento | Pedido criado com status "Recebido" |
| 9 | Finalizar com o endereço vazio | Mensagem pedindo o endereço completo |
| 10 | Abrir "Meus pedidos" e acompanhar o pedido | Linha do tempo com o status atual |
| 11 | Cancelar o pedido enquanto "Recebido" | Status muda para "Cancelado" |
| 12 | Usar o sistema pelo celular | Telas legíveis e botões fáceis de tocar |
| 13 | Entrar com a senha errada | Mensagem "E-mail ou senha incorretos." |

A conta de administrador é usada apenas pela pessoa responsável pelo projeto para avançar os
status, o que permite ao testador ver o acompanhamento mudar.

## 3. Critério de aceite

O sistema é considerado aprovado quando todos os testes automatizados passam e os cenários 1 a 11
funcionam para os cinco testadores. Falhas encontradas são registradas no laudo de qualidade,
corrigidas e testadas de novo.
