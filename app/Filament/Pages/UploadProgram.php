<?php

namespace App\Filament\Pages;

use BackedEnum;
use Filament\Forms\Components\FileUpload;
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
                FileUpload::make('program')
                    ->label('Program PDF')
                    ->acceptedFileTypes(['application/pdf'])
                    ->maxSize(10240)
                    ->disk('public')
                    ->directory('')
                    ->visibility('public')
                    ->helperText('Încărcați fișierul PDF cu programul evenimentului. Acesta va fi disponibil pentru descărcare pe pagina de înregistrare.'),
            ]);
    }

    public function save(): void
    {
        $data = $this->form->getState();

        if (! empty($data['program'])) {
            $uploadedPath = $data['program'];

            if (Storage::disk('public')->exists('program.pdf')) {
                Storage::disk('public')->delete('program.pdf');
            }

            Storage::disk('public')->move($uploadedPath, 'program.pdf');
        }

        Notification::make()
            ->title('Programul a fost încărcat cu succes!')
            ->success()
            ->send();

        $this->form->fill();
    }

    public function hasExistingProgram(): bool
    {
        return Storage::disk('public')->exists('program.pdf');
    }
}
