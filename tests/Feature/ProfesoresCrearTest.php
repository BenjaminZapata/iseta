<?php

use Faker\Factory as Faker;

it('creates a profesor with fake data', function () {
    $faker = Faker::create();

    $data = [
        'nombre' => $faker->firstName,
        'apellido' => $faker->lastName,
        'dni' => $faker->unique()->numerify('########'), // 8 dígitos
        'fecha_nacimiento' => $faker->date('Y-m-d', '2000-01-01'),
        'estado_civil' => $faker->randomElement(['Soltero', 'Casado', 'Divorciado', 'Viudo', 'Conyuge', 'Otro']),
        'lugar_nacimiento' => $faker->city,
        'ciudad' => $faker->city,
        'codigo_postal' => $faker->postcode,
        'calle' => $faker->streetName,
        'casa_numero' => $faker->buildingNumber,
        'dpto' => $faker->optional()->randomLetter,
        'piso' => $faker->optional()->numberBetween(1, 10),
        'formacion_academica' => $faker->jobTitle,
        'anio_ingreso' => $faker->year,
        'email' => $faker->unique()->safeEmail,
        'telefono1' => '+54-11-' . $faker->numerify('########'),
        'telefono2' => $faker->optional()->numerify('11-########'),
        'telefono3' => $faker->optional()->numerify('11-########'),
        'observaciones' => $faker->optional()->sentence,
    ];

    $response = $this->post(route('admin.profesores.store'), $data);

    $response->assertStatus(302);
    $response->assertSessionHasNoErrors();
});
