{{-- Cartão de item na cozinha --}}
<div class="kcard">
    <div class="kcard-head">
        <span class="kcard-mesa">Mesa {{ $item->order->table->number }}</span>
        <span class="kcard-time"><i class="ti ti-clock"></i> {{ $item->created_at->format('H:i') }}</span>
    </div>
    <div class="kcard-meta">
        Pedido #{{ str_pad($item->order_id, 3, '0', STR_PAD_LEFT) }}
        @if($item->isCustom())
            <span class="kcard-tag-custom"><i class="ti ti-adjustments"></i> Monte sua Massa</span>
        @endif
    </div>

    <div class="kcard-item">
        <span class="kcard-qty">{{ $item->quantity }}</span>
        <div style="display:inline-block;vertical-align:top;margin-left:6px;">
            <div class="kcard-iname">{{ $item->product_name }}</div>
            @if($item->isCustom())
                <div class="kcard-icustom">{{ $item->customization_label }}</div>
            @endif
            @if($item->notes)<div class="kcard-iobs">obs: {{ $item->notes }}</div>@endif
        </div>
    </div>

    @if($item->order->notes)
        <div class="kcard-iobs" style="margin-top:6px;">Obs. geral: {{ $item->order->notes }}</div>
    @endif

    @if($next && $label)
        <div class="kcard-foot">
            <form method="POST" action="{{ route('cozinha.item.status', $item) }}" style="flex:1;">
                @csrf
                <input type="hidden" name="status" value="{{ $next }}">
                <button type="submit" class="btn {{ $btnClass }} btn-sm" style="width:100%;">{{ $label }}</button>
            </form>
        </div>
    @endif
</div>
