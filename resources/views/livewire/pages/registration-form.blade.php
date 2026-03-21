<?php

use App\Enums\Degree;
use App\Enums\Prefix;

?>

<div>
    {{-- Language Picker --}}
    <div class="mb-6 flex justify-end">
        <div class="inline-flex overflow-hidden rounded-full border border-white/[0.08] bg-white/[0.03] text-xs">
            <button type="button" wire:click="switchLocale('ro')"
                    class="px-3 py-1.5 font-medium transition {{ $locale === 'ro' ? 'bg-amber-500 text-black' : 'text-white/50 hover:text-white/80' }}">
                RO
            </button>
            <button type="button" wire:click="switchLocale('en')"
                    class="px-3 py-1.5 font-medium transition {{ $locale === 'en' ? 'bg-amber-500 text-black' : 'text-white/50 hover:text-white/80' }}">
                EN
            </button>
        </div>
    </div>

    {{-- Header --}}
    <div class="mb-10 text-center">
        <h1 class="text-3xl font-bold tracking-tight text-white sm:text-4xl">{{ config("simpozion.event_name.{$locale}") }}</h1>
        <p class="mt-1 text-lg font-medium text-amber-400/90 sm:text-xl">{{ config("simpozion.event_title.{$locale}") }}</p>
        <p class="mt-0.5 text-sm italic text-white/50">{{ config("simpozion.event_subtitle.{$locale}") }}</p>
        <div class="mt-3 text-xs font-semibold uppercase tracking-[0.25em] text-amber-400/80">{{ config("simpozion.event_edition.{$locale}") }}</div>
        <div class="mt-3 flex items-center justify-center gap-3 text-xs text-white/40">
            <span>{{ config('simpozion.event_location') }}</span>
            <span class="text-amber-500/40">|</span>
            <span>{{ config("simpozion.event_date.{$locale}") }}</span>
        </div>

        @php
            $programPath = storage_path('app/public/program.pdf');
            $hasProgram = file_exists($programPath);
        @endphp

        @if($hasProgram)
            <a href="{{ asset('storage/program.pdf') }}"
               target="_blank"
               class="mt-6 inline-flex items-center gap-2 rounded-full border border-amber-500/20 bg-amber-500/10 px-5 py-2 text-sm font-medium text-amber-300 transition hover:border-amber-500/40 hover:bg-amber-500/20">
                <flux:icon.document-text variant="mini" class="size-4" />
                {{ __('Download Program') }}
            </a>
        @endif
    </div>

    <form wire:submit="submit">
        @foreach($participants as $index => $participant)
            <div class="mb-5 overflow-hidden rounded-2xl border border-white/[0.06] bg-white/[0.03] shadow-xl backdrop-blur-sm" wire:key="participant-{{ $index }}">
                {{-- Card header --}}
                <div class="flex items-center justify-between border-b border-white/[0.06] bg-white/[0.02] px-5 py-3 sm:px-6">
                    <div class="flex items-center gap-2">
                        <span class="flex size-6 items-center justify-center rounded-full bg-amber-500/20 text-xs font-bold text-amber-400">{{ $index + 1 }}</span>
                        <h2 class="text-sm font-semibold text-white/90">{{ __('Participant') }}</h2>
                    </div>
                    @if(count($participants) > 1)
                        <button type="button"
                                x-data
                                x-on:click="if (confirm('{{ __('Are you sure you want to remove this participant?') }}')) { $wire.removeParticipant({{ $index }}) }"
                                class="rounded-lg px-2.5 py-1 text-xs text-red-400/70 transition hover:bg-red-500/10 hover:text-red-400">
                            <flux:icon.trash variant="mini" class="size-4" />
                        </button>
                    @endif
                </div>

                <div class="space-y-5 p-5 sm:p-6">
                    {{-- Prefix + Name --}}
                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-[130px_1fr]">
                        <flux:select wire:model="participants.{{ $index }}.prefix" label="Prefix">
                            @foreach(Prefix::cases() as $prefixOption)
                                <flux:select.option value="{{ $prefixOption->value }}">{{ $prefixOption->value }}</flux:select.option>
                            @endforeach
                        </flux:select>

                        <flux:input wire:model="participants.{{ $index }}.full_name" :label="__('Full Name')" placeholder="ex: Popescu Ion" />
                    </div>

                    {{-- Degree + Dignity --}}
                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <flux:select wire:model="participants.{{ $index }}.degree" :label="__('Degree')">
                            @foreach(Degree::cases() as $degreeOption)
                                <flux:select.option value="{{ $degreeOption->value }}">{{ $degreeOption->label() }}</flux:select.option>
                            @endforeach
                        </flux:select>

                        <flux:input wire:model="participants.{{ $index }}.dignity" :label="__('Dignity')" />
                    </div>

                    {{-- Lodge + Number + Orient --}}
                    <div class="grid grid-cols-2 gap-4 sm:grid-cols-[1fr_90px_1fr]">
                        <div class="col-span-2 sm:col-span-1">
                            <flux:input wire:model="participants.{{ $index }}.lodge_name" :label="__('Lodge Name')" />
                        </div>
                        <flux:input wire:model="participants.{{ $index }}.lodge_number" :label="__('Lodge No.')" type="number" min="1" />
                        <flux:input wire:model="participants.{{ $index }}.orient" :label="__('Orient')" />
                    </div>

                    {{-- Email + Phone --}}
                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <flux:input wire:model="participants.{{ $index }}.email" label="Email" type="email" />
                        <flux:input wire:model="participants.{{ $index }}.phone" :label="__('Phone')" placeholder="+40 7XX XXX XXX" />
                    </div>

                    {{-- Separator --}}
                    <div class="border-t border-white/[0.06]"></div>

                    {{-- Events --}}
                    <div>
                        <h3 class="mb-4 text-xs font-semibold uppercase tracking-[0.15em] text-amber-400/80">{{ __('Events') }}</h3>

                        <div class="space-y-2.5">
                            {{-- Friday dinner --}}
                            <div class="flex items-center justify-between rounded-xl border border-white/[0.06] bg-white/[0.02] px-4 py-3">
                                <div class="min-w-0 flex-1">
                                    <div class="text-sm font-medium text-white/90">{{ __('Friday Dinner') }}</div>
                                    <div class="text-xs text-amber-400/70">{{ config('simpozion.prices.friday_dinner') }} {{ __('lei / person') }}</div>
                                </div>
                                <div class="flex items-center gap-1">
                                    <button type="button" wire:click="decrementCount({{ $index }}, 'friday_dinner_count')"
                                            class="flex size-8 items-center justify-center rounded-lg border border-white/10 text-white/60 transition hover:border-white/20 hover:bg-white/5 hover:text-white">
                                        <flux:icon.minus variant="micro" class="size-3.5" />
                                    </button>
                                    <span class="w-8 text-center text-sm font-semibold text-white">{{ $participant['friday_dinner_count'] }}</span>
                                    <button type="button" wire:click="incrementCount({{ $index }}, 'friday_dinner_count')"
                                            class="flex size-8 items-center justify-center rounded-lg border border-white/10 text-white/60 transition hover:border-amber-500/30 hover:bg-amber-500/10 hover:text-amber-400">
                                        <flux:icon.plus variant="micro" class="size-3.5" />
                                    </button>
                                </div>
                            </div>

                            {{-- Symposium + lunch --}}
                            <div class="flex items-center justify-between rounded-xl border border-white/[0.06] bg-white/[0.02] px-4 py-3">
                                <div class="min-w-0 flex-1">
                                    <div class="text-sm font-medium text-white/90">{{ __('Symposium + Saturday Lunch') }}</div>
                                    <div class="text-xs text-amber-400/70">{{ config('simpozion.prices.symposium_lunch') }} {{ __('lei / person') }}</div>
                                </div>
                                <div class="flex items-center gap-1">
                                    <button type="button" wire:click="decrementCount({{ $index }}, 'symposium_lunch_count')"
                                            class="flex size-8 items-center justify-center rounded-lg border border-white/10 text-white/60 transition hover:border-white/20 hover:bg-white/5 hover:text-white">
                                        <flux:icon.minus variant="micro" class="size-3.5" />
                                    </button>
                                    <span class="w-8 text-center text-sm font-semibold text-white">{{ $participant['symposium_lunch_count'] }}</span>
                                    <button type="button" wire:click="incrementCount({{ $index }}, 'symposium_lunch_count')"
                                            class="flex size-8 items-center justify-center rounded-lg border border-white/10 text-white/60 transition hover:border-amber-500/30 hover:bg-amber-500/10 hover:text-amber-400">
                                        <flux:icon.plus variant="micro" class="size-3.5" />
                                    </button>
                                </div>
                            </div>

                            {{-- Ritual participation --}}
                            <div class="flex items-center justify-between rounded-xl border border-white/[0.06] px-4 py-3 {{ $participant['ritual_participation'] ? 'bg-amber-500/10 border-amber-500/20' : 'bg-white/[0.02]' }}">
                                <div class="min-w-0 flex-1">
                                    <div class="text-sm font-medium text-white/90">{{ __('Ritual Session Participation') }}</div>
                                    <div class="text-xs text-white/40">{{ __('No cost') }}</div>
                                </div>
                                <label class="relative inline-flex cursor-pointer items-center">
                                    <input type="checkbox"
                                           wire:model.live="participants.{{ $index }}.ritual_participation"
                                           class="peer sr-only">
                                    <div class="peer h-6 w-11 rounded-full bg-white/10 after:absolute after:left-[2px] after:top-[2px] after:size-5 after:rounded-full after:bg-white/50 after:transition-all peer-checked:bg-amber-500 peer-checked:after:translate-x-full peer-checked:after:bg-white peer-focus:ring-2 peer-focus:ring-amber-500/30"></div>
                                </label>
                            </div>

                            {{-- Ball --}}
                            <div class="flex items-center justify-between rounded-xl border border-white/[0.06] bg-white/[0.02] px-4 py-3">
                                <div class="min-w-0 flex-1">
                                    <div class="text-sm font-medium text-white/90">{{ __('Ball Participation') }}</div>
                                    <div class="text-xs text-amber-400/70">{{ config('simpozion.prices.ball') }} {{ __('lei / person') }}</div>
                                </div>
                                <div class="flex items-center gap-1">
                                    <button type="button" wire:click="decrementCount({{ $index }}, 'ball_count')"
                                            class="flex size-8 items-center justify-center rounded-lg border border-white/10 text-white/60 transition hover:border-white/20 hover:bg-white/5 hover:text-white">
                                        <flux:icon.minus variant="micro" class="size-3.5" />
                                    </button>
                                    <span class="w-8 text-center text-sm font-semibold text-white">{{ $participant['ball_count'] }}</span>
                                    <button type="button" wire:click="incrementCount({{ $index }}, 'ball_count')"
                                            class="flex size-8 items-center justify-center rounded-lg border border-white/10 text-white/60 transition hover:border-amber-500/30 hover:bg-amber-500/10 hover:text-amber-400">
                                        <flux:icon.plus variant="micro" class="size-3.5" />
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Observations --}}
                    <flux:textarea wire:model="participants.{{ $index }}.observations" :label="__('Observations')" :placeholder="__('Any additional information...')" rows="2" />
                </div>
            </div>
        @endforeach

        {{-- Add participant --}}
        <button type="button"
                wire:click="addParticipant"
                class="mb-8 w-full rounded-2xl border-2 border-dashed border-white/[0.08] py-4 text-center text-sm font-medium text-white/40 transition hover:border-amber-500/30 hover:bg-amber-500/5 hover:text-amber-400">
            {{ __('+ Add another participant') }}
        </button>

        {{-- Total --}}
        <div class="mb-6 overflow-hidden rounded-2xl border border-amber-500/20 bg-gradient-to-r from-amber-500/10 to-amber-600/5">
            <div class="flex items-center justify-between px-6 py-5">
                <span class="text-sm font-medium uppercase tracking-wider text-white/50">{{ __('Total to pay') }}</span>
                <span class="text-3xl font-bold tracking-tight text-amber-400">{{ number_format($this->totalAmount, 0, ',', '.') }} <span class="text-lg font-normal text-amber-400/60">lei</span></span>
            </div>
        </div>

        {{-- Validation errors --}}
        @if($errors->any())
            <div class="mb-6 rounded-2xl border border-red-500/20 bg-red-500/10 p-5">
                <p class="mb-2 text-sm font-semibold text-red-400">{{ __('Please correct the following errors:') }}</p>
                <ul class="list-inside list-disc space-y-1 text-xs text-red-300/80">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Submit --}}
        <flux:button type="submit" variant="primary" class="w-full !rounded-2xl !bg-amber-500 !py-4 !text-base !font-semibold !text-black hover:!bg-amber-400">
            {{ __('Continue to payment') }}
        </flux:button>
    </form>
</div>
