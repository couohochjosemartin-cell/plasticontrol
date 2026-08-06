<?php

return [
    'admin' => [
        'nombre' => env('PLASTICONTROL_ADMIN_NOMBRE', 'Administrador General'),
        'usuario' => env('PLASTICONTROL_ADMIN_USUARIO', 'admin'),
        'password' => env('PLASTICONTROL_ADMIN_PASSWORD'),
    ],

    'negocio' => [
        'nombre' => env('PLASTICONTROL_NEGOCIO_NOMBRE', 'PlastiControl'),
        'propietario' => env('PLASTICONTROL_NEGOCIO_PROPIETARIO', 'Propietario'),
        'desarrollador' => env(
            'PLASTICONTROL_DESARROLLADOR',
            'Jose Martin Couoh Och'
        ),
    ],
];