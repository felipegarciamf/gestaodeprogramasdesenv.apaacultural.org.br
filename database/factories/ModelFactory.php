<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

$factory->define(App\User::class, function (Faker\Generator $faker) {
    return [
        'email' => $faker->safeEmail,
		'nome' => $faker->name,
		'sobrenome' => $faker->lastname,
		'perfil' => $faker->randomElement([2,3,4,5,6,7,8,9]),
		'senha' => bcrypt(str_random(10)),
		'remember_token' => str_random(10)
    ];
});

$factory->define(App\Cg::class, function (Faker\Generator $faker) {
    return [
		'nome' => $faker->name
    ];
});

$factory->define(App\Objeto::class, function (Faker\Generator $faker) {
    return [
		'nome' => $faker->name
    ];
});

$factory->define(App\Os::class, function (Faker\Generator $faker) {
    return [
		'nome' => $faker->name
    ];
});

$factory->define(App\Uge::class, function (Faker\Generator $faker) {
    return [
		'nome' => $faker->name
    ];
});

$factory->define(App\TipoObjeto::class, function (Faker\Generator $faker) {
    return [
		'nome' => $faker->name
    ];
});

$factory->define(App\EspecieAcao::class, function (Faker\Generator $faker) {
    return [
		'nome' => $faker->name
    ];
});

$factory->define(App\LinguagemAcao::class, function (Faker\Generator $faker) {
    return [
		'nome' => $faker->name
    ];
});

$factory->define(App\FuncaoAcao::class, function (Faker\Generator $faker) {
    return [
		'nome' => $faker->name
    ];
});

$factory->define(App\RegiaoAdministrativa::class, function (Faker\Generator $faker) {
    return [
		'nome' => $faker->name
    ];
});

$factory->define(App\TipoPublico::class, function (Faker\Generator $faker) {
    return [
		'nome' => $faker->name
    ];
});

$factory->define(App\EngajamentoPublico::class, function (Faker\Generator $faker) {
    return [
		'nome' => $faker->name
    ];
});

$factory->define(App\SegmentoPublico::class, function (Faker\Generator $faker) {
    return [
		'nome' => $faker->name
    ];
});

$factory->define(App\TipoEvento::class, function (Faker\Generator $faker) {
    return [
        'nome' => $faker->name
    ];
});

$factory->define(App\LinguagemPrograma::class, function (Faker\Generator $faker) {
    return [
		'nome' => $faker->name
    ];
});

$factory->define(App\GeneroLinguagem::class, function (Faker\Generator $faker) {
    return [
        'nome' => $faker->name,
        'linguagem_programa_id' => $faker->randomElement([2,3,4,5,6,7,8,9])
    ];
});

$factory->define(App\Realizador::class, function (Faker\Generator $faker) {
    return [
        'nome' => $faker->name
    ];
});