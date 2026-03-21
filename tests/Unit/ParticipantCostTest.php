<?php

use App\Models\Participant;

it('computes cost correctly with all events', function () {
    $prices = ['friday_dinner' => 200, 'symposium_lunch' => 200, 'ball' => 350];

    $cost = Participant::computeCost([
        'friday_dinner_count' => 2,
        'symposium_lunch_count' => 1,
        'ball_count' => 3,
    ], $prices);

    expect($cost)->toBe(1650);
});

it('computes zero cost when all counts are zero', function () {
    $prices = ['friday_dinner' => 200, 'symposium_lunch' => 200, 'ball' => 350];

    $cost = Participant::computeCost([
        'friday_dinner_count' => 0,
        'symposium_lunch_count' => 0,
        'ball_count' => 0,
    ], $prices);

    expect($cost)->toBe(0);
});

it('computes cost with missing keys defaulting to zero', function () {
    $prices = ['friday_dinner' => 200, 'symposium_lunch' => 200, 'ball' => 350];

    $cost = Participant::computeCost([
        'friday_dinner_count' => 1,
    ], $prices);

    expect($cost)->toBe(200);
});

it('computes cost with custom prices', function () {
    $cost = Participant::computeCost([
        'friday_dinner_count' => 1,
        'symposium_lunch_count' => 1,
        'ball_count' => 1,
    ], [
        'friday_dinner' => 100,
        'symposium_lunch' => 150,
        'ball' => 500,
    ]);

    expect($cost)->toBe(750);
});
