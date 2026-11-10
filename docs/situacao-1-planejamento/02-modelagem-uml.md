# 2. Modelagem UML

Os diagramas usam a sintaxe Mermaid, que o GitHub exibe direto no navegador.

## 2.1 Diagrama de casos de uso

```mermaid
flowchart LR
    V([Visitante])
    C([Cliente])
    A([Administrador])

    subgraph Sistema[Cupcake Gourmet]
        UC1(Ver cardápio)
        UC2(Ver detalhes do cupcake)
        UC3(Gerenciar carrinho)
        UC4(Cadastrar-se)
        UC5(Entrar e sair)
        UC6(Finalizar pedido)
        UC7(Acompanhar pedidos)
        UC8(Cancelar pedido)
        UC9(Atualizar status do pedido)
        UC10(Gerenciar cardápio)
        UC11(Avaliar o site)
        UC12(Consultar avaliações e exportar PDF)
    end

    V --- UC1
    V --- UC2
    V --- UC3
    V --- UC4
    V --- UC5
    C --- UC1
    C --- UC3
    C --- UC6
    C --- UC7
    C --- UC8
    A --- UC9
    A --- UC10
    A --- UC12
    V --- UC11
    C --- UC11
    UC6 -. inclui .-> UC5
    UC7 -. inclui .-> UC5
```

## 2.2 Diagrama de classes

```mermaid
classDiagram
    class User {
        +int id
        +string name
        +string email
        +string telefone
        +string password
        +bool is_admin
        +pedidos()
    }
    class Produto {
        +int id
        +string nome
        +string descricao
        +string sabor
        +string categoria
        +decimal preco
        +string imagem
        +bool ativo
        +bool destaque
        +urlImagem() string
    }
    class Pedido {
        +int id
        +int user_id
        +string status
        +decimal taxa_entrega
        +decimal total
        +string endereco_entrega
        +string forma_pagamento
        +string observacao
        +itens()
        +podeSerCancelado() bool
        +cancelar()
        +avancarStatus()
    }
    class ItemPedido {
        +int id
        +int pedido_id
        +int produto_id
        +int quantidade
        +decimal preco_unitario
        +subtotal() decimal
    }
    class Carrinho {
        +adicionar(produto, quantidade)
        +atualizar(produto, quantidade)
        +remover(produto)
        +itens()
        +subtotal() decimal
        +taxaEntrega() decimal
        +faltaParaFreteGratis() decimal
        +esvaziar()
    }
    class Avaliacao {
        +int id
        +string nome
        +date data_teste
        +string funcionou
        +string nao_funcionou
        +string nao_testado
        +string sugestoes
        +int nota
        +string captura
    }

    User "1" --> "0..*" Pedido : faz
    Pedido "1" *-- "1..*" ItemPedido : contém
    Produto "1" <-- "0..*" ItemPedido : refere-se a
    Carrinho ..> Produto : usa
```

A classe `Carrinho` não é persistida: ela vive na sessão do usuário e só vira `Pedido` e
`ItemPedido` na finalização da compra.

## 2.3 Diagrama de sequência: finalizar pedido

```mermaid
sequenceDiagram
    actor Cliente
    participant V as View (checkout)
    participant C as PedidoController
    participant K as Carrinho
    participant M as Pedido / ItemPedido
    participant DB as MySQL

    Cliente->>V: preenche endereço e forma de pagamento
    V->>C: POST /pedidos
    C->>C: valida os dados
    alt dados inválidos
        C-->>V: volta com mensagens de erro
    else carrinho vazio
        C-->>V: redireciona ao cardápio com aviso
    else dados válidos
        C->>K: itens()
        K-->>C: lista de produtos e quantidades
        C->>DB: inicia transação
        C->>M: cria Pedido (status recebido)
        C->>M: cria ItemPedido com preço atual de cada produto
        M->>DB: grava
        C->>DB: confirma transação
        C->>K: esvaziar()
        C-->>V: redireciona para o pedido criado
        V-->>Cliente: mostra confirmação e status
    end
```

## 2.4 Diagrama de atividades: do cardápio ao acompanhamento

```mermaid
flowchart TD
    A([Início]) --> B[Abrir o cardápio]
    B --> C[Escolher cupcakes e adicionar ao carrinho]
    C --> D{Quer finalizar?}
    D -- Não --> B
    D -- Sim --> E{Está autenticado?}
    E -- Não --> F[Entrar ou cadastrar-se]
    F --> G
    E -- Sim --> G[Informar endereço e forma de pagamento]
    G --> H{Dados válidos?}
    H -- Não --> I[Mostrar mensagens de erro] --> G
    H -- Sim --> J[Registrar pedido com status recebido]
    J --> K[Acompanhar o status do pedido]
    K --> L{Status}
    L -- recebido ou em preparo --> M{Cancelar?}
    M -- Sim --> N[Pedido cancelado] --> Z
    M -- Não --> K
    L -- saiu para entrega --> K
    L -- entregue --> Z([Fim])
```

## 2.5 Diagrama de estados do pedido

```mermaid
stateDiagram-v2
    [*] --> recebido
    recebido --> em_preparo
    recebido --> cancelado
    em_preparo --> saiu_para_entrega
    em_preparo --> cancelado
    saiu_para_entrega --> entregue
    entregue --> [*]
    cancelado --> [*]
```
