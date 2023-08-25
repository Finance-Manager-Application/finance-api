<?php

return [
    [
        'first_name' => 'John',
        'last_name' => 'Doe',
        'email' => 'john.doe@example.com',
        'password' => bcrypt('password123'),
        'address' => '123 Main Street, Anytown, USA',
    ],
    [
        'first_name' => 'Jane',
        'last_name' => 'Smith',
        'email' => 'jane.smith@example.com',
        'password' => bcrypt('securepassword'),
        'address' => '456 Elm Avenue, Cityville, Canada',
    ],
];
