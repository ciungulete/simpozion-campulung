<?php

namespace App\Filament\Resources\Plansas\Schemas;

use App\Models\Plansa;
use App\Support\QrCodeGenerator;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\HtmlString;

class PlansaForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Detalii planșă')
                    ->columns(1)
                    ->schema([
                        TextInput::make('title')
                            ->label('Titlu')
                            ->required()
                            ->maxLength(255),
                        FileUpload::make('file_path')
                            ->label('Document PDF')
                            ->required()
                            ->acceptedFileTypes(['application/pdf'])
                            ->maxSize(20480)
                            ->disk('public')
                            ->directory('planse')
                            ->visibility('public')
                            ->downloadable()
                            ->openable()
                            ->previewable(false),
                    ]),
                Section::make('Cod QR')
                    ->visible(fn (?Plansa $record): bool => $record !== null)
                    ->schema([
                        Placeholder::make('qr_code')
                            ->label('')
                            ->content(fn (?Plansa $record): HtmlString => self::qrPlaceholder($record)),
                    ]),
            ]);
    }

    private static function qrPlaceholder(?Plansa $record): HtmlString
    {
        if ($record === null) {
            return new HtmlString('');
        }

        $url = $record->publicUrl();
        $svg = app(QrCodeGenerator::class)->svg($url);

        return new HtmlString(<<<HTML
            <div class="flex flex-col gap-3 items-start">
                <div class="rounded-lg border border-gray-200 dark:border-gray-700 bg-white p-3 inline-block">
                    <div style="width: 220px; height: 220px;">{$svg}</div>
                </div>
                <div class="text-sm">
                    <div class="font-medium text-gray-700 dark:text-gray-300 mb-1">Link public:</div>
                    <a href="{$url}" target="_blank" rel="noopener" class="text-primary-600 hover:underline break-all">{$url}</a>
                </div>
                <a href="{$url}/qr" target="_blank" rel="noopener" class="text-sm text-primary-600 hover:underline">Descarcă codul QR (PNG)</a>
            </div>
        HTML);
    }
}
