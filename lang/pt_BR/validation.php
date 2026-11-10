<?php

return [
    'required' => 'O campo :attribute é obrigatório.',
    'string' => 'O campo :attribute deve ser um texto.',
    'email' => 'Informe um e-mail válido, como nome@exemplo.com.',
    'unique' => 'Este :attribute já está em uso.',
    'confirmed' => 'A confirmação de :attribute não confere.',
    'integer' => 'O campo :attribute deve ser um número inteiro.',
    'numeric' => 'O campo :attribute deve ser um número.',
    'boolean' => 'O campo :attribute deve ser verdadeiro ou falso.',
    'in' => 'O valor escolhido para :attribute é inválido.',
    'exists' => 'O valor escolhido para :attribute não existe.',
    'min' => [
        'string' => 'O campo :attribute deve ter pelo menos :min caracteres.',
        'numeric' => 'O campo :attribute deve ser no mínimo :min.',
    ],
    'max' => [
        'string' => 'O campo :attribute deve ter no máximo :max caracteres.',
        'numeric' => 'O campo :attribute deve ser no máximo :max.',
    ],
    'between' => [
        'numeric' => 'O campo :attribute deve estar entre :min e :max.',
    ],
    'attributes' => [
        'name' => 'nome',
        'email' => 'e-mail',
        'telefone' => 'telefone',
        'password' => 'senha',
        'endereco_entrega' => 'endereço de entrega',
        'forma_pagamento' => 'forma de pagamento',
        'observacao' => 'observação',
        'quantidade' => 'quantidade',
        'nome' => 'nome',
        'descricao' => 'descrição',
        'sabor' => 'sabor',
        'preco' => 'preço',
        'ativo' => 'situação',
    ],
];
