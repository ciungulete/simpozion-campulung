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
            $programFile = "program-{$locale}.pdf";
            $hasProgram = file_exists(storage_path("app/public/{$programFile}"));
        @endphp

        @if($hasProgram)
            <a href="{{ asset("storage/{$programFile}") }}"
               target="_blank"
               class="mt-6 inline-flex items-center gap-2 rounded-full border border-amber-500/20 bg-amber-500/10 px-5 py-2 text-sm font-medium text-amber-300 transition hover:border-amber-500/40 hover:bg-amber-500/20">
                <flux:icon.document-text variant="mini" class="size-4" />
                {{ __('Download Program') }}
            </a>
        @endif
    </div>

    @if(! $this->isRegistrationOpen)
        <div class="overflow-hidden rounded-2xl border border-amber-500/20 bg-gradient-to-b from-amber-500/5 to-transparent">
            <div class="p-8 text-center sm:p-12">
                <div class="mx-auto mb-4 flex size-16 items-center justify-center rounded-full bg-amber-500/10">
                    <svg class="size-8 text-amber-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z" />
                    </svg>
                </div>
                <h2 class="text-2xl font-bold text-white">{{ __('Registration Closed') }}</h2>
                <p class="mx-auto mt-3 max-w-md text-sm leading-relaxed text-white/60">
                    {{ __('Registration for this event has been closed. Thank you for your interest!') }}
                </p>
                <p class="mt-2 text-xs text-white/40">
                    {{ __('For any questions, please contact the organizers.') }}
                </p>
            </div>
        </div>

        @if(config('simpozion.whatsapp_group_link'))
            <div class="mt-6 overflow-hidden rounded-2xl border border-emerald-500/20 bg-emerald-500/5 p-6 text-center">
                <a href="{{ config('simpozion.whatsapp_group_link') }}"
                   target="_blank"
                   class="inline-flex items-center gap-2 rounded-xl bg-emerald-600 px-6 py-3 text-sm font-semibold text-white transition hover:bg-emerald-500">
                    <svg class="size-5" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                    {{ __('Join WhatsApp group') }}
                </a>
            </div>
        @endif
    @else
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
                    <div class="grid grid-cols-[70px_1fr] gap-4 sm:grid-cols-[1fr_90px_1fr]">
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
                                    <div class="text-xs text-amber-400/70">{{ config('simpozion.events.friday_dinner.price') }} {{ __('lei / person') }}</div>
                                    <div class="text-[11px] text-white/30">{{ config("simpozion.events.friday_dinner.datetime.{$locale}") }}</div>
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
                                    <div class="text-xs text-amber-400/70">{{ config('simpozion.events.symposium_lunch.price') }} {{ __('lei / person') }}</div>
                                    <div class="text-[11px] text-white/30">{{ config("simpozion.events.symposium_lunch.datetime.{$locale}") }}</div>
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

                            {{-- Companion lunch (Saturday) --}}
                            <div class="flex items-center justify-between rounded-xl border border-white/[0.06] bg-white/[0.02] px-4 py-3">
                                <div class="min-w-0 flex-1">
                                    <div class="text-sm font-medium text-white/90">{{ __('Lunch (for companions)') }}</div>
                                    <div class="text-xs text-amber-400/70">{{ config('simpozion.events.companion_lunch.price') }} {{ __('lei / person') }}</div>
                                    <div class="text-[11px] text-white/30">{{ config("simpozion.events.companion_lunch.datetime.{$locale}") }}</div>
                                </div>
                                <div class="flex items-center gap-1">
                                    <button type="button" wire:click="decrementCount({{ $index }}, 'companion_lunch_count')"
                                            class="flex size-8 items-center justify-center rounded-lg border border-white/10 text-white/60 transition hover:border-white/20 hover:bg-white/5 hover:text-white">
                                        <flux:icon.minus variant="micro" class="size-3.5" />
                                    </button>
                                    <span class="w-8 text-center text-sm font-semibold text-white">{{ $participant['companion_lunch_count'] }}</span>
                                    <button type="button" wire:click="incrementCount({{ $index }}, 'companion_lunch_count')"
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
                                    <div class="text-[11px] text-white/30">{{ config("simpozion.events.ritual.datetime.{$locale}") }}</div>
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
                                    <div class="text-xs text-amber-400/70">{{ config('simpozion.events.ball.price') }} {{ __('lei / person') }}</div>
                                    <div class="text-[11px] text-white/30">{{ config("simpozion.events.ball.datetime.{$locale}") }}</div>
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
    @endif
</div>
