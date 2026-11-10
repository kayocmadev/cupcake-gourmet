# Cupcake Gourmet 🧁

Aplicação web de venda de cupcakes gourmet: cardápio, carrinho, pedido e acompanhamento da
entrega, com painel simples para a loja. Projeto do **Projeto Integrador Transdisciplinar em
Engenharia de Software II**, continuação da prototipação feita no PIT I.

## Tecnologias

| Camada | Tecnologia |
|---|---|
| Back-end | PHP 8.3 com Laravel 13, arquitetura **MVC** |
| Banco de dados | **MySQL 8** (migrations e seeders do Laravel) |
| Front-end | HTML (Blade), CSS e JavaScript simples, responsivo (mobile first) |
| Testes | PHPUnit (testes de unidade e de funcionalidade) |

Padrão MVC no projeto:

- **Model** (`app/Models`): `User`, `Produto`, `Pedido`, `ItemPedido`, com as regras de negócio (status, cancelamento).
- **View** (`resources/views`): templates Blade.
- **Controller** (`app/Http/Controllers`): recebem a requisição, validam e chamam os serviços.
- Serviços (`app/Services`): `Carrinho` (sessão) e `PedidoService` (criação do pedido em transação).

## Como rodar

Requisitos: PHP 8.3+, Composer e MySQL.

```bash
git clone <url-do-repositório>
cd cupcake-gourmet
composer install
cp .env.example .env          # ajuste DB_DATABASE, DB_USERNAME e DB_PASSWORD
php artisan key:generate
php artisan migrate --seed    # cria as tabelas, o cardápio e contas de demonstração
php artisan serve             # http://localhost:8000
```

Contas de demonstração (criadas pelo seeder):

| Perfil | E-mail | Senha |
|---|---|---|
| Administrador | admin@cupcakegourmet.test | [senha-removida] |
| Cliente | cliente@cupcakegourmet.test | [senha-removida] |

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
└── situacao-3-testes/         plano e relatório de testes, formulário dos testadores, laudo de qualidade
```

O código-fonte fica na estrutura padrão do Laravel (`app/`, `routes/`, `resources/`, `database/`,
`tests/`).

## Licença

Projeto acadêmico, sem fins comerciais.
