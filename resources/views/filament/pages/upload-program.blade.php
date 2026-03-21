<x-filament-panels::page>
    <form wire:submit="save">
        {{ $this->form }}

        <div class="mt-4 flex items-center gap-4">
            <x-filament::button type="submit">
                Încarcă Programul
            </x-filament::button>

            @if($this->hasExistingProgram())
                <span class="text-sm text-success-600 dark:text-success-400">
                    Program existent disponibil pentru descărcare
                </span>
            @else
                <span class="text-sm text-warning-600 dark:text-warning-400">
                    Niciun program încărcat
                </span>
            @endif
        </div>
    </form>
</x-filament-panels::page>
