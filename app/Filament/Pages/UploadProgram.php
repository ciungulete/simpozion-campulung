<?php

namespace App\Filament\Pages;

use BackedEnum;
use Filament\Forms\Components\FileUpload;
use Filament\Schemas\Components\Section;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Facades\Storage;

class UploadProgram extends Page
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedDocumentArrowUp;

    protected static ?string $navigationLabel = 'Program PDF';

    protected static ?string $title = 'Încarcă Programul';

    protected static ?int $navigationSort = 99;

    protected string $view = 'filament.pages.upload-program';

    /** @var array<string, mixed>|null */
    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill();
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->statePath('data')
            ->components([
                Section::make('Program în Română')
                    ->schema([
                        FileUpload::make('program_ro')
                            ->label('Program PDF (Română)')
                            ->acceptedFileTypes(['application/pdf'])
                            ->maxSize(50240)
                            ->disk('public')
                            ->directory('')
                            ->visibility('public')
                            ->helperText('Programul în limba română. Va fi afișat utilizatorilor care au selectat limba română.'),
                    ]),
                Section::make('Program in English')
                    ->schema([
                        FileUpload::make('program_en')
                            ->label('Program PDF (English)')
                            ->acceptedFileTypes(['application/pdf'])
                            ->maxSize(50240)
                            ->disk('public')
                            ->directory('')
                            ->visibility('public')
                            ->helperText('The program in English. Displayed to users who selected the English language.'),
                    ]),
            ]);
    }

    public function save(): void
    {
        $data = $this->form->getState();

        foreach (['ro', 'en'] as $lang) {
            $key = "program_{$lang}";
            $filename = "program-{$lang}.pdf";

            if (! empty($data[$key])) {
                if (Storage::disk('public')->exists($filename)) {
                    Storage::disk('public')->delete($filename);
                }

                Storage::disk('public')->move($data[$key], $filename);
            }
        }

        Notification::make()
            ->title('Programele au fost încărcate cu succes!')
            ->success()
            ->send();

        $this->form->fill();
    }

    public function hasProgramRo(): bool
    {
        return Storage::disk('public')->exists('program-ro.pdf');
    }

    public function hasProgramEn(): bool
    {
        return Storage::disk('public')->exists('program-en.pdf');
    }
}
