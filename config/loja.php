<?php

return [
    // Taxa de entrega fixa (RN06). Pedidos com subtotal a partir do valor abaixo têm frete grátis.
    'taxa_entrega' => 8.00,
    'frete_gratis_acima' => 70.00,

    // Limites de quantidade de cada cupcake no carrinho (RN04).
    'quantidade_minima' => 1,
    'quantidade_maxima' => 20,

    'formas_pagamento' => [
        'pix' => 'Pix na entrega',
        'dinheiro' => 'Dinheiro na entrega',
        'cartao' => 'Cartão na entrega',
    ],
];
