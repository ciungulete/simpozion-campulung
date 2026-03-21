<h1>Confirmare Înregistrare - {{ config('simpozion.event_name') }}</h1>

<p>Referință: <strong>{{ $registration->shortReference() }}</strong></p>

<hr>

<h2>Participanți înregistrați</h2>

@foreach($registration->participants as $participant)
    <div style="margin-bottom: 20px; padding: 15px; border: 1px solid #ddd; border-radius: 8px;">
        <h3>{{ $participant->prefix->value }} {{ $participant->full_name }}</h3>
        <p>
            <strong>Grad:</strong> {{ $participant->degree->label() }}<br>
            <strong>Demnitate:</strong> {{ $participant->dignity }}<br>
            <strong>Loja:</strong> {{ $participant->lodge_name }} nr. {{ $participant->lodge_number }}<br>
            <strong>Orient:</strong> {{ $participant->orient }}<br>
            <strong>Email:</strong> {{ $participant->email }}<br>
            <strong>Telefon:</strong> {{ $participant->phone }}
        </p>

        <p><strong>Evenimente:</strong></p>
        <ul>
            @if($participant->friday_dinner_count > 0)
                <li>Cină vineri: {{ $participant->friday_dinner_count }} pers × {{ config('simpozion.prices.friday_dinner') }} lei = {{ $participant->friday_dinner_count * config('simpozion.prices.friday_dinner') }} lei</li>
            @endif
            @if($participant->symposium_lunch_count > 0)
                <li>Simpozion + Prânz: {{ $participant->symposium_lunch_count }} pers × {{ config('simpozion.prices.symposium_lunch') }} lei = {{ $participant->symposium_lunch_count * config('simpozion.prices.symposium_lunch') }} lei</li>
            @endif
            @if($participant->ritual_participation)
                <li>Participare ținută rituală: Da</li>
            @endif
            @if($participant->ball_count > 0)
                <li>Bal: {{ $participant->ball_count }} pers × {{ config('simpozion.prices.ball') }} lei = {{ $participant->ball_count * config('simpozion.prices.ball') }} lei</li>
            @endif
        </ul>

        <p><strong>Subtotal:</strong> {{ number_format($participant->calculateCost(), 0, ',', '.') }} lei</p>

        @if($participant->observations)
            <p><strong>Observații:</strong> {{ $participant->observations }}</p>
        @endif
    </div>
@endforeach

<hr>

<h2>Total: {{ number_format($registration->total_amount, 0, ',', '.') }} lei</h2>

@if($registration->total_amount > 0)
    <h3>Detalii plată</h3>

    <p><strong>Transfer bancar:</strong></p>
    <ul>
        <li>IBAN: {{ config('simpozion.payment.iban') }}</li>
        <li>Titular: {{ config('simpozion.payment.account_holder') }}</li>
        <li>Bancă: {{ config('simpozion.payment.bank_name') }}</li>
        <li>Referință: {{ $registration->shortReference() }}</li>
    </ul>

    <p><strong>Revolut:</strong> <a href="{{ config('simpozion.payment.revolut_link') }}">{{ config('simpozion.payment.revolut_link') }}</a></p>
@endif

<p>
    <a href="{{ route('payment', $registration) }}">Vizualizează pagina de confirmare</a>
</p>
