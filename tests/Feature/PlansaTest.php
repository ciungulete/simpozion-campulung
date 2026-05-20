<?php

use App\Models\Plansa;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;

uses(RefreshDatabase::class);

it('generates a uuid automatically when creating a plansa', function () {
    $plansa = Plansa::create([
        'title' => 'Test Planșă',
        'file_path' => 'planse/test.pdf',
    ]);

    expect($plansa->uuid)->not->toBeEmpty();
    expect($plansa->uuid)->toMatch('/^[0-9a-f-]{36}$/');
});

it('exposes a public url using the uuid', function () {
    $plansa = Plansa::create([
        'title' => 'Test Planșă',
        'file_path' => 'planse/test.pdf',
    ]);

    expect($plansa->publicUrl())->toContain($plansa->uuid);
    expect($plansa->publicUrl())->toContain('/plansa/');
});

it('serves the pdf inline via the public route', function () {
    Storage::fake('public');
    Storage::disk('public')->put('planse/test.pdf', '%PDF-1.4 fake content');

    $plansa = Plansa::create([
        'title' => 'Test Planșă',
        'file_path' => 'planse/test.pdf',
    ]);

    $response = $this->get("/plansa/{$plansa->uuid}");

    $response->assertSuccessful()
        ->assertHeader('Content-Type', 'application/pdf');

    expect($response->headers->get('Content-Disposition'))->toContain('inline');
});

it('returns 404 when the underlying pdf is missing', function () {
    Storage::fake('public');

    $plansa = Plansa::create([
        'title' => 'Missing PDF',
        'file_path' => 'planse/missing.pdf',
    ]);

    $this->get("/plansa/{$plansa->uuid}")->assertNotFound();
});

it('serves a png qr code for the plansa', function () {
    Storage::fake('public');

    $plansa = Plansa::create([
        'title' => 'QR Planșă',
        'file_path' => 'planse/qr.pdf',
    ]);

    $this->get("/plansa/{$plansa->uuid}/qr")
        ->assertSuccessful()
        ->assertHeader('Content-Type', 'image/png');
});

it('allows admin to access the create planse page in filament', function () {
    $admin = User::factory()->create(['id' => 1]);

    $this->actingAs($admin)
        ->get('/admin/plansas/create')
        ->assertSuccessful();
});

it('blocks non-admin users from creating planse', function () {
    $user = User::factory()->create(['id' => 99]);

    $this->actingAs($user)
        ->get('/admin/plansas/create')
        ->assertForbidden();
});
