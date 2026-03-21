<x-filament-panels::page>
    <div class="space-y-6">
        <div class="rounded-xl border p-6 {{ $this->isRegistrationOpen() ? 'border-success-500/30 bg-success-500/5' : 'border-danger-500/30 bg-danger-500/5' }}">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-semibold {{ $this->isRegistrationOpen() ? 'text-success-600 dark:text-success-400' : 'text-danger-600 dark:text-danger-400' }}">
                        {{ $this->isRegistrationOpen() ? 'Înregistrările sunt DESCHISE' : 'Înregistrările sunt ÎNCHISE' }}
                    </h3>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                        @if($this->isRegistrationOpen())
                            Participanții pot completa formularul de înregistrare.
                        @else
                            Formularul de înregistrare nu mai acceptă participanți noi. Se afișează un mesaj informativ.
                        @endif
                    </p>
                </div>

                <x-filament::button
                    wire:click="toggleRegistration"
                    :color="$this->isRegistrationOpen() ? 'danger' : 'success'"
                    icon="{{ $this->isRegistrationOpen() ? 'heroicon-o-lock-closed' : 'heroicon-o-lock-open' }}"
                >
                    {{ $this->isRegistrationOpen() ? 'Închide înregistrările' : 'Deschide înregistrările' }}
                </x-filament::button>
            </div>
        </div>
    </div>
</x-filament-panels::page>
