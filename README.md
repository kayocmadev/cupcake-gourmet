# Cupcake Gourmet 🧁

Aplicação web de venda de cupcakes gourmet: cardápio por categoria com fotos, carrinho com frete
grátis, pedido, acompanhamento da entrega, painel para a loja e formulário de avaliação do site
integrado. Projeto do **Projeto Integrador Transdisciplinar em
Engenharia de Software II**, continuação da prototipação feita no PIT I.

## Tecnologias

| Camada | Tecnologia |
|---|---|
| Back-end | PHP 8.3 com Laravel 13, arquitetura **MVC** |
| Banco de dados | **MySQL 8** (migrations e seeders do Laravel) |
| Front-end | HTML (Blade), CSS e JavaScript puros, responsivo (mobile first), sem build |
| PDF | dompdf (exportação das avaliações) |
| Testes | PHPUnit (testes de unidade e de funcionalidade) |

Padrão MVC no projeto:

- **Model** (`app/Models`): `User`, `Produto`, `Pedido`, `ItemPedido`, com as regras de negócio (status, cancelamento).
- **View** (`resources/views`): templates Blade.
- **Controller** (`app/Http/Controllers`): recebem a requisição, validam e chamam os serviços.
- Serviços (`app/Services`): `Carrinho` (sessão) e `PedidoService` (criação do pedido em transação).

## Funcionalidades

- Cardápio com fotos, categorias (Tradicionais, Frutas, Gourmet), busca e etiqueta "Mais pedido".
- Carrinho com quantidade de 1 a 20 por item e **frete grátis a partir de R$ 70**.
- Cadastro e login; finalização com endereço e forma de pagamento (pago na entrega).
- Acompanhamento do pedido em linha do tempo e cancelamento antes de sair para entrega.
- Painel da loja: pedidos e status, cardápio (com envio de foto) e avaliações.
- **Avaliar o site:** botão em todas as telas, com formulário (o que funcionou, o que não
  funcionou, o que não foi testado, sugestões, nota e captura de tela) e exportação em PDF.
- Telas de erro amigáveis, acessibilidade básica e layout responsivo.

## Como rodar

Requisitos: PHP 8.3+, Composer e MySQL.

```bash
git clone <url-do-repositório>
cd cupcake-gourmet
composer install
cp .env.example .env          # ajuste DB_DATABASE, DB_USERNAME e DB_PASSWORD
php artisan key:generate
php artisan migrate --seed    # cria as tabelas, o cardápio e contas de demonstração
# fotos enviadas pelo painel e capturas das avaliações ficam em public/uploads (sem storage:link)
php artisan serve             # http://localhost:8000
```

O seeder cria uma conta de administrador (`admin@cupcakegourmet.test`) e uma de cliente
(`cliente@cupcakegourmet.test`). As senhas não ficam no código: defina `ADMIN_PASSWORD` e
`CLIENTE_DEMO_PASSWORD` no `.env` antes de rodar o seeder ou, se deixar em branco, uma senha
aleatória é gerada e exibida uma única vez no terminal.

## Testes automatizados

```bash
php artisan test
```

Os testes usam SQLite em memória por padrão (`phpunit.xml`), então não mexem no banco de
desenvolvimento. Para rodar contra o MySQL, defina `DB_CONNECTION=mysql` e um banco de teste.

## Estrutura da documentação (por situação do desafio)

```
docs/
├── situacao-1-planejamento/   requisitos, UML, modelo de dados e dicionário, interface (IHC)
├── situacao-2-codigo/         manual de uso e arquitetura
├── situacao-3-testes/         plano e relatório de testes, formulário dos testadores, laudo de qualidade
└── creditos-das-imagens.md    fotógrafos das imagens (Pexels, licença livre)
```

O código-fonte fica na estrutura padrão do Laravel (`app/`, `routes/`, `resources/`, `database/`,
`tests/`).

## Licença

Projeto acadêmico, sem fins comerciais.
