@extends('layouts.app')

@section('content')
<style>
    /* Simple, consistent look */
    .box { background:#fff;border-radius:12px;box-shadow:0 6px 18px rgba(0,0,0,.06); }
    .box + .box { margin-top:24px; }
    .box-header { padding:14px 18px;border-bottom:1px solid #eee;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:10px; }
    .box-title { font-weight:700;color:#3b3b3b;font-size:18px; display:flex; align-items:center; gap:10px; }
    .box-body { padding:18px; }
    .muted { color:#6b7280;font-size: .92rem; }
    .grid-2,.grid-3,.grid-4{display:grid;gap:12px}
    .grid-2{grid-template-columns:repeat(2,minmax(0,1fr))}
    .grid-3{grid-template-columns:repeat(3,minmax(0,1fr))}
    .grid-4{grid-template-columns:repeat(4,minmax(0,1fr))}
    @media (max-width:992px){ .grid-3,.grid-4{grid-template-columns:repeat(2,minmax(0,1fr))} }
    @media (max-width:576px){ .grid-2,.grid-3,.grid-4{grid-template-columns:1fr} }

    .pill { background:#f3f4f6;border-radius:999px;padding:6px 10px;font-size:.82rem;color:#374151; }
    .badge { border-radius:8px;padding:6px 10px;font-size:.78rem; }
    .badge-green { background:#e8f8ef;color:#0e7a49; }
    .badge-amber { background:#fff7e6;color:#9a5b00; }
    .badge-sky { background:#eaf6ff;color:#0b6aa1; }
    .badge-maroon { background:#5c2323;color:#fff; }

    .tbl { width:100%;border-collapse:separate;border-spacing:0;border-radius:12px;overflow:hidden;border:1px solid #eee; }
    .tbl th { background:#5c2323;color:#fff;padding:10px 12px;font-weight:600; }
    .tbl td { padding:10px 12px;border-top:1px solid #f1f1f1; background:#fff; }
    .section-label { font-weight:700;margin:0 0 8px;color:#3b3b3b; }
    .kv { display:flex;flex-direction:column;background:#fafafa;border:1px solid #eee;border-radius:10px;padding:12px; }
    .kv small { color:#6b7280; }
    .money { text-align:right; white-space:nowrap; }
</style>

@php
    $fmt = fn($n) => number_format((float)($n ?? 0), 2);
@endphp

<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">

            {{-- CUSTOMER HEADER --}}
            <div class="box">
    <div class="box-header">
        <div class="box-title d-flex align-items-center gap-2">
            Customer Overview
            @if(!empty($customer->customer_code))
                <span class="pill">#{{ $customer->customer_code }}</span>
            @endif
        </div>

        <a href="{{ route('customer.index') }}"
           class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
            Back
        </a>
    </div>

    <div class="box-body">
        {{-- Row 1: core contact --}}
        <div class="grid-4">
            <div class="kv">
                <small>Name</small>
                <div>{{ $customer->customer_name ?? '-' }}</div>
            </div>
            <div class="kv">
                <small>Mobile</small>
                <div>
                    @if(!empty($customer->customer_phone))
                        <a href="tel:{{ $customer->customer_phone }}">{{ $customer->customer_phone }}</a>
                    @else
                        -
                    @endif
                </div>
            </div>
            <div class="kv">
                <small>Email</small>
                <div>
                    @if(!empty($customer->customer_email))
                        <a href="mailto:{{ $customer->customer_email }}">{{ $customer->customer_email }}</a>
                    @else
                        -
                    @endif
                </div>
            </div>
            <div class="kv">
                <small>City</small>
                <div>{{ $customer->city ?? '-' }}</div>
            </div>
        </div>

        {{-- Row 2: profile & location --}}
        <div class="grid-4" style="margin-top:12px">
            <div class="kv">
                <small>Cast</small>
                <div>{{ data_get($customer, 'cast.cast', '-') }}</div>
            </div>
            <div class="kv">
                <small>Branch</small>
                <div>{{ data_get($customer, 'branch.branch_name', '-') }}</div>
            </div>
            <div class="kv">
                <small>Address</small>
                <div>{{ $customer->address ?? '-' }}</div>
            </div>
        </div>

       <!--  {{-- Optional: branch details (show only if available) --}}
        @if(!empty($customer->branch))
            <div class="grid-4" style="margin-top:12px">
                <div class="kv">
                    <small>Branch Phone</small>
                    <div>{{ data_get($customer, 'branch.branch_phone', '-') }}</div>
                </div>
                <div class="kv">
                    <small>Branch Email</small>
                    <div>{{ data_get($customer, 'branch.branch_emailId', '-') }}</div>
                </div>
                <div class="kv">
                    <small>Branch Address</small>
                    <div>{{ data_get($customer, 'branch.branch_address', '-') }}</div>
                </div>
                <div class="kv">
                    <small>Branch IP</small>
                    <div>{{ data_get($customer, 'branch.branch_ip', '-') }}</div>
                </div>
            </div>
        @endif -->
    </div>
</div>

            {{-- ALL VISITS --}}
            @forelse($visitBlocks as $block)
                @php
                    $visit     = $block['visit'];
                    $visitDate =  $visit->created_at ?? null;

                    // ensure followups is a collection
                    $followupsRaw = $block['followups'] ?? null;
                    $followups = $followupsRaw instanceof \Illuminate\Support\Collection
                        ? $followupsRaw
                        : collect($followupsRaw ? [$followupsRaw] : []);
                @endphp

                <div class="box">
                    <div class="box-header">
                        <div class="box-title">
                            Visit
                            <span class="pill">{{ $visitDate ? \Carbon\Carbon::parse($visitDate)->format('d-m-Y') : '-' }}</span>
                            @if(!empty($visit->closereason))
                                <span class="badge badge-sky">Close Reason: {{ $visit->closereason->close_reason ?? '' }}</span>
                            @endif
                        </div>

                        {{-- >>> PER-VISIT TOTALS IN THE HEADER (not in table) <<< --}}
                        <div class="d-flex gap-2 flex-wrap">
                            <span class="badge badge-sky">Amount: {{ $fmt($block['totals']['amount']) }}</span>
                            <span class="badge badge-green">Paid: {{ $fmt($block['totals']['paid']) }}</span>
                            <span class="badge badge-amber">Due: {{ $fmt($block['totals']['due']) }}</span>
                            <span class="badge badge-maroon">Net: {{ $fmt($block['totals']['net']) }}</span>
                        </div>
                    </div>

                    <div class="box-body">

                        {{-- VIEWED PRODUCTS --}}
                        <div style="margin-bottom:18px">
                            <div class="section-label">View Product List</div>
                            <table class="tbl">
                                <thead>
                                    <tr>
                                        <th>Category</th>
                                        <th>Product</th>
                                        <th>Status</th>
                                        <th>Attended By</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @forelse($block['viewProducts'] as $vp)
                                    <tr>
                                        <td>{{ $vp->category->category_name ?? '' }}</td>
                                        <td>{{ $vp->product->product_name ?? '' }}</td>
                                        <td><span class="badge badge-green">{{ ucfirst($vp->status ?? 'view') }}</span></td>
                                        <td>{{ $vp->employee->emp_name ?? '' }}</td>
                                    </tr>
                                @empty
                                    <tr><td colspan="4" class="muted" style="text-align:center">No viewed products</td></tr>
                                @endforelse
                                </tbody>
                            </table>
                        </div>

                        {{-- PURCHASED PRODUCTS --}}
                        <div style="margin-bottom:18px">
                            <div class="section-label">Ordered Product List</div>
                            <table class="tbl">
                                <thead>
                                    <tr>
                                        <th>Category</th>
                                        <th>Product</th>
                                        <th>Karat</th>
                                        <th>Color</th>
                                        <th>Weight</th>
                                        <th>Size</th>
                                        <th>Order Given To</th>
                                        <th>Delivery Date</th>
                                        <th>Status</th>
                                        <th>Attended By</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @forelse($block['purchasedProducts'] as $pp)
                                    @php $dd = $pp->orderDetails->delivery_date ?? null; @endphp
                                    <tr>
                                        <td>{{ $pp->category->category_name ?? '' }}</td>
                                        <td>{{ $pp->product->product_name ?? '' }}</td>
                                        <td>{{ $pp->orderDetails->karat ?? '-' }}</td>
                                        <td>{{ $pp->orderDetails->color->color_name ?? '-' }}</td>
                                        <td>{{ $pp->orderDetails->weight ?? '-' }}</td>
                                        <td>{{ $pp->orderDetails->size ?? '-' }}</td>
                                        <td>{{ $pp->orderDetails->vendor->contact_person ?? '-' }}</td>
                                        <td>{{ $dd ? \Carbon\Carbon::parse($dd)->format('d-m-Y') : '-' }}</td>
                                        <td>
                                            <span class="badge {{ ($pp->status ?? '') === 'processing' ? 'badge-amber' : 'badge-sky' }}">
                                                {{ $pp->orderDetails->OrderStatus->status ?? $pp->status }}
                                            </span>
                                        </td>
                                        <td>{{ $pp->employee->emp_name ?? '' }}</td>
                                    </tr>
                                @empty
                                    <tr><td colspan="10" class="muted" style="text-align:center">No purchased products</td></tr>
                                @endforelse
                                </tbody>
                            </table>
                        </div>

                        {{-- FOLLOWUPS --}}
                        <div style="margin-bottom:18px">
                            <div class="section-label">Followup History</div>
                            <table class="tbl">
                                <thead>
                                    <tr>
                                        <th style="width:56px">#</th>
                                        <th>Visit Date</th>
                                        <th>Followup Date</th>
                                        <th>Employee</th>
                                        <th>Status</th>
                                        <th>Close Reason</th>
                                        <th>Remark</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @forelse($followups as $f)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ !empty($f->visit_date) ? \Carbon\Carbon::parse($f->visit_date)->format('d-m-Y') : '-' }}</td>
                                        <td>{{ !empty($f->next_followup_date) ? \Carbon\Carbon::parse($f->next_followup_date)->format('d-m-Y') : '-' }}</td>
                                        <td>{{ data_get($f,'employee.emp_name','-') }}</td>
                                        <td>{{ ($f->followup_status ?? 0) == 1 ? 'Close' : 'Open' }}</td>
                                        <td>{{ data_get($visit,'closereason.close_reason','-') }}</td>
                                        <td>{{ $f->remark ?? '-' }}</td>
                                    </tr>
                                @empty
                                    <tr><td colspan="7" class="text-center muted">No followups</td></tr>
                                @endforelse
                                </tbody>
                            </table>
                        </div>

                        {{-- ORDER MONEY (Per-visit) --}}
                        <!-- <div>
                            <div class="section-label">Order Summary (This Visit)</div>
                            <table class="tbl">
                                <thead>
                                    <tr>
                                        <th style="width:56px">#</th>
                                        <th>Order ID</th>
                                        <th class="money">Amount</th>
                                        <th class="money">Net Amount</th>
                                        <th class="money">Paid</th>
                                        <th class="money">Due</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @forelse($block['orderBreakdown'] as $o)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $o['order_id'] }}</td>
                                        <td class="money">{{ $fmt($o['amount'] ?? 0) }}</td>
                                        <td class="money">{{ $fmt($o['net_total'] ?? 0) }}</td>
                                        <td class="money">{{ $fmt($o['paid'] ?? 0) }}</td>
                                        <td class="money">{{ $fmt($o['due'] ?? 0) }}</td>
                                    </tr>
                                @empty
                                    <tr><td colspan="6" class="muted" style="text-align:center">No orders for this visit</td></tr>
                                @endforelse
                                </tbody>
                                {{-- Removed the footer totals row since totals are now in the header --}}
                            </table>
                        </div> -->

                    </div>
                </div>
            @empty
                <div class="box">
                    <div class="box-body"><span class="muted">No visits found for this customer.</span></div>
                </div>
            @endforelse

            {{-- GRAND TOTALS --}}
            <div class="box">
                <div class="box-header">
                    <div class="box-title">Grand Totals (All Visits)</div>
                </div>
                <div class="box-body">
                    <div class="grid-4">
                        <div class="kv"><small>Amount</small><div class="money">{{ $fmt($grand['amount'] ?? 0) }}</div></div>
                        <div class="kv"><small>Net Amount</small><div class="money">{{ $fmt($grand['net'] ?? 0) }}</div></div>
                        <div class="kv"><small>Paid</small><div class="money">{{ $fmt($grand['paid'] ?? 0) }}</div></div>
                        <div class="kv"><small>Due</small><div class="money">{{ $fmt($grand['due'] ?? 0) }}</div></div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
