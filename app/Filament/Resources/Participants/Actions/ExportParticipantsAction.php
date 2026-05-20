<?php

namespace App\Filament\Resources\Participants\Actions;

use App\Models\Participant;
use Filament\Actions\Action;
use Filament\Support\Icons\Heroicon;
use Illuminate\Database\Eloquent\Builder;
use OpenSpout\Common\Entity\Row;
use OpenSpout\Common\Entity\Style\Color;
use OpenSpout\Common\Entity\Style\Style;
use OpenSpout\Writer\XLSX\Writer;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ExportParticipantsAction
{
    public static function make(): Action
    {
        return Action::make('exportExcel')
            ->label('Export Excel')
            ->icon(Heroicon::OutlinedArrowDownTray)
            ->color('success')
            ->action(fn ($livewire): StreamedResponse => self::download(
                $livewire->getFilteredSortedTableQuery()
            ));
    }

    protected static function download(Builder $query): StreamedResponse
    {
        $fileName = 'participanti-'.now()->format('Y-m-d-His').'.xlsx';

        return response()->streamDownload(function () use ($query): void {
            $writer = new Writer;
            $writer->openToFile('php://output');

            $headerStyle = (new Style)
                ->setFontBold()
                ->setBackgroundColor(Color::rgb(229, 231, 235));

            $writer->addRow(Row::fromValues(self::headers(), $headerStyle));

            $query->with('registration')
                ->lazy()
                ->each(function (Participant $participant) use ($writer): void {
                    $writer->addRow(Row::fromValues(self::row($participant)));
                });

            $writer->close();
        }, $fileName, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]);
    }

    /**
     * @return list<string>
     */
    protected static function headers(): array
    {
        return [
            'ID',
            'Referință',
            'Prefix',
            'Nume',
            'Grad',
            'Loja',
            'Nr. Loja',
            'Orient',
            'Email',
            'Telefon',
            'Cina Vineri',
            'Prânz Simpozion',
            'Prânz Însoțitor',
            'Ritual',
            'Bal',
            'Total de plată (lei)',
            'Achitat (lei)',
            'Rest de plată (lei)',
            'Observații',
            'Data înregistrării',
        ];
    }

    /**
     * @return list<mixed>
     */
    protected static function row(Participant $participant): array
    {
        $registration = $participant->registration;

        return [
            $participant->id,
            $registration ? strtoupper(substr($registration->uuid, 0, 8)) : null,
            $participant->prefix?->value,
            $participant->full_name,
            $participant->degree?->label(),
            $participant->lodge_name,
            $participant->lodge_number,
            $participant->orient,
            $participant->email,
            $participant->phone,
            $participant->friday_dinner_count,
            $participant->symposium_lunch_count,
            $participant->companion_lunch_count,
            $participant->ritual_participation ? 'Da' : 'Nu',
            $participant->ball_count,
            $registration?->total_amount ?? 0,
            $registration?->paid_amount ?? 0,
            $registration?->remainingAmount() ?? 0,
            $participant->observations,
            $participant->created_at?->format('d.m.Y H:i'),
        ];
    }
}
