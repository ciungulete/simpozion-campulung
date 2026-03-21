<?php

use App\Models\Participant;
use App\Models\Registration;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('updates registration total on participant save', function () {
    $registration = Registration::factory()->create();
    $participant = Participant::factory()->create([
        'registration_id' => $registration->id,
        'friday_dinner_count' => 1,
        'symposium_lunch_count' => 0,
        'ball_count' => 0,
    ]);

    $registration->refresh();
    expect($registration->total_amount)->toBe(200);

    $participant->update(['friday_dinner_count' => 3]);
    $registration->refresh();
    expect($registration->total_amount)->toBe(600);
});

it('updates registration total on participant delete', function () {
    $registration = Registration::factory()->create();

    Participant::factory()->create([
        'registration_id' => $registration->id,
        'friday_dinner_count' => 1,
        'symposium_lunch_count' => 0,
        'ball_count' => 0,
    ]);

    $participant2 = Participant::factory()->create([
        'registration_id' => $registration->id,
        'friday_dinner_count' => 2,
        'symposium_lunch_count' => 0,
        'ball_count' => 0,
    ]);

    $registration->refresh();
    expect($registration->total_amount)->toBe(600);

    $participant2->delete();
    $registration->refresh();
    expect($registration->total_amount)->toBe(200);
});

it('recalculates total from participants', function () {
    $registration = Registration::factory()->create();

    $participant = Participant::factory()->create([
        'registration_id' => $registration->id,
        'friday_dinner_count' => 2,
        'symposium_lunch_count' => 1,
        'ball_count' => 1,
    ]);

    $registration->refresh();
    $expected = Participant::computeCost([
        'friday_dinner_count' => $participant->friday_dinner_count,
        'symposium_lunch_count' => $participant->symposium_lunch_count,
        'ball_count' => $participant->ball_count,
    ]);
    expect($registration->total_amount)->toBe($expected);
});
