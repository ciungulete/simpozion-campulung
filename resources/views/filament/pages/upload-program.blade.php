<x-filament-panels::page>
    <form wire:submit="save">
        {{ $this->form }}

        <div class="mt-6 flex flex-wrap items-center gap-4">
            <x-filament::button type="submit">
                Salvează
            </x-filament::button>

            <div class="flex flex-col gap-1 text-sm">
                @if($this->hasProgramRo())
                    <span class="text-success-600 dark:text-success-400">RO: Program încărcat</span>
                @else
                    <span class="text-warning-600 dark:text-warning-400">RO: Niciun program</span>
                @endif

                @if($this->hasProgramEn())
                    <span class="text-success-600 dark:text-success-400">EN: Program uploaded</span>
                @else
                    <span class="text-warning-600 dark:text-warning-400">EN: No program</span>
                @endif
            </div>
        </div>
    </form>
</x-filament-panels::page>
