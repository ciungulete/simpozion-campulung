<?php

use App\Livewire\RegistrationForm;
use App\Models\Participant;
use App\Models\Registration;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

uses(RefreshDatabase::class);

it('renders registration form', function () {
    $this->get('/')->assertStatus(200);
});

it('has one participant by default', function () {
    Livewire::test(RegistrationForm::class)
        ->assertSet('participants', fn ($participants) => count($participants) === 1);
});

it('can add a participant', function () {
    Livewire::test(RegistrationForm::class)
        ->call('addParticipant')
        ->assertSet('participants', fn ($participants) => count($participants) === 2);
});

it('cannot remove last participant', function () {
    Livewire::test(RegistrationForm::class)
        ->call('removeParticipant', 0)
        ->assertSet('participants', fn ($participants) => count($participants) === 1);
});

it('can remove a participant when multiple exist', function () {
    Livewire::test(RegistrationForm::class)
        ->call('addParticipant')
        ->call('removeParticipant', 0)
        ->assertSet('participants', fn ($participants) => count($participants) === 1);
});

it('validates required fields', function () {
    Livewire::test(RegistrationForm::class)
        ->set('participants.0.full_name', '')
        ->set('participants.0.lodge_name', '')
        ->set('participants.0.lodge_number', '')
        ->set('participants.0.orient', '')
        ->set('participants.0.email', '')
        ->set('participants.0.phone', '')
        ->call('submit')
        ->assertHasErrors([
            'participants.0.full_name',
            'participants.0.lodge_name',
            'participants.0.lodge_number',
            'participants.0.orient',
            'participants.0.email',
            'participants.0.phone',
        ]);
});

it('validates email format', function () {
    Livewire::test(RegistrationForm::class)
        ->set('participants.0.email', 'not-an-email')
        ->call('submit')
        ->assertHasErrors(['participants.0.email']);
});

it('validates lodge number is positive', function () {
    Livewire::test(RegistrationForm::class)
        ->set('participants.0.lodge_number', 0)
        ->call('submit')
        ->assertHasErrors(['participants.0.lodge_number']);
});

it('validates event count max', function () {
    Livewire::test(RegistrationForm::class)
        ->set('participants.0.friday_dinner_count', 21)
        ->call('submit')
        ->assertHasErrors(['participants.0.friday_dinner_count']);
});

it('creates registration with participants on submit', function () {
    Livewire::test(RegistrationForm::class)
        ->set('participants.0.prefix', 'FR∴')
        ->set('participants.0.full_name', 'Ion Popescu')
        ->set('participants.0.degree', 'ucenic')
        ->set('participants.0.dignity', 'Venerable Maestru')
        ->set('participants.0.lodge_name', 'Loja Test')
        ->set('participants.0.lodge_number', 42)
        ->set('participants.0.orient', 'București')
        ->set('participants.0.email', 'ion@test.com')
        ->set('participants.0.phone', '+40 700 000 000')
        ->set('participants.0.friday_dinner_count', 2)
        ->set('participants.0.symposium_lunch_count', 1)
        ->set('participants.0.ritual_participation', true)
        ->set('participants.0.ball_count', 0)
        ->call('submit')
        ->assertHasNoErrors()
        ->assertRedirect();

    expect(Registration::count())->toBe(1);
    expect(Participant::count())->toBe(1);

    $registration = Registration::first();
    expect($registration->total_amount)->toBe(600); // (2*200) + (1*200) + (0*350)
    expect($registration->uuid)->not()->toBeNull();

    $participant = Participant::first();
    expect($participant->full_name)->toBe('Ion Popescu');
    expect($participant->friday_dinner_count)->toBe(2);
});

it('calculates total correctly with multiple participants', function () {
    Livewire::test(RegistrationForm::class)
        ->set('participants.0.prefix', 'FR∴')
        ->set('participants.0.full_name', 'Ion Popescu')
        ->set('participants.0.degree', 'ucenic')
        ->set('participants.0.dignity', 'Maestru')
        ->set('participants.0.lodge_name', 'Loja A')
        ->set('participants.0.lodge_number', 1)
        ->set('participants.0.orient', 'București')
        ->set('participants.0.email', 'ion@test.com')
        ->set('participants.0.phone', '+40700000000')
        ->set('participants.0.friday_dinner_count', 1)
        ->set('participants.0.symposium_lunch_count', 1)
        ->set('participants.0.ball_count', 1)
        ->call('addParticipant')
        ->set('participants.1.prefix', 'FR∴')
        ->set('participants.1.full_name', 'Maria Ionescu')
        ->set('participants.1.degree', 'calfa')
        ->set('participants.1.dignity', 'Orator')
        ->set('participants.1.lodge_name', 'Loja B')
        ->set('participants.1.lodge_number', 2)
        ->set('participants.1.orient', 'Cluj')
        ->set('participants.1.email', 'maria@test.com')
        ->set('participants.1.phone', '+40700000001')
        ->set('participants.1.friday_dinner_count', 2)
        ->set('participants.1.symposium_lunch_count', 0)
        ->set('participants.1.ball_count', 0)
        ->call('submit')
        ->assertHasNoErrors();

    $registration = Registration::first();
    // P1: (1*200) + (1*200) + (1*350) = 750
    // P2: (2*200) + (0*200) + (0*350) = 400
    // Total: 1150
    expect($registration->total_amount)->toBe(1150);
    expect($registration->participants->count())->toBe(2);
});

it('redirects to payment page after submit', function () {
    Livewire::test(RegistrationForm::class)
        ->set('participants.0.prefix', 'FR∴')
        ->set('participants.0.full_name', 'Test User')
        ->set('participants.0.degree', 'ucenic')
        ->set('participants.0.dignity', 'Secretar')
        ->set('participants.0.lodge_name', 'Loja Test')
        ->set('participants.0.lodge_number', 1)
        ->set('participants.0.orient', 'București')
        ->set('participants.0.email', 'test@test.com')
        ->set('participants.0.phone', '+40700000000')
        ->set('participants.0.friday_dinner_count', 0)
        ->set('participants.0.symposium_lunch_count', 0)
        ->set('participants.0.ball_count', 0)
        ->call('submit')
        ->assertRedirect();

    $registration = Registration::first();
    expect($registration)->not()->toBeNull();
});

it('handles email failure gracefully', function () {
    Mail::shouldReceive('to->send')->andThrow(new Exception('SMTP error'));

    Livewire::test(RegistrationForm::class)
        ->set('participants.0.prefix', 'FR∴')
        ->set('participants.0.full_name', 'Test User')
        ->set('participants.0.degree', 'ucenic')
        ->set('participants.0.dignity', 'Secretar')
        ->set('participants.0.lodge_name', 'Loja Test')
        ->set('participants.0.lodge_number', 1)
        ->set('participants.0.orient', 'București')
        ->set('participants.0.email', 'test@test.com')
        ->set('participants.0.phone', '+40700000000')
        ->set('participants.0.friday_dinner_count', 1)
        ->set('participants.0.symposium_lunch_count', 0)
        ->set('participants.0.ball_count', 0)
        ->call('submit')
        ->assertRedirect();

    expect(Registration::count())->toBe(1);
});
