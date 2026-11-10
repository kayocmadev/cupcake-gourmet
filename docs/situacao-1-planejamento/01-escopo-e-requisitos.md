# 1. Escopo e requisitos

Projeto Integrador Transdisciplinar em Engenharia de Software II, continuação do PIT I
(prototipação da interface de um aplicativo de venda de cupcakes gourmet).

## 1.1 Escopo

O **Cupcake Gourmet** é uma aplicação web responsiva (usada principalmente no celular) em que o
cliente conhece o cardápio de cupcakes, monta o pedido e acompanha a entrega. A loja atualiza o
andamento dos pedidos em um painel simples.

Fora do escopo desta versão: pagamento online (o pagamento é feito na entrega), cálculo de
frete por distância, cupons e controle de estoque.

## 1.2 Revisão do que foi definido no PIT I

No PIT I a interface foi prototipada com três fluxos principais, que continuam valendo:

| Fluxo | O que cobre |
|---|---|
| Autenticação | cadastro, entrada e saída do cliente |
| Compra | cardápio, detalhes do cupcake, carrinho e finalização do pedido |
| Acompanhamento | lista de pedidos e andamento de cada pedido |

A tela **Home** é o ponto central da navegação e todas as demais telas permitem voltar a ela.

Melhorias de escopo feitas nesta etapa (ciclo de melhoria contínua):

- **categorias, fotos e destaque** no cardápio, **frete grátis** acima de um valor e **formulário de avaliação** dentro do próprio site, para facilitar o teste com usuários (Situação 3);
- inclusão do **painel da loja** (perfil administrador), porque o acompanhamento só faz sentido se
  alguém atualizar o status do pedido;
- definição de **regras de negócio** explícitas (seção 1.5);
- definição das **mensagens de erro** de cada tela (documento `04-interface-e-navegacao.md`);
- escolha de uma **arquitetura MVC** com Laravel e banco MySQL.

## 1.3 Requisitos funcionais

| Código | Requisito | Perfil |
|---|---|---|
| RF01 | Cadastrar-se informando nome, e-mail, telefone e senha | Visitante |
| RF02 | Entrar e sair do sistema | Cliente, Administrador |
| RF03 | Ver o cardápio de cupcakes disponíveis, com busca por nome | Todos |
| RF04 | Ver os detalhes de um cupcake (descrição, sabor, preço) | Todos |
| RF05 | Adicionar cupcakes ao carrinho, alterar quantidades e remover itens | Todos |
| RF06 | Finalizar o pedido informando endereço, forma de pagamento e observações | Cliente |
| RF07 | Consultar os próprios pedidos e o andamento de cada um | Cliente |
| RF08 | Cancelar um pedido que ainda não saiu para entrega | Cliente |
| RF09 | Listar todos os pedidos e atualizar o status de cada um | Administrador |
| RF10 | Cadastrar, editar e ativar/desativar cupcakes do cardápio, com foto, categoria e marca de "mais pedido" | Administrador |
| RF11 | Filtrar o cardápio por categoria (Tradicionais, Frutas, Gourmet) | Todos |
| RF12 | Enviar a avaliação do teste do sistema (o que funcionou, o que falhou, sugestões, nota e captura de tela), sem precisar de conta | Todos |
| RF13 | Consultar as avaliações recebidas e exportá-las em PDF | Administrador |

## 1.4 Requisitos não funcionais

| Código | Requisito |
|---|---|
| RNF01 | Interface responsiva, pensada primeiro para telas de celular |
| RNF02 | Arquitetura MVC (Laravel), banco de dados relacional MySQL |
| RNF03 | Senhas armazenadas com hash; proteção contra CSRF nos formulários |
| RNF04 | Validação de todos os dados de entrada, com mensagens de erro claras em português |
| RNF05 | Código coberto por testes automatizados (PHPUnit) |
| RNF06 | Um cliente só pode ver e cancelar os seus próprios pedidos |
| RNF07 | Interface com contraste adequado e rótulos em todos os campos de formulário |

## 1.5 Regras de negócio

| Código | Regra |
|---|---|
| RN01 | O e-mail do cliente é único no sistema |
| RN02 | A senha deve ter no mínimo 8 caracteres |
| RN03 | Só é possível finalizar um pedido com o carrinho não vazio e o cliente autenticado |
| RN04 | A quantidade de cada cupcake no carrinho vai de 1 a 20 |
| RN05 | O preço do cupcake é copiado para o item do pedido no momento da compra, para que mudanças futuras no cardápio não alterem pedidos antigos |
| RN06 | O total do pedido é a soma de quantidade × preço de cada item, mais a taxa de entrega fixa de R$ 8,00. Pedidos com subtotal a partir de R$ 70,00 têm **frete grátis** (taxa zero) |
| RN07 | O status do pedido segue a ordem: recebido, em preparo, saiu para entrega, entregue |
| RN08 | O cliente só pode cancelar pedidos com status "recebido" ou "em preparo" |
| RN09 | Cupcakes desativados não aparecem no cardápio nem podem ser adicionados ao carrinho |
| RN10 | A avaliação do teste exige nome e data (não futura); a nota, quando informada, vai de 1 a 5; a captura de tela aceita só JPG, PNG ou WebP de até 4 MB |

## 1.6 Histórias de usuário

1. Como **visitante**, quero ver o cardápio para decidir se quero comprar antes de me cadastrar.
2. Como **cliente**, quero montar meu pedido e informar o endereço de entrega para receber em casa.
3. Como **cliente**, quero acompanhar o status do pedido para saber quando ele chega.
4. Como **cliente**, quero cancelar um pedido se mudar de ideia antes de sair para entrega.
5. Como **administrador**, quero atualizar o status dos pedidos para manter o cliente informado.
6. Como **administrador**, quero editar o cardápio para incluir novos sabores e tirar os indisponíveis.
