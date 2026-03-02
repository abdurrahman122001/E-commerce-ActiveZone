@extends('vendors.layouts.app')

@section('panel_content')

@php
    $totalReferred  = $referrals->total();
    $approvedCount  = $referrals->getCollection()->where('status', 'approved')->count();
    $pendingCount   = $referrals->getCollection()->where('status', 'pending')->count();
@endphp

<style>
    /* ── Hero referral card ── */
    .referral-hero {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 16px;
        color: #fff;
        padding: 32px 28px;
        position: relative;
        overflow: hidden;
        margin-bottom: 24px;
    }
    .referral-hero::before {
        content: '';
        position: absolute;
        top: -40px; right: -40px;
        width: 200px; height: 200px;
        background: rgba(255,255,255,.07);
        border-radius: 50%;
    }
    .referral-hero::after {
        content: '';
        position: absolute;
        bottom: -60px; right: 60px;
        width: 150px; height: 150px;
        background: rgba(255,255,255,.05);
        border-radius: 50%;
    }
    .referral-hero .label {
        font-size: 11px;
        font-weight: 600;
        letter-spacing: 1.5px;
        text-transform: uppercase;
        opacity: .75;
        margin-bottom: 6px;
    }
    .referral-code-box {
        display: inline-flex;
        align-items: center;
        background: rgba(255,255,255,.18);
        border: 1.5px solid rgba(255,255,255,.35);
        border-radius: 10px;
        padding: 10px 20px;
        font-size: 26px;
        font-weight: 800;
        letter-spacing: 4px;
        margin: 6px 0 18px;
        cursor: pointer;
        transition: background .2s;
    }
    .referral-code-box:hover { background: rgba(255,255,255,.26); }
    .referral-code-box i { font-size: 18px; margin-left: 12px; opacity: .8; }

    .btn-copy-hero {
        background: rgba(255,255,255,.22);
        border: 1.5px solid rgba(255,255,255,.5);
        color: #fff;
        border-radius: 8px;
        font-weight: 600;
        font-size: 13px;
        padding: 8px 20px;
        transition: all .2s;
        cursor: pointer;
    }
    .btn-copy-hero:hover {
        background: #fff;
        color: #764ba2;
    }

    /* ── Stat mini-cards ── */
    .stat-strip { display: flex; gap: 16px; flex-wrap: wrap; margin-bottom: 24px; }
    .stat-card {
        flex: 1;
        min-width: 150px;
        background: #fff;
        border: 1px solid #f1f1f4;
        border-radius: 12px;
        padding: 18px 22px;
        display: flex;
        align-items: center;
        gap: 14px;
        box-shadow: 0 2px 10px rgba(0,0,0,.04);
    }
    .stat-icon {
        width: 46px; height: 46px;
        border-radius: 12px;
        display: flex; align-items: center; justify-content: center;
        font-size: 20px;
        flex-shrink: 0;
    }
    .stat-icon.blue  { background: #eef2ff; color: #667eea; }
    .stat-icon.green { background: #ecfdf5; color: #10b981; }
    .stat-icon.amber { background: #fffbeb; color: #f59e0b; }
    .stat-label { font-size: 11px; color: #9da3ae; font-weight: 600; text-transform: uppercase; letter-spacing: .8px; }
    .stat-value { font-size: 26px; font-weight: 800; color: #232734; line-height: 1.1; }

    /* ── Table ── */
    .ref-table thead th {
        background: #f8f8fc;
        font-size: 11px;
        text-transform: uppercase;
        letter-spacing: .8px;
        color: #9da3ae;
        font-weight: 700;
        border-bottom: 1px solid #f1f1f4;
        padding: 14px 16px;
    }
    .ref-table tbody td {
        padding: 14px 16px;
        vertical-align: middle;
        border-bottom: 1px solid #f7f7fa;
        font-size: 13px;
        color: #232734;
    }
    .ref-table tbody tr:last-child td { border-bottom: none; }
    .ref-table tbody tr:hover td { background: #fafbff; }

    .vendor-avatar {
        width: 38px; height: 38px;
        border-radius: 50%;
        object-fit: cover;
        border: 2px solid #eef0f7;
    }
    .vendor-name { font-weight: 600; font-size: 13px; }
    .vendor-email { font-size: 11px; color: #9da3ae; }

    .badge-approved { background: #ecfdf5; color: #059669; font-weight: 600; border-radius: 20px; padding: 4px 12px; font-size: 11px; }
    .badge-pending  { background: #fffbeb; color: #d97706; font-weight: 600; border-radius: 20px; padding: 4px 12px; font-size: 11px; }
    .badge-other    { background: #fef2f2; color: #dc2626; font-weight: 600; border-radius: 20px; padding: 4px 12px; font-size: 11px; }

    .empty-state {
        text-align: center;
        padding: 60px 20px;
        color: #c0c5d0;
    }
    .empty-state i { font-size: 56px; display: block; margin-bottom: 12px; }
    .empty-state p { font-size: 14px; margin: 0; }
</style>

{{-- ── Hero card ── --}}
<div class="referral-hero">
    <div class="d-flex flex-wrap justify-content-between align-items-center" style="position:relative;z-index:1;">
        <div>
            <div class="label">{{ translate('Your Referral Code') }}</div>
            <div class="referral-code-box" id="ref-code-wrap" onclick="copyCode()" title="{{ translate('Click to copy') }}">
                <span id="referral-code-text">{{ $vendor->referral_code }}</span>
                <i class="las la-copy"></i>
            </div>
            <div style="font-size:13px;opacity:.8;">
                <i class="las la-info-circle mr-1"></i>
                {{ translate('Share this code. Every vendor who registers with it will appear below.') }}
            </div>
        </div>
        <div class="mt-3 mt-md-0 text-right" style="flex-shrink:0;">
            <button class="btn-copy-hero" onclick="copyCode()">
                <i class="las la-copy mr-1"></i> {{ translate('Copy Code') }}
            </button>
        </div>
    </div>
</div>

{{-- ── Stat strip ── --}}
<div class="stat-strip">
    <div class="stat-card">
        <div class="stat-icon blue"><i class="las la-users"></i></div>
        <div>
            <div class="stat-label">{{ translate('Total Referred') }}</div>
            <div class="stat-value">{{ $totalReferred }}</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon green"><i class="las la-check-circle"></i></div>
        <div>
            <div class="stat-label">{{ translate('Approved') }}</div>
            <div class="stat-value">{{ \App\Models\Vendor::where('referred_by_id', $vendor->id)->where('status','approved')->count() }}</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon amber"><i class="las la-clock"></i></div>
        <div>
            <div class="stat-label">{{ translate('Pending') }}</div>
            <div class="stat-value">{{ \App\Models\Vendor::where('referred_by_id', $vendor->id)->where('status','pending')->count() }}</div>
        </div>
    </div>
</div>

{{-- ── Referrals table card ── --}}
<div class="card" style="border-radius:14px;overflow:hidden;border:1px solid #f1f1f4;box-shadow:0 2px 14px rgba(0,0,0,.05);">
    <div class="card-header d-flex align-items-center justify-content-between" style="background:#fff;border-bottom:1px solid #f1f1f4;padding:18px 20px;">
        <h6 class="mb-0 fw-700" style="font-size:14px;">
            <i class="las la-user-friends mr-2 text-primary"></i>
            {{ translate('Referred Vendors') }}
        </h6>
        <span class="badge" style="background:#eef2ff;color:#667eea;font-size:12px;padding:5px 14px;border-radius:20px;font-weight:700;">
            {{ $totalReferred }} {{ translate('Total') }}
        </span>
    </div>

    <div class="table-responsive">
        <table class="table ref-table mb-0">
            <thead>
                <tr>
                    <th style="width:40px;">#</th>
                    <th>{{ translate('Vendor') }}</th>
                    <th>{{ translate('Phone') }}</th>
                    <th>{{ translate('Shop Name') }}</th>
                    <th>{{ translate('Status') }}</th>
                    <th>{{ translate('Joined') }}</th>
                </tr>
            </thead>
            <tbody>
                @forelse($referrals as $i => $referral)
                <tr>
                    <td class="text-muted fw-600">{{ $referrals->firstItem() + $i }}</td>
                    <td>
                        <div class="d-flex align-items-center" style="gap:10px;">
                            <img class="vendor-avatar"
                                 src="{{ uploaded_asset($referral->user->avatar_original ?? '') }}"
                                 onerror="this.onerror=null;this.src='{{ static_asset('assets/img/avatar-place.png') }}';"
                                 alt="">
                            <div>
                                <div class="vendor-name">{{ $referral->user->name ?? 'N/A' }}</div>
                                <div class="vendor-email">{{ $referral->user->email ?? '' }}</div>
                            </div>
                        </div>
                    </td>
                    <td>{{ $referral->user->phone ?? '-' }}</td>
                    <td>
                        <span class="fw-600">{{ $referral->shop_name ?: '-' }}</span>
                    </td>
                    <td>
                        @if($referral->status == 'approved')
                            <span class="badge-approved">✓ {{ translate('Approved') }}</span>
                        @elseif($referral->status == 'pending')
                            <span class="badge-pending">⏳ {{ translate('Pending') }}</span>
                        @else
                            <span class="badge-other">{{ ucfirst($referral->status) }}</span>
                        @endif
                    </td>
                    <td class="text-muted">
                        {{ $referral->created_at ? $referral->created_at->format('d M Y') : '-' }}
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6">
                        <div class="empty-state">
                            <i class="las la-user-plus"></i>
                            <p>{{ translate('No vendors have used your referral code yet.') }}</p>
                            <small style="color:#c0c5d0;">{{ translate('Share your code and start earning referral benefits!') }}</small>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($referrals->hasPages())
    <div class="card-footer bg-white border-top" style="padding:12px 20px;">
        {{ $referrals->links() }}
    </div>
    @endif
</div>

@endsection

@section('script')
<script>
    function copyCode() {
        var code = document.getElementById('referral-code-text').innerText.trim();
        if (navigator.clipboard) {
            navigator.clipboard.writeText(code).then(showCopied);
        } else {
            var el = document.createElement('textarea');
            el.value = code;
            document.body.appendChild(el);
            el.select();
            document.execCommand('copy');
            document.body.removeChild(el);
            showCopied();
        }
    }
    function showCopied() {
        AIZ.plugins.notify('success', '{{ translate("Referral code copied to clipboard!") }}');
    }
</script>
@endsection
