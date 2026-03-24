<?php

use App\Models\Participant;
use App\Models\Registration;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('shows registration summary on payment page', function () {
    $registration = Registration::factory()->create(['total_amount' => 400]);
    Participant::factory()->create([
        'registration_id' => $registration->id,
        'full_name' => 'Ion Popescu',
        'friday_dinner_count' => 2,
        'symposium_lunch_count' => 0,
        'ball_count' => 0,
    ]);

    $this->get("/payment/{$registration->uuid}")
        ->assertStatus(200)
        ->assertSee('Ion Popescu')
        ->assertSee('400');
});

it('returns 404 for invalid uuid', function () {
    $this->get('/payment/invalid-uuid-here')
        ->assertStatus(404);
});

it('hides payment for zero total', function () {
    $registration = Registration::factory()->create(['total_amount' => 0]);
    Participant::factory()->create([
        'registration_id' => $registration->id,
        'friday_dinner_count' => 0,
        'symposium_lunch_count' => 0,
        'companion_lunch_count' => 0,
        'ball_count' => 0,
        'ritual_participation' => true,
    ]);

    $this->get("/payment/{$registration->uuid}")
        ->assertStatus(200)
        ->assertSee('Nu este necesară plata')
        ->assertDontSee('Transfer bancar');
});

it('shows payment details for non-zero total', function () {
    $registration = Registration::factory()->create(['total_amount' => 200]);
    Participant::factory()->create([
        'registration_id' => $registration->id,
        'friday_dinner_count' => 1,
        'symposium_lunch_count' => 0,
        'ball_count' => 0,
    ]);

    $this->get("/payment/{$registration->uuid}")
        ->assertStatus(200)
        ->assertSee('Transfer bancar')
        ->assertSee('Revolut')
        ->assertSee(config('simpozion.payment.iban'));
});

it('shows registration reference', function () {
    $registration = Registration::factory()->create();

    $this->get("/payment/{$registration->uuid}")
        ->assertStatus(200)
        ->assertSee($registration->shortReference());
});
