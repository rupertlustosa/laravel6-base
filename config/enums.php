<?php

return [
    'genders' => [
        'MALE' => 'Masculino',
        'FEMALE' => 'Feminino',
    ],
    'boolean' => [
        '0' => 'Não',
        '1' => 'Sim'
    ],
    'payment_status' => [
        'DONE' => 'Realizado',
        'NOT_DONE' => 'Não Realizado',
        'PENDING' => 'Pendente',
    ],
    'rating_range' => range(1, 5),
    'check_pending_rating_sales_date' => 7,#verifica se tem abastecimentos pendentes de avaliação nos últimos x dias
    'crop' => [
        'image' => [
            "width" => 800,
            "height" => 600,
        ],
    ],
];
