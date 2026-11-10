<?php

return [
    // Taxa de entrega fixa aplicada a todos os pedidos (RN06).
    'taxa_entrega' => 8.00,

    // Limites de quantidade de cada cupcake no carrinho (RN04).
    'quantidade_minima' => 1,
    'quantidade_maxima' => 20,

    'formas_pagamento' => [
        'pix' => 'Pix na entrega',
        'dinheiro' => 'Dinheiro na entrega',
        'cartao' => 'Cartão na entrega',
    ],
];
