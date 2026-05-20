<?php

use App\Filament\Resources\Participants\Actions\ExportParticipantsAction;
use App\Filament\Resources\Participants\Pages\ListParticipants;
use App\Models\Participant;
use App\Models\Registration;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use OpenSpout\Reader\XLSX\Reader;
use Symfony\Component\HttpFoundation\StreamedResponse;

uses(RefreshDatabase::class);

it('registers the export action in the participants table header', function () {
    $admin = User::factory()->create(['id' => 1]);

    $this->actingAs($admin);

    Livewire::test(ListParticipants::class)
        ->assertTableActionExists('exportExcel');
});

it('produces an xlsx download with total/paid/remaining columns', function () {
    $registration = Registration::factory()->create(['paid_amount' => 100]);
    $participant = Participant::factory()->create([
        'registration_id' => $registration->id,
        'full_name' => 'Test Person',
        'friday_dinner_count' => 1,
        'symposium_lunch_count' => 0,
        'companion_lunch_count' => 0,
        'ball_count' => 0,
    ]);

    $reflection = new ReflectionMethod(ExportParticipantsAction::class, 'download');
    $reflection->setAccessible(true);

    /** @var StreamedResponse $response */
    $response = $reflection->invoke(null, Participant::query()->with('registration'));

    expect($response)->toBeInstanceOf(StreamedResponse::class);

    $tempPath = tempnam(sys_get_temp_dir(), 'export-test').'.xlsx';
    ob_start();
    $response->sendContent();
    file_put_contents($tempPath, ob_get_clean());

    $reader = new Reader;
    $reader->open($tempPath);

    $rows = [];
    foreach ($reader->getSheetIterator() as $sheet) {
        foreach ($sheet->getRowIterator() as $row) {
            $rows[] = $row->toArray();
        }
        break;
    }
    $reader->close();
    @unlink($tempPath);

    expect($rows)->toHaveCount(2);

    $header = $rows[0];
    $data = $rows[1];

    expect($header)->toContain('Total de plată (lei)', 'Achitat (lei)', 'Rest de plată (lei)');

    $totalIdx = array_search('Total de plată (lei)', $header, true);
    $paidIdx = array_search('Achitat (lei)', $header, true);
    $remainingIdx = array_search('Rest de plată (lei)', $header, true);
    $nameIdx = array_search('Nume', $header, true);

    expect($data[$nameIdx])->toBe('Test Person');
    expect((int) $data[$totalIdx])->toBe($participant->calculateCost());
    expect((int) $data[$paidIdx])->toBe(100);
    expect((int) $data[$remainingIdx])->toBe($participant->calculateCost() - 100);
});
