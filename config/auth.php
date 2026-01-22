<?php

return [

    'defaults' => [
        'guard' => 'administradores', // Cambia el guard por defecto
        'passwords' => 'administradores',
    ],

    'guards' => [
        'web' => [
            'driver' => 'session',
            'provider' => 'administradores',
        ],

        'api' => [
            'driver' => 'token',
            'provider' => 'administradores',
            'hash' => false,
        ],
        
        // Guard específico para administradores
        'administradores' => [
            'driver' => 'session',
            'provider' => 'administradores',
        ],
        
        // Guard para aingresos
        'aingreso' => [
            'driver' => 'session',
            'provider' => 'aingresos',
        ],
        
        // Guard para acompras
        'acompra' => [
            'driver' => 'session',
            'provider' => 'acompras',
        ],
        
        // Guard para adestajos
        'adestajo' => [
            'driver' => 'session',
            'provider' => 'adestajos',
        ],
    ],

    'providers' => [
        // Proveedor para administradores
        'administradores' => [
            'driver' => 'eloquent',
            'model' => App\Models\Administrador::class,
        ],
        
        // Proveedor para aingresos
        'aingresos' => [
            'driver' => 'eloquent',
            'model' => App\Models\AIngreso::class,
        ],
        
        // Proveedor para acompras
        'acompras' => [
            'driver' => 'eloquent',
            'model' => App\Models\ACompra::class,
        ],
        
        // Proveedor para adestajos
        'adestajos' => [
            'driver' => 'eloquent',
            'model' => App\Models\ADestajo::class,
        ],
        
        // Mantén el de users por si acaso
        'users' => [
            'driver' => 'eloquent',
            'model' => App\Models\User::class,
        ],
    ],

    'passwords' => [
        'administradores' => [
            'provider' => 'administradores',
            'table' => 'password_resets',
            'expire' => 60,
            'throttle' => 60,
        ],
        
        'aingresos' => [
            'provider' => 'aingresos',
            'table' => 'password_resets',
            'expire' => 60,
            'throttle' => 60,
        ],
        
        'acompras' => [
            'provider' => 'acompras',
            'table' => 'password_resets',
            'expire' => 60,
            'throttle' => 60,
        ],
        
        'adestajos' => [
            'provider' => 'adestajos',
            'table' => 'password_resets',
            'expire' => 60,
            'throttle' => 60,
        ],
        
        // Mantén el de users
        'users' => [
            'provider' => 'users',
            'table' => 'password_resets',
            'expire' => 60,
            'throttle' => 60,
        ],
    ],

    'password_timeout' => 10800,
];