<x-layouts.app>
    <div class="text-center">
        <div class="mb-2 text-xs font-semibold uppercase tracking-[0.25em] text-amber-400/80">Confirmare</div>
        <h1 class="text-3xl font-bold text-white">Înregistrare confirmată!</h1>
        <p class="mt-2 text-sm text-white/40">Referință: <span class="font-mono font-medium text-white/70">{{ $registration->shortReference() }}</span></p>
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
                        <div><span class="text-white/30">Grad:</span> {{ $participant->degree->label() }}</div>
                        <div><span class="text-white/30">Demnitate:</span> {{ $participant->dignity }}</div>
                        <div><span class="text-white/30">Loja:</span> {{ $participant->lodge_name }} nr. {{ $participant->lodge_number }}</div>
                        <div><span class="text-white/30">Orient:</span> {{ $participant->orient }}</div>
                        <div><span class="text-white/30">Email:</span> {{ $participant->email }}</div>
                        <div><span class="text-white/30">Telefon:</span> {{ $participant->phone }}</div>
                    </div>

                    <div class="mt-3 space-y-1 text-xs text-white/60">
                        @if($participant->friday_dinner_count > 0)
                            <div>Cină vineri: {{ $participant->friday_dinner_count }} pers × {{ config('simpozion.prices.friday_dinner') }} lei = {{ $participant->friday_dinner_count * config('simpozion.prices.friday_dinner') }} lei</div>
                        @endif
                        @if($participant->symposium_lunch_count > 0)
                            <div>Simpozion + Prânz: {{ $participant->symposium_lunch_count }} pers × {{ config('simpozion.prices.symposium_lunch') }} lei = {{ $participant->symposium_lunch_count * config('simpozion.prices.symposium_lunch') }} lei</div>
                        @endif
                        @if($participant->ritual_participation)
                            <div>Participare ținută rituală: Da</div>
                        @endif
                        @if($participant->ball_count > 0)
                            <div>Bal: {{ $participant->ball_count }} pers × {{ config('simpozion.prices.ball') }} lei = {{ $participant->ball_count * config('simpozion.prices.ball') }} lei</div>
                        @endif
                        @if($participant->observations)
                            <div class="mt-1"><span class="text-white/30">Observații:</span> {{ $participant->observations }}</div>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    {{-- Payment Section --}}
    @if($registration->total_amount > 0)
        <div class="mt-8 overflow-hidden rounded-2xl border border-amber-500/20 bg-gradient-to-b from-amber-500/10 to-transparent p-8 text-center">
            <h2 class="text-lg font-bold text-white">Efectuați plata pentru a finaliza înregistrarea</h2>
            <div class="my-4 text-4xl font-bold text-amber-400">{{ number_format($registration->total_amount, 0, ',', '.') }} <span class="text-lg font-normal text-amber-400/60">lei</span></div>

            <div class="mt-6 grid grid-cols-1 gap-4 text-left sm:grid-cols-2">
                {{-- Bank Transfer --}}
                <div class="rounded-xl border border-white/[0.06] bg-white/[0.03] p-5">
                    <h3 class="mb-3 text-sm font-semibold text-amber-400">Transfer bancar</h3>
                    <div class="space-y-2 text-xs text-white/60">
                        <div><span class="text-white/30">IBAN:</span> <span class="font-mono font-medium text-white/90">{{ config('simpozion.payment.iban') }}</span></div>
                        <div><span class="text-white/30">Titular:</span> <span class="font-medium text-white/90">{{ config('simpozion.payment.account_holder') }}</span></div>
                        <div><span class="text-white/30">Bancă:</span> <span class="font-medium text-white/90">{{ config('simpozion.payment.bank_name') }}</span></div>
                        <div><span class="text-white/30">Referință:</span> <span class="font-mono font-medium text-white/90">{{ $registration->shortReference() }}</span></div>
                    </div>
                </div>

                {{-- Revolut --}}
                <div class="flex flex-col rounded-xl border border-white/[0.06] bg-white/[0.03] p-5">
                    <h3 class="mb-3 text-sm font-semibold text-amber-400">Revolut</h3>
                    <p class="mb-4 flex-1 text-xs text-white/50">Transfer rapid prin Revolut către contul organizatorilor</p>
                    <a href="{{ config('simpozion.payment.revolut_link') }}"
                       target="_blank"
                       class="inline-block rounded-xl bg-amber-500 px-6 py-2.5 text-center text-sm font-semibold text-black transition hover:bg-amber-400">
                        Plătește cu Revolut
                    </a>
                </div>
            </div>
        </div>
    @else
        <div class="mt-8 rounded-2xl border border-emerald-500/20 bg-emerald-500/10 p-8 text-center">
            <h2 class="text-lg font-bold text-emerald-400">Nu este necesară plata</h2>
            <p class="mt-2 text-sm text-white/40">Înregistrarea dvs. nu include evenimente cu plată.</p>
        </div>
    @endif

    {{-- WhatsApp Group --}}
    @if(config('simpozion.whatsapp_group_link'))
        <div class="mt-8 overflow-hidden rounded-2xl border border-emerald-500/20 bg-emerald-500/5 p-6 text-center">
            <p class="mb-4 text-sm text-white/70">
                Pentru a fi la curent cu toate detaliile privind organizarea simpozionului, vă invităm să vă alăturați grupului nostru de WhatsApp.
            </p>
            <a href="{{ config('simpozion.whatsapp_group_link') }}"
               target="_blank"
               class="inline-flex items-center gap-2 rounded-xl bg-emerald-600 px-6 py-3 text-sm font-semibold text-white transition hover:bg-emerald-500">
                <svg class="size-5" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                Alătură-te grupului WhatsApp
            </a>
        </div>
    @endif
</x-layouts.app>
