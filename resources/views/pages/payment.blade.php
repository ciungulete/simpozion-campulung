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
</x-layouts.app>
