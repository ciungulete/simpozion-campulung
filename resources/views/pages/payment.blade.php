<x-layouts.app>
    {{-- Language Picker --}}
    <div class="mb-6 flex justify-end">
        <div class="inline-flex overflow-hidden rounded-full border border-white/[0.08] bg-white/[0.03] text-xs">
            <a href="{{ request()->fullUrlWithQuery(['lang' => 'ro']) }}"
               class="px-3 py-1.5 font-medium transition {{ $locale === 'ro' ? 'bg-amber-500 text-black' : 'text-white/50 hover:text-white/80' }}">
                RO
            </a>
            <a href="{{ request()->fullUrlWithQuery(['lang' => 'en']) }}"
               class="px-3 py-1.5 font-medium transition {{ $locale === 'en' ? 'bg-amber-500 text-black' : 'text-white/50 hover:text-white/80' }}">
                EN
            </a>
        </div>
    </div>

    <div class="text-center">
        <div class="mb-2 text-xs font-semibold uppercase tracking-[0.25em] text-amber-400/80">{{ __('Confirmation') }}</div>
        <h1 class="text-3xl font-bold text-white">{{ __('Registration confirmed!') }}</h1>
        <p class="mt-2 text-sm text-white/40">{{ __('Reference') }}: <span class="font-mono font-medium text-white/70">{{ $registration->shortReference() }}</span></p>
    </div>

    {{-- Registration Summary --}}
    <div class="mt-8 space-y-4">
        @foreach($registration->participants as $participant)
            <div class="overflow-hidden rounded-2xl border border-white/[0.06] bg-white/[0.03]">
                <div class="flex items-center justify-between border-b border-white/[0.06] bg-white/[0.02] px-5 py-3">
                    <h3 class="text-sm font-semibold text-amber-400">
                        {{ $participant->prefix->value }} {{ $participant->full_name }}
                    </h3>
                    <span class="text-sm font-bold text-amber-400">
                        {{ number_format($participant->calculateCost(), 0, ',', '.') }} lei
                    </span>
                </div>

                <div class="p-5">
                    <div class="grid grid-cols-1 gap-2 text-xs text-white/60 sm:grid-cols-2">
                        <div><span class="text-white/30">{{ __('Degree') }}:</span> {{ $participant->degree->label() }}</div>
                        <div><span class="text-white/30">{{ __('Dignity_label') }}:</span> {{ $participant->dignity }}</div>
                        <div><span class="text-white/30">{{ __('Lodge') }}:</span> {{ $participant->lodge_name }} {{ __('no.') }} {{ $participant->lodge_number }}</div>
                        <div><span class="text-white/30">{{ __('Orient') }}:</span> {{ $participant->orient }}</div>
                        <div><span class="text-white/30">Email:</span> {{ $participant->email }}</div>
                        <div><span class="text-white/30">{{ __('Phone') }}:</span> {{ $participant->phone }}</div>
                    </div>

                    <div class="mt-3 space-y-1 text-xs text-white/60">
                        @if($participant->friday_dinner_count > 0)
                            <div>{{ __('Friday Dinner') }}: {{ $participant->friday_dinner_count }} {{ __('persons') }} × {{ config('simpozion.events.friday_dinner.price') }} lei = {{ $participant->friday_dinner_count * config('simpozion.events.friday_dinner.price') }} lei</div>
                        @endif
                        @if($participant->symposium_lunch_count > 0)
                            <div>{{ __('Symposium + Saturday Lunch') }}: {{ $participant->symposium_lunch_count }} {{ __('persons') }} × {{ config('simpozion.events.symposium_lunch.price') }} lei = {{ $participant->symposium_lunch_count * config('simpozion.events.symposium_lunch.price') }} lei</div>
                        @endif
                        @if($participant->companion_lunch_count > 0)
                            <div>{{ __('Lunch (for companions)') }}: {{ $participant->companion_lunch_count }} {{ __('persons') }} × {{ config('simpozion.events.companion_lunch.price') }} lei = {{ $participant->companion_lunch_count * config('simpozion.events.companion_lunch.price') }} lei</div>
                        @endif
                        @if($participant->ritual_participation)
                            <div>{{ __('Ritual Session Participation_label') }}: {{ __('Yes') }}</div>
                        @endif
                        @if($participant->ball_count > 0)
                            <div>{{ __('Ball Participation') }}: {{ $participant->ball_count }} {{ __('persons') }} × {{ config('simpozion.events.ball.price') }} lei = {{ $participant->ball_count * config('simpozion.events.ball.price') }} lei</div>
                        @endif
                        @if($participant->observations)
                            <div class="mt-1"><span class="text-white/30">{{ __('Observations') }}:</span> {{ $participant->observations }}</div>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    {{-- Payment Section --}}
    @if($registration->total_amount > 0)
        <div class="mt-8 overflow-hidden rounded-2xl border border-amber-500/20 bg-gradient-to-b from-amber-500/10 to-transparent p-8 text-center">
            <h2 class="text-lg font-bold text-white">{{ __('Complete the payment to finalize the registration') }}</h2>
            <div class="my-4 text-4xl font-bold text-amber-400">{{ number_format($registration->total_amount, 0, ',', '.') }} <span class="text-lg font-normal text-amber-400/60">lei</span></div>

            <div class="mt-6 grid grid-cols-1 gap-4 text-left sm:grid-cols-2">
                {{-- Bank Transfer --}}
                <div class="rounded-xl border border-white/[0.06] bg-white/[0.03] p-5">
                    <h3 class="mb-3 text-sm font-semibold text-amber-400">{{ __('Bank transfer') }}</h3>
                    <div class="space-y-2 text-xs text-white/60">
                        <div x-data="{ copied: false }"
                             class="group cursor-pointer rounded-lg px-2 py-1 -mx-2 transition hover:bg-white/[0.04]"
                             x-on:click="
                                 navigator.clipboard.writeText('{{ config('simpozion.payment.iban') }}');
                                 copied = true;
                                 setTimeout(() => copied = false, 2000);
                             ">
                            <span class="text-white/30">IBAN:</span>
                            <span class="font-mono font-medium text-white/90">{{ config('simpozion.payment.iban') }}</span>
                            <span x-show="!copied" class="ml-1.5 inline-block text-white/20 transition group-hover:text-amber-400/60">
                                <svg class="inline size-3.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 17.25v3.375c0 .621-.504 1.125-1.125 1.125h-9.75a1.125 1.125 0 0 1-1.125-1.125V7.875c0-.621.504-1.125 1.125-1.125H6.75a9.06 9.06 0 0 1 1.5.124m7.5 10.376h3.375c.621 0 1.125-.504 1.125-1.125V11.25c0-4.46-3.243-8.161-7.5-8.876a9.06 9.06 0 0 0-1.5-.124H9.375c-.621 0-1.125.504-1.125 1.125v3.5m7.5 10.375H9.375a1.125 1.125 0 0 1-1.125-1.125v-9.25m12 6.625v-1.875a3.375 3.375 0 0 0-3.375-3.375h-1.5a1.125 1.125 0 0 1-1.125-1.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H9.75" /></svg>
                            </span>
                            <span x-show="copied" x-cloak
                                  x-transition:enter="transition ease-out duration-200"
                                  x-transition:enter-start="opacity-0 scale-95"
                                  x-transition:enter-end="opacity-100 scale-100"
                                  class="ml-1.5 text-[11px] font-semibold text-amber-400">
                                IBAN copiat ✓
                            </span>
                        </div>
                        <div><span class="text-white/30">{{ __('Account holder') }}:</span> <span class="font-medium text-white/90">{{ config('simpozion.payment.account_holder') }}</span></div>
                        <div><span class="text-white/30">{{ __('Bank') }}:</span> <span class="font-medium text-white/90">{{ config('simpozion.payment.bank_name') }}</span></div>
                        <div><span class="text-white/30">{{ __('Payment reference') }}:</span> <span class="font-mono font-medium text-white/90">{{ $registration->shortReference() }}</span></div>
                    </div>
                </div>

                {{-- Revolut --}}
                <div class="flex flex-col rounded-xl border border-white/[0.06] bg-white/[0.03] p-5">
                    <h3 class="mb-3 text-sm font-semibold text-amber-400">Revolut</h3>
                    <p class="mb-4 flex-1 text-xs text-white/50">{{ __("Quick transfer via Revolut to the organizers' account") }}</p>
                    <a href="{{ config('simpozion.payment.revolut_link') }}"
                       target="_blank"
                       class="inline-block rounded-xl bg-amber-500 px-6 py-2.5 text-center text-sm font-semibold text-black transition hover:bg-amber-400">
                        {{ __('Pay with Revolut') }}
                    </a>
                </div>
            </div>
        </div>
    @else
        <div class="mt-8 rounded-2xl border border-emerald-500/20 bg-emerald-500/10 p-8 text-center">
            <h2 class="text-lg font-bold text-emerald-400">{{ __('No payment required') }}</h2>
            <p class="mt-2 text-sm text-white/40">{{ __('Your registration does not include paid events.') }}</p>
        </div>
    @endif

    {{-- Recommended Accommodations --}}
    @if($accommodations->isNotEmpty())
        <div class="mt-8">
            <div class="mb-4 text-center">
                <div class="mb-2 text-xs font-semibold uppercase tracking-[0.25em] text-amber-400/80">{{ __('Recommended Accommodations') }}</div>
                <p class="text-sm text-white/40">{{ __('We recommend the following accommodations near the event venue:') }}</p>
            </div>

            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                @foreach($accommodations as $accommodation)
                    <div class="overflow-hidden rounded-2xl border border-white/[0.06] bg-white/[0.03]">
                        @if($accommodation->image)
                            <img src="{{ Storage::disk('public')->url($accommodation->image) }}"
                                 alt="{{ $accommodation->name }}"
                                 class="h-44 w-full object-cover" />
                        @endif

                        <div class="p-5">
                            <h3 class="text-sm font-semibold text-amber-400">{{ $accommodation->name }}</h3>

                            @if($accommodation->pricing)
                                <div class="mt-2">
                                    <span class="text-[11px] font-medium uppercase tracking-wider text-white/30">{{ __('Room pricing') }}</span>
                                    <p class="mt-0.5 text-xs text-white/60">{!! nl2br(e($accommodation->pricing)) !!}</p>
                                </div>
                            @endif

                            <div class="mt-3 flex flex-wrap items-center gap-2">
                                @if($accommodation->phone)
                                    <a href="tel:{{ $accommodation->phone }}"
                                       class="inline-flex items-center gap-1.5 rounded-lg border border-white/[0.08] bg-white/[0.04] px-3 py-1.5 text-xs font-medium text-white/70 transition hover:bg-white/[0.08] hover:text-white">
                                        <svg class="size-3.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 0 0 2.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 0 1-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 0 0-1.091-.852H4.5A2.25 2.25 0 0 0 2.25 4.5v2.25Z" /></svg>
                                        {{ $accommodation->phone }}
                                    </a>
                                @endif
                                @if($accommodation->website)
                                    <a href="{{ $accommodation->website }}"
                                       target="_blank"
                                       class="inline-flex items-center gap-1.5 rounded-lg border border-amber-500/20 bg-amber-500/10 px-3 py-1.5 text-xs font-medium text-amber-400 transition hover:bg-amber-500/20">
                                        <svg class="size-3.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 21a9.004 9.004 0 0 0 8.716-6.747M12 21a9.004 9.004 0 0 1-8.716-6.747M12 21c2.485 0 4.5-4.03 4.5-9S14.485 3 12 3m0 18c-2.485 0-4.5-4.03-4.5-9S9.515 3 12 3m0 0a8.997 8.997 0 0 1 7.843 4.582M12 3a8.997 8.997 0 0 0-7.843 4.582m15.686 0A11.953 11.953 0 0 1 12 10.5c-2.998 0-5.74-1.1-7.843-2.918m15.686 0A8.959 8.959 0 0 1 21 12c0 .778-.099 1.533-.284 2.253m0 0A17.919 17.919 0 0 1 12 16.5c-3.162 0-6.133-.815-8.716-2.247m0 0A9.015 9.015 0 0 1 3 12c0-1.605.42-3.113 1.157-4.418" /></svg>
                                        {{ __('Visit website') }}
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    {{-- WhatsApp Group --}}
    @if(config('simpozion.whatsapp_group_link'))
        <div class="mt-8 overflow-hidden rounded-2xl border border-emerald-500/20 bg-emerald-500/5 p-6 text-center">
            <p class="mb-4 text-sm text-white/70">
                {{ __('To stay up to date with all the details regarding the symposium organization, we invite you to join our WhatsApp group.') }}
            </p>
            <a href="{{ config('simpozion.whatsapp_group_link') }}"
               target="_blank"
               class="inline-flex items-center gap-2 rounded-xl bg-emerald-600 px-6 py-3 text-sm font-semibold text-white transition hover:bg-emerald-500">
                <svg class="size-5" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                {{ __('Join WhatsApp group') }}
            </a>
        </div>
    @endif
</x-layouts.app>
