<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(Munkireport_model::class, function (Faker\Generator $faker) {
    $error_list = [
        "Could not reach server", "Manifest not found",
    ];
    $error_count = $faker->numberBetween(0, count($error_list));

    $warning_list = [
        "Could not process item Firefox for install. No pkginfo found in catalogs: testing",
    ];
    $warning_count = $faker->numberBetween(0, count($warning_list));

    $starttime = $faker->dateTimeBetween('-4 months', 'now');
    $endtime = $faker->dateTimeBetween($starttime, 'now');
    $timestamp = $faker->dateTimeBetween($endtime, 'now');

    return [
        'runtype' => 'auto',
        'version' => $faker->numerify('#.#.#'),
        'errors' => $error_count,
        'warnings' => $warning_count,
        'manifestname' => $faker->word,
        'error_json' => json_encode($faker->randomElements($error_list, $error_count)),
        'warning_json' => json_encode($faker->randomElements($warning_list, $warning_count)),
        'starttime' => $starttime,
        'endtime' => $endtime,
        'timestamp' => $timestamp,
    ];
});
