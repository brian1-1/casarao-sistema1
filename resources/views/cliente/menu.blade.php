@extends('layouts.app')
@section('title', 'Mesa ' . $table->number)

@php
    // Totais do carrinho (itens ainda não enviados)
    $cartTotal = collect($cart)->sum(fn($i) => $i['price'] * $i['quantity']);
    // Total já enviado à cozinha (comanda aberta), descontando itens cancelados
    $sentTotal = $sentOrders->sum(fn($o) => $o->items->sum('subtotal'));
    $grandTotal = $cartTotal + $sentTotal;
@endphp

@push('styles')
<style>
    .tc-layout { display: grid; grid-template-columns: 1fr 360px; gap: 20px; align-items: start; }
    .cat-nav { display: flex; gap: 8px; flex-wrap: wrap; margin-bottom: 18px; }
    .cat-btn { background: #fff; border: 1px solid var(--border); border-radius: 22px; padding: 8px 16px; font-size: 13px; font-weight: 700; color: var(--muted); display: inline-flex; gap: 6px; align-items: center; }
    .cat-btn.active { background: var(--brand); color: #fff; border-color: var(--brand); }
    .menu-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(220px, 1fr)); gap: 16px; }
    .prod-card { display: flex; flex-direction: column; }
    .prod-img { width: 100%; height: 140px; object-fit: cover; background: #e8e0d8; }
    .prod-body { padding: 14px; display: flex; flex-direction: column; flex: 1; }
    .prod-name { font-weight: 700; font-size: 14px; }
    .prod-serves { font-size: 11px; color: var(--muted2); font-weight: 600; margin-top: 2px; display: flex; align-items: center; gap: 4px; }
    .prod-desc { font-size: 12px; color: var(--muted); margin: 4px 0 10px; flex: 1; line-height: 1.5; }
    .prod-foot { display: flex; align-items: center; justify-content: space-between; }
    .prod-price { font-weight: 800; color: var(--brand); font-size: 15px; }
    .btn-add { width: 38px; height: 38px; border-radius: 10px; background: var(--brand); color: #fff; border: none; font-size: 20px; display: flex; align-items: center; justify-content: center; }
    .btn-montar { background: var(--gold); color: #1c1c1a; border: none; border-radius: 9px; padding: 8px 14px; font-size: 12px; font-weight: 800; display: inline-flex; align-items: center; gap: 6px; }
    .cat-section { display: none; }
    .cat-section.active { display: block; }
    .cat-title { font-size: 18px; font-weight: 800; margin-bottom: 14px; }

    .comanda { position: sticky; top: 84px; background: var(--panel-bg); color: var(--panel-text); border-radius: var(--radius); padding: 18px; }
    .comanda-head { display: flex; align-items: center; justify-content: space-between; margin-bottom: 14px; }
    .comanda-title { font-size: 15px; font-weight: 800; }
    .comanda-mesa { font-size: 12px; color: var(--panel-muted); }
    .comanda-actions { display: flex; gap: 6px; margin-bottom: 12px; }
    .comanda-actions .btn { flex: 1; }
    .transfer-box { display: none; margin-bottom: 12px; }
    .transfer-box.open { display: block; }
    .comanda-list { max-height: 280px; overflow-y: auto; margin-bottom: 12px; }
    .citem { display: flex; align-items: flex-start; gap: 10px; padding: 9px 0; border-bottom: 1px solid rgba(255,255,255,0.08); }
    .citem.cancelled { opacity: .5; }
    .citem-info { flex: 1; }
    .citem-name { font-size: 13px; font-weight: 600; display: flex; flex-wrap: wrap; align-items: center; gap: 6px; }
    .citem-name.strike { text-decoration: line-through; }
    .citem-custom { font-size: 11px; color: var(--gold); margin-top: 2px; }
    .citem-obs { font-size: 11px; color: var(--panel-muted); margin-top: 2px; }
    .citem-cancel-reason { font-size: 11px; color: #e8a09a; margin-top: 2px; }
    .citem-sub { font-size: 13px; font-weight: 700; white-space: nowrap; }
    .qty-ctrl { display: flex; align-items: center; gap: 4px; }
    .qty-btn { width: 24px; height: 24px; border-radius: 6px; border: none; background: rgba(255,255,255,0.14); color: #fff; font-size: 15px; display: flex; align-items: center; justify-content: center; }
    .qty-val { min-width: 22px; text-align: center; font-weight: 700; font-size: 13px; }
    .comanda-empty { text-align: center; color: var(--panel-muted); font-size: 13px; padding: 20px 0; }
    .comanda-total { display: flex; justify-content: space-between; align-items: center; padding: 12px 0; border-top: 1px solid rgba(255,255,255,0.15); font-size: 16px; font-weight: 800; }
    .pay-methods { display: flex; gap: 8px; margin: 8px 0 12px; flex-wrap: wrap; }
    .pay-opt { flex: 1 1 calc(50% - 4px); min-width: 80px; }
    .pay-opt input { display: none; }
    .pay-opt label { display: flex; flex-direction: column; align-items: center; gap: 4px; padding: 10px 6px; border-radius: 9px; background: rgba(255,255,255,0.08); font-size: 11px; font-weight: 700; cursor: pointer; border: 2px solid transparent; }
    .pay-opt input:checked + label { border-color: var(--gold); background: rgba(212,160,23,0.18); }
    .qrcode-preview { display: none; flex-direction: column; align-items: center; gap: 8px; background: rgba(255,255,255,0.06); border-radius: 9px; padding: 14px; margin-bottom: 12px; }
    .qrcode-preview.show { display: flex; }
    .qrcode-preview canvas { border-radius: 6px; background: #fff; }
    .qrcode-caption { font-size: 11px; color: var(--panel-muted); text-align: center; }
    .sent-tag { font-size: 10px; padding: 1px 6px; border-radius: 10px; font-weight: 700; }
    .sent-tag.st-enviado { background: var(--amber); color: #1c1c1a; }
    .sent-tag.st-em_preparo { background: var(--blue); color: #fff; }
    .sent-tag.st-pronto { background: var(--green); color: #fff; }
    .sent-tag.st-cancelado { background: var(--red); color: #fff; }
    .mini-cancel { background: none; border: none; color: #e8a09a; font-size: 10px; font-weight: 700; text-decoration: underline; cursor: pointer; padding: 0; }
    @media (max-width: 880px) { .tc-layout { grid-template-columns: 1fr; } .comanda { position: static; } }

    /* ---- Modal Monte sua Massa ---- */
    .modal-overlay { display: none; position: fixed; inset: 0; background: rgba(0,0,0,.55); z-index: 100; align-items: center; justify-content: center; padding: 16px; }
    .modal-overlay.open { display: flex; }
    .modal-box { background: #fff; border-radius: var(--radius); max-width: 480px; width: 100%; max-height: 88vh; overflow-y: auto; padding: 22px; }
    .modal-head { display: flex; align-items: center; justify-content: space-between; margin-bottom: 4px; }
    .modal-title { font-size: 17px; font-weight: 800; }
    .modal-close { background: none; border: none; font-size: 20px; color: var(--muted); cursor: pointer; }
    .modal-sub { font-size: 12px; color: var(--muted); margin-bottom: 16px; }
    .opt-group-title { font-size: 12px; font-weight: 800; text-transform: uppercase; letter-spacing: .04em; color: var(--muted); margin: 14px 0 8px; }
    .opt-radio-list, .opt-check-list { display: flex; flex-direction: column; gap: 6px; }
    .opt-radio, .opt-check { display: flex; align-items: center; gap: 8px; padding: 9px 10px; border: 1px solid var(--border); border-radius: 9px; font-size: 13px; cursor: pointer; }
    .opt-radio input, .opt-check input { accent-color: var(--brand); }
    .opt-check.disabled { opacity: .4; cursor: not-allowed; }
    .ingred-counter { font-size: 11px; color: var(--muted); margin-top: 6px; }
    .ingred-counter.limit { color: var(--red); font-weight: 700; }
    .modal-foot { margin-top: 18px; }
    .extra-price { color: var(--brand); font-weight: 700; margin-left: auto; font-size: 12px; }
</style>
@endpush

@section('content')
<div class="container">
    <div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:10px;">
        <div>
            <h1 class="page-title">Mesa {{ $table->number }} · Cardápio</h1>
            <p class="page-sub">Adicione itens, ajuste a quantidade e confirme o pedido.</p>
        </div>
        <a href="{{ route('cliente.mesas') }}" class="btn btn-ghost btn-sm"><i class="ti ti-arrow-left"></i> Voltar às mesas</a>
    </div>

    @include('partials.flash')

    <div class="tc-layout">
        {{-- ----- Cardápio ----- --}}
        <div>
            <div class="cat-nav">
                @foreach($categories as $i => $cat)
                    <button type="button" class="cat-btn {{ $i === 0 ? 'active' : '' }}" data-cat="{{ $cat->slug }}" onclick="showCat('{{ $cat->slug }}', this)">
                        <i class="ti {{ $cat->icon ?? 'ti-tools-kitchen-2' }}"></i> {{ $cat->name }}
                    </button>
                @endforeach
            </div>

            @foreach($categories as $i => $cat)
                <div class="cat-section {{ $i === 0 ? 'active' : '' }}" id="cat-{{ $cat->slug }}">
                    <div class="cat-title">{{ $cat->name }}</div>
                    <div class="menu-grid">
                        @forelse($cat->availableProducts as $product)
                            <div class="card prod-card">
                                <img class="prod-img" src="{{ $product->image_url }}" alt="{{ $product->name }}"
                                     onerror="this.onerror=null;this.src='{{ asset('images/placeholder.svg') }}';">
                                <div class="prod-body">
                                    <div class="prod-name">{{ $product->name }}</div>
                                    @if($product->serves)
                                        <div class="prod-serves"><i class="ti ti-users"></i> {{ $product->serves }}</div>
                                    @endif
                                    <div class="prod-desc">{{ $product->description }}</div>
                                    <div class="prod-foot">
                                        <div class="prod-price">R$ {{ number_format($product->price, 2, ',', '.') }}</div>

                                        @if($product->is_customizable)
                                            <button type="button" class="btn-montar" onclick="openPastaModal({{ $product->id }}, '{{ $product->name }}', {{ $product->price }})">
                                                <i class="ti ti-adjustments"></i> Montar
                                            </button>
                                        @elseif($product->requires_meat_point)
                                            <button type="button" class="btn-add" aria-label="Adicionar {{ $product->name }}" onclick="openMeatModal({{ $product->id }}, '{{ $product->name }}')">+</button>
                                        @else
                                            <form method="POST" action="{{ route('cliente.item.add', $table) }}">
                                                @csrf
                                                <input type="hidden" name="product_id" value="{{ $product->id }}">
                                                <button class="btn-add" type="submit" aria-label="Adicionar {{ $product->name }}">+</button>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @empty
                            <p class="page-sub">Nenhum produto disponível nesta categoria.</p>
                        @endforelse
                    </div>
                </div>
            @endforeach
        </div>

        {{-- ----- Comanda ----- --}}
        <aside class="comanda">
            <div class="comanda-head">
                <div>
                    <div class="comanda-title">Comanda</div>
                    <div class="comanda-mesa">Mesa {{ $table->number }}</div>
                </div>
                <span class="badge badge-{{ $table->status }}">{{ $table->status_label }}</span>
            </div>

            @if($table->status === 'ocupada' && $otherTables->where('status', 'livre')->isNotEmpty())
                <div class="comanda-actions">
                    <button type="button" class="btn btn-ghost btn-sm" onclick="document.getElementById('transfer-box').classList.toggle('open')">
                        <i class="ti ti-transfer"></i> Transferir mesa
                    </button>
                </div>
                <div id="transfer-box" class="transfer-box">
                    <form method="POST" action="{{ route('cliente.transfer', $table) }}" onsubmit="return confirm('Transferir toda a comanda desta mesa?');">
                        @csrf
                        <select name="to_table_id" class="form-control" style="margin-bottom:8px;font-size:12px;" required>
                            <option value="">Mesa de destino (livre)</option>
                            @foreach($otherTables->where('status', 'livre') as $ot)
                                <option value="{{ $ot->id }}">Mesa {{ $ot->number }}</option>
                            @endforeach
                        </select>
                        <button type="submit" class="btn btn-gold btn-sm" style="width:100%;">Confirmar transferência</button>
                    </form>
                </div>
            @endif

            {{-- Itens já enviados à cozinha --}}
            @if($sentOrders->isNotEmpty())
                <div class="comanda-list">
                    @foreach($sentOrders as $order)
                        @foreach($order->items as $item)
                            <div class="citem {{ $item->isCancelled() ? 'cancelled' : '' }}">
                                <div class="qty-val">{{ $item->quantity }}x</div>
                                <div class="citem-info">
                                    <div class="citem-name {{ $item->isCancelled() ? 'strike' : '' }}">
                                        {{ $item->product_name }}
                                        @if($item->isCancelled())
                                            <span class="sent-tag st-cancelado">Cancelado</span>
                                        @else
                                            <span class="sent-tag st-{{ $item->status }}">{{ $item->status_label }}</span>
                                        @endif
                                    </div>
                                    @if($item->isCustom())
                                        <div class="citem-custom">{{ $item->customization_label }}</div>
                                    @endif
                                    @if($item->notes)<div class="citem-obs">obs: {{ $item->notes }}</div>@endif
                                    @if($item->isCancelled())
                                        <div class="citem-cancel-reason">Motivo: {{ $item->cancel_reason }}</div>
                                    @elseif($item->status !== 'pronto')
                                        <form method="POST" action="{{ route('cliente.item.cancel', [$table, $item]) }}" style="margin-top:4px;">
                                            @csrf
                                            <input type="hidden" name="reason" value="">
                                            <button type="button" class="mini-cancel" onclick="promptCancel(this)">Cancelar item</button>
                                        </form>
                                    @endif
                                </div>
                                <div class="citem-sub">R$ {{ number_format($item->subtotal, 2, ',', '.') }}</div>
                            </div>
                        @endforeach
                    @endforeach
                </div>
            @endif

            {{-- Carrinho (itens em edição) --}}
            <div class="comanda-list">
                @forelse($cart as $key => $item)
                    <div class="citem">
                        <div class="qty-ctrl">
                            <form method="POST" action="{{ route('cliente.item.qty', $table) }}">
                                @csrf
                                <input type="hidden" name="key" value="{{ $key }}">
                                <input type="hidden" name="delta" value="-1">
                                <button class="qty-btn" type="submit" aria-label="Diminuir">−</button>
                            </form>
                            <span class="qty-val">{{ $item['quantity'] }}</span>
                            <form method="POST" action="{{ route('cliente.item.qty', $table) }}">
                                @csrf
                                <input type="hidden" name="key" value="{{ $key }}">
                                <input type="hidden" name="delta" value="1">
                                <button class="qty-btn" type="submit" aria-label="Aumentar">+</button>
                            </form>
                        </div>
                        <div class="citem-info">
                            <div class="citem-name">{{ $item['name'] }}</div>
                            @if(!empty($item['customization']))
                                <div class="citem-custom">
                                    @if(!empty($item['customization']['massa']))
                                        {{ $item['customization']['massa'] }} · {{ $item['customization']['molho'] }}
                                        @if(!empty($item['customization']['ingredientes']))
                                            · + {{ implode(', ', $item['customization']['ingredientes']) }}
                                        @endif
                                    @elseif(!empty($item['customization']['ponto_label']))
                                        Ponto: {{ $item['customization']['ponto_label'] }}
                                    @endif
                                </div>
                            @endif
                            @if($item['notes'])<div class="citem-obs">{{ $item['notes'] }}</div>@endif
                            <div class="citem-obs">R$ {{ number_format($item['price'], 2, ',', '.') }} un.</div>
                        </div>
                        <div class="citem-sub">R$ {{ number_format($item['price'] * $item['quantity'], 2, ',', '.') }}</div>
                    </div>
                @empty
                    @if($sentOrders->isEmpty())
                        <div class="comanda-empty">Seu pedido<br>aparecerá aqui.</div>
                    @endif
                @endforelse
            </div>

            <div class="comanda-total">
                <span>Total</span>
                <span>R$ {{ number_format($grandTotal, 2, ',', '.') }}</span>
            </div>

            {{-- Confirmar itens do carrinho --}}
            @if(!empty($cart))
                <form method="POST" action="{{ route('cliente.confirm', $table) }}">
                    @csrf
                    <button type="submit" class="btn btn-gold" style="width:100%;margin-bottom:10px;">
                        <i class="ti ti-send"></i> Enviar pedido para a cozinha
                    </button>
                </form>
            @endif

            {{-- Pagamento / fechar conta --}}
            @if($sentTotal > 0)
                <form method="POST" action="{{ route('cliente.pay', $table) }}" onsubmit="return confirm('Confirmar pagamento e encerrar a conta da mesa?');">
                    @csrf
                    <div class="pay-methods">
                        <div class="pay-opt">
                            <input type="radio" name="method" id="m-pix" value="pix" checked onchange="onPayMethodChange()">
                            <label for="m-pix"><i class="ti ti-bolt"></i> Pix</label>
                        </div>
                        <div class="pay-opt">
                            <input type="radio" name="method" id="m-din" value="dinheiro" onchange="onPayMethodChange()">
                            <label for="m-din"><i class="ti ti-cash"></i> Dinheiro</label>
                        </div>
                        <div class="pay-opt">
                            <input type="radio" name="method" id="m-car" value="cartao" onchange="onPayMethodChange()">
                            <label for="m-car"><i class="ti ti-credit-card"></i> Cartão</label>
                        </div>
                        <div class="pay-opt">
                            <input type="radio" name="method" id="m-qr" value="qrcode" onchange="onPayMethodChange()">
                            <label for="m-qr"><i class="ti ti-scan"></i> QR Code</label>
                        </div>
                    </div>

                    <div id="qrcode-preview" class="qrcode-preview">
                        <canvas id="qrcode-canvas" width="160" height="160"></canvas>
                        <div class="qrcode-caption">Aponte a câmera e escaneie para pagar</div>
                    </div>

                    <button type="submit" class="btn btn-green" style="width:100%;">
                        <i class="ti ti-check"></i> Encerrar conta · R$ {{ number_format($sentTotal, 2, ',', '.') }}
                    </button>
                </form>
            @endif
        </aside>
    </div>
</div>

{{-- ----- Modal "Monte sua Massa" ----- --}}
<div class="modal-overlay" id="pasta-modal">
    <div class="modal-box">
        <div class="modal-head">
            <div class="modal-title" id="pasta-modal-title">Monte sua Massa</div>
            <button type="button" class="modal-close" onclick="closePastaModal()">&times;</button>
        </div>
        <p class="modal-sub">Escolha 1 massa, 1 molho e até {{ \App\Models\PastaOption::MAX_INGREDIENTES }} ingredientes.</p>

        <form method="POST" action="{{ route('cliente.item.add_custom', $table) }}" id="pasta-form">
            @csrf
            <input type="hidden" name="product_id" id="pasta-product-id">

            <div class="opt-group-title">Massa</div>
            <div class="opt-radio-list">
                @foreach($massas as $m)
                    <label class="opt-radio">
                        <input type="radio" name="massa_id" value="{{ $m->id }}" required>
                        {{ $m->name }}
                    </label>
                @endforeach
                @if($massas->isEmpty())
                    <p class="page-sub" style="margin:0;">Nenhuma opção de massa cadastrada.</p>
                @endif
            </div>

            <div class="opt-group-title">Molho</div>
            <div class="opt-radio-list">
                @foreach($molhos as $m)
                    <label class="opt-radio">
                        <input type="radio" name="molho_id" value="{{ $m->id }}" required>
                        {{ $m->name }}
                    </label>
                @endforeach
                @if($molhos->isEmpty())
                    <p class="page-sub" style="margin:0;">Nenhuma opção de molho cadastrada.</p>
                @endif
            </div>

            <div class="opt-group-title">Ingredientes (até {{ \App\Models\PastaOption::MAX_INGREDIENTES }})</div>
            <div class="opt-check-list" id="ingredientes-list">
                @foreach($ingredientes as $ing)
                    <label class="opt-check">
                        <input type="checkbox" name="ingredientes[]" value="{{ $ing->id }}" onchange="onIngredientChange()">
                        {{ $ing->name }}
                        @if($ing->extra_price > 0)
                            <span class="extra-price">+R$ {{ number_format($ing->extra_price, 2, ',', '.') }}</span>
                        @endif
                    </label>
                @endforeach
                @if($ingredientes->isEmpty())
                    <p class="page-sub" style="margin:0;">Nenhum ingrediente cadastrado.</p>
                @endif
            </div>
            <div class="ingred-counter" id="ingred-counter">0 / {{ \App\Models\PastaOption::MAX_INGREDIENTES }} ingredientes escolhidos</div>

            <div class="modal-foot">
                <button type="submit" class="btn btn-gold" style="width:100%;">
                    <i class="ti ti-check"></i> Adicionar à comanda
                </button>
            </div>
        </form>
    </div>
</div>

{{-- ----- Modal "Ponto da Carne" ----- --}}
<div class="modal-overlay" id="meat-modal">
    <div class="modal-box" style="max-width:380px;">
        <div class="modal-head">
            <div class="modal-title" id="meat-modal-title">Ponto da carne</div>
            <button type="button" class="modal-close" onclick="closeMeatModal()">&times;</button>
        </div>
        <p class="modal-sub">Como você prefere a carne?</p>

        <form method="POST" action="{{ route('cliente.item.add_meat', $table) }}" id="meat-form">
            @csrf
            <input type="hidden" name="product_id" id="meat-product-id">

            <div class="opt-radio-list">
                @foreach(\App\Models\Product::MEAT_POINTS as $value => $label)
                    <label class="opt-radio">
                        <input type="radio" name="ponto" value="{{ $value }}" required @checked($loop->first)>
                        {{ $label }}
                    </label>
                @endforeach
            </div>

            <div class="modal-foot">
                <button type="submit" class="btn btn-gold" style="width:100%;">
                    <i class="ti ti-check"></i> Adicionar à comanda
                </button>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
    // Alterna entre as categorias do cardápio
    function showCat(slug, btn) {
        document.querySelectorAll('.cat-section').forEach(s => s.classList.remove('active'));
        document.querySelectorAll('.cat-btn').forEach(b => b.classList.remove('active'));
        document.getElementById('cat-' + slug).classList.add('active');
        btn.classList.add('active');
    }

    // ---- Modal "Monte sua Massa" ----
    const MAX_INGREDIENTES = {{ \App\Models\PastaOption::MAX_INGREDIENTES }};

    function openPastaModal(productId, productName, price) {
        document.getElementById('pasta-modal-title').textContent = productName;
        document.getElementById('pasta-product-id').value = productId;
        document.getElementById('pasta-modal').classList.add('open');
        onIngredientChange();
    }

    function closePastaModal() {
        document.getElementById('pasta-modal').classList.remove('open');
    }

    // ---- Modal "Ponto da Carne" ----
    function openMeatModal(productId, productName) {
        document.getElementById('meat-modal-title').textContent = productName;
        document.getElementById('meat-product-id').value = productId;
        document.getElementById('meat-modal').classList.add('open');
    }

    function closeMeatModal() {
        document.getElementById('meat-modal').classList.remove('open');
    }

    // Validação de limite de ingredientes NO FRONTEND (a revalidação real ocorre no backend)
    function onIngredientChange() {
        const checks = document.querySelectorAll('#ingredientes-list input[type="checkbox"]');
        const checked = Array.from(checks).filter(c => c.checked);
        const counter = document.getElementById('ingred-counter');

        counter.textContent = checked.length + ' / ' + MAX_INGREDIENTES + ' ingredientes escolhidos';
        counter.classList.toggle('limit', checked.length >= MAX_INGREDIENTES);

        // Bloqueia novos ingredientes quando o limite é atingido
        checks.forEach(c => {
            if (!c.checked) {
                c.disabled = checked.length >= MAX_INGREDIENTES;
                c.closest('.opt-check').classList.toggle('disabled', c.disabled);
            }
        });
    }

    // Pede o motivo do cancelamento antes de enviar o formulário
    function promptCancel(btn) {
        const reason = prompt('Motivo do cancelamento (obrigatório):');
        if (reason === null) return; // usuário cancelou o prompt
        if (reason.trim().length < 3) {
            alert('Descreva o motivo com um pouco mais de detalhe.');
            return;
        }
        const form = btn.closest('form');
        form.querySelector('input[name="reason"]').value = reason.trim();
        form.submit();
    }

    // ---- Pagamento: mostra/esconde e gera a imagem genérica de QR Code ----
    function onPayMethodChange() {
        const selected = document.querySelector('input[name="method"]:checked');
        const preview = document.getElementById('qrcode-preview');
        if (selected && selected.value === 'qrcode') {
            preview.classList.add('show');
            drawRandomQrCode();
        } else {
            preview.classList.remove('show');
        }
    }

    // Desenha um padrão aleatório no estilo de QR Code (genérico, apenas ilustrativo).
    function drawRandomQrCode() {
        const canvas = document.getElementById('qrcode-canvas');
        if (!canvas) return;
        const ctx = canvas.getContext('2d');
        const grid = 21; // tamanho típico de um QR Code "versão 1"
        const cell = canvas.width / grid;

        ctx.fillStyle = '#fff';
        ctx.fillRect(0, 0, canvas.width, canvas.height);
        ctx.fillStyle = '#1c1c1a';

        // Módulos aleatórios (genéricos, sem dado real codificado)
        for (let y = 0; y < grid; y++) {
            for (let x = 0; x < grid; x++) {
                if (Math.random() < 0.45) {
                    ctx.fillRect(x * cell, y * cell, cell, cell);
                }
            }
        }

        // "Olhos" característicos de QR Code, nos 3 cantos, para parecer realista
        const drawEye = (ox, oy) => {
            ctx.fillStyle = '#fff';
            ctx.fillRect(ox * cell, oy * cell, cell * 7, cell * 7);
            ctx.fillStyle = '#1c1c1a';
            ctx.fillRect(ox * cell, oy * cell, cell * 7, cell * 7);
            ctx.fillStyle = '#fff';
            ctx.fillRect((ox + 1) * cell, (oy + 1) * cell, cell * 5, cell * 5);
            ctx.fillStyle = '#1c1c1a';
            ctx.fillRect((ox + 2) * cell, (oy + 2) * cell, cell * 3, cell * 3);
        };
        drawEye(0, 0);
        drawEye(grid - 7, 0);
        drawEye(0, grid - 7);
    }
</script>
@endpush
