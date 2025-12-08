@extends('customer.layout')

@section('content')
<div class="page">
    <div class="card" style="margin-bottom: 16px;">
        <h1 style="font-size:20px; font-weight:700; margin:0 0 8px;">
            Assistente de Assinaturas
        </h1>
        <p style="font-size:14px; color:#6b7280; margin:0 0 16px;">
            Conte pra gente como você usa seus dispositivos hoje, quais marcas já testou,
            o que você gostou ou não, e quanto pode investir por mês. Vamos usar isso
            junto com seu histórico de pedidos para montar uma Box sob medida. 🧠
        </p>

        <form action="{{ route('customer.subscriptions.assistant.process') }}" method="POST">
            @csrf

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

            {{-- OPCIONAL: campos estruturados extras --}}
            <div style="display:flex; gap:12px; flex-wrap:wrap; margin-bottom:16px;">
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

            <button type="submit"
                style="padding:10px 18px; border-radius:999px; border:none; background:#111827; color:#f9fafb; font-size:14px; font-weight:600; cursor:pointer;">
                Ver sugestões de Box
            </button>
        </form>
    </div>
</div>
@endsection