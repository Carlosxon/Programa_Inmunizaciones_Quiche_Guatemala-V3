<?php

return [
    'required' => 'El campo :attribute es obligatorio.',
    'exists' => 'El :attribute seleccionado no es válido.',
    'date' => 'El campo :attribute debe ser una fecha válida.',
    'integer' => 'El campo :attribute debe ser un número entero.',
    'min' => [
        'numeric' => 'El campo :attribute debe ser al menos :min.',
    ],
    'array' => 'El campo :attribute debe ser un array.',

    // Puedes agregar más mensajes personalizados aquí

    'attributes' => [
        'from_warehouse_id' => 'Desde Bodega',
        'to_warehouse_id' => 'Hacia Bodega',
        'transfer_date' => 'Fecha de Transferencia',
        'products' => 'Productos',
        'products.*.id' => 'ID del producto',
        'products.*.quantity' => 'Cantidad del producto',
    ],
];
