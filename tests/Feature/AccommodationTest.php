<?php

use App\Models\Accommodation;
use App\Models\Participant;
use App\Models\Registration;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('shows active accommodations on the payment page', function () {
    $registration = Registration::factory()->create(['total_amount' => 200]);
    Participant::factory()->create([
        'registration_id' => $registration->id,
        'friday_dinner_count' => 1,
    ]);

    $accommodation = Accommodation::factory()->create([
        'name' => 'Hotel Muscelul',
        'phone' => '0248-123-456',
        'website' => 'https://hotel-muscelul.ro',
        'pricing' => 'Single - 200 lei/noapte',
    ]);

    $this->get("/payment/{$registration->uuid}")
        ->assertSuccessful()
        ->assertSee('Hotel Muscelul')
        ->assertSee('0248-123-456')
        ->assertSee('https://hotel-muscelul.ro')
        ->assertSee('Single - 200 lei/noapte');
});

it('hides inactive accommodations on the payment page', function () {
    $registration = Registration::factory()->create(['total_amount' => 200]);
    Participant::factory()->create([
        'registration_id' => $registration->id,
        'friday_dinner_count' => 1,
    ]);

    Accommodation::factory()->inactive()->create([
        'name' => 'Hotel Ascuns',
    ]);

    $this->get("/payment/{$registration->uuid}")
        ->assertSuccessful()
        ->assertDontSee('Hotel Ascuns');
});

it('shows accommodations ordered by sort_order', function () {
    $registration = Registration::factory()->create(['total_amount' => 200]);
    Participant::factory()->create([
        'registration_id' => $registration->id,
        'friday_dinner_count' => 1,
    ]);

    Accommodation::factory()->create(['name' => 'Hotel B', 'sort_order' => 2]);
    Accommodation::factory()->create(['name' => 'Hotel A', 'sort_order' => 1]);

    $response = $this->get("/payment/{$registration->uuid}");
    $response->assertSuccessful();

    $content = $response->getContent();
    $posA = strpos($content, 'Hotel A');
    $posB = strpos($content, 'Hotel B');

    expect($posA)->toBeLessThan($posB);
});

it('does not show accommodations section when none exist', function () {
    $registration = Registration::factory()->create(['total_amount' => 200]);
    Participant::factory()->create([
        'registration_id' => $registration->id,
        'friday_dinner_count' => 1,
    ]);

    $this->get("/payment/{$registration->uuid}")
        ->assertSuccessful()
        ->assertDontSee('Cazare recomandată');
});

it('allows admin to create accommodations in filament', function () {
    $admin = User::factory()->create(['id' => 1]);

    $this->actingAs($admin)
        ->get('/admin/accommodations/create')
        ->assertSuccessful();
});

it('allows admin to list accommodations in filament', function () {
    $admin = User::factory()->create(['id' => 1]);

    Accommodation::factory()->count(3)->create();

    $this->actingAs($admin)
        ->get('/admin/accommodations')
        ->assertSuccessful();
});
