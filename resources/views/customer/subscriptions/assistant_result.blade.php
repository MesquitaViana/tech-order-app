@extends('customer.layout')

@section('content')
<div class="page">

    <div class="card" style="margin-bottom:20px;">
        <h2 style="margin:0 0 6px; font-size:20px; font-weight:700;">
            Suas recomendações de Box 🎁
        </h2>
        <p style="color:#555;">
            Baseado nas suas respostas e no seu histórico, selecionamos as melhores opções para você.
        </p>
    </div>

    @foreach ($recommendations as $box)
        <div class="card" style="margin-bottom:16px;">
            <h3 style="margin:0; font-size:18px; font-weight:700;">
                {{ $box['name'] }}
            </h3>
            <p style="margin:6px 0; color:#555;">
                {{ $box['description'] }}
            </p>

            <p style="margin:6px 0; font-weight:600;">
                💰 R$ {{ number_format($box['monthly_price'], 2, ',', '.') }}/mês
            </p>

            <p style="margin:6px 0; color:#333;">
                🔍 <strong>Destaque:</strong> {{ $box['highlight'] }}
            </p>

            <button style="margin-top:10px; background:#16a34a; color:white; padding:10px 16px; border-radius:8px; border:none; font-weight:600; cursor:pointer;">
                Quero assinar esta Box
            </button>
        </div>
    @endforeach

    <a href="{{ route('customer.subscriptions.assistant') }}"
       style="display:block; margin-top:20px; text-align:center; color:#111827; font-weight:600;">
        Voltar e refazer questionário
    </a>


</div>
@endsection
