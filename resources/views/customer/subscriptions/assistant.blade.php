@extends('customer.layout')

@section('content')
<div class="page">

    {{-- CARD PRINCIPAL --}}
    <div class="card" style="margin-bottom: 16px;">
        <h1 style="font-size:20px; font-weight:700; margin:0 0 8px;">
            Assistente de Assinaturas
        </h1>

        <p style="font-size:14px; color:#6b7280; margin:0 0 16px;">
            Conte pra gente como você usa seus dispositivos hoje, quais marcas já testou,
            o que você gostou ou não, e quanto pode investir por mês. Vamos usar isso
            junto com seu histórico de pedidos para montar uma Box sob medida. 🧠
        </p>

        {{-- FORMULÁRIO --}}
        <form action="{{ route('customer.subscriptions.assistant.process') }}" method="POST">
            @csrf

            {{-- Texto livre --}}
            <div style="margin-bottom:16px;">
                <label for="preferences_text" style="display:block; font-size:14px; font-weight:600; margin-bottom:6px;">
                    Fale sobre o que você procura
                </label>

                <textarea
                    id="preferences_text"
                    name="preferences_text"
                    rows="6"
                    style="width:100%; padding:10px; border-radius:10px; border:1px solid #e5e7eb; font-size:14px;"
                    placeholder="Ex: Uso pod todo dia, gosto de gelado forte e sabores frutados, já usei Life Pod e Elf Bar. Posso gastar até R$ 220 por mês..."
                >{{ old('preferences_text') }}</textarea>

                @error('preferences_text')
                    <div style="color:#b91c1c; font-size:13px; margin-top:4px;">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            {{-- CAMPOS OPCIONAIS --}}
            <div style="display:flex; gap:12px; flex-wrap:wrap; margin-bottom:16px;">

                {{-- Orçamento --}}
                <div style="flex:1; min-width:180px;">
                    <label for="budget" style="display:block; font-size:14px; font-weight:600; margin-bottom:6px;">
                        Orçamento mensal aproximado (R$)
                    </label>
                    <input
                        type="number"
                        id="budget"
                        name="budget"
                        min="50"
                        step="10"
                        value="{{ old('budget') }}"
                        style="width:100%; padding:8px; border-radius:8px; border:1px solid #e5e7eb; font-size:14px;"
                        placeholder="Ex: 200"
                    >
                </div>

                {{-- Principal Pod usado --}}
                <div style="flex:1; min-width:180px;">
                    <label for="main_device" style="display:block; font-size:14px; font-weight:600; margin-bottom:6px;">
                        Dispositivo / produto que você mais usa hoje
                    </label>
                    <input
                        type="text"
                        id="main_device"
                        name="main_device"
                        value="{{ old('main_device') }}"
                        style="width:100%; padding:8px; border-radius:8px; border:1px solid #e5e7eb; font-size:14px;"
                        placeholder="Ex: Life Pod The One, Elf Bar TE30K..."
                    >
                </div>
            </div>

            {{-- Botão --}}
            <button type="submit"
                style="padding:10px 18px; border-radius:999px; border:none; background:#111827; color:#f9fafb; font-size:14px; font-weight:600; cursor:pointer;">
                Ver sugestões de Box
            </button>
        </form>

        {{-- ⚠️ ERRO DA IA (se houver) --}}
        @if(!empty($aiError))
            <div style="margin-top:16px; padding:12px; border-radius:8px; background:#fef2f2; color:#b91c1c; font-size:14px;">
                {{ $aiError }}
            </div>
        @endif

        {{-- ✅ RESULTADO DA IA (se houver) --}}
        @if(!empty($aiResult))
            <div style="margin-top:16px; padding:16px; border-radius:10px; background:#f9fafb; border:1px solid #e5e7eb;">
                <h2 style="margin:0 0 8px; font-size:16px; font-weight:700;">
                    Recomendações para sua assinatura
                </h2>

                <pre style="margin:0; white-space:pre-wrap; font-size:14px; color:#111827; font-family:system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;">
{{ $aiResult }}
                </pre>
            </div>
        @endif

    </div>
</div>
@endsection
