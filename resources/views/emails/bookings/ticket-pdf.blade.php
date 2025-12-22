@php($tour = $booking->tour)
@php($location = $booking->location)
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Ticket {{ $booking->reference }}</title>
    <style>
        body { font-family: DejaVu Sans, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif; font-size: 12px; color: #0f172a; }
        .card { border: 1px solid #e5e7eb; border-radius: 12px; padding: 16px 18px; }
        .muted { color: #6b7280; font-size: 11px; }
        .heading { font-size: 16px; font-weight: 600; margin: 0 0 4px 0; }
        .row { display: flex; justify-content: space-between; gap: 16px; }
        .col { flex: 1; }
        .label { font-size: 11px; color: #6b7280; margin-bottom: 2px; }
        .value { font-size: 13px; font-weight: 500; }
        .badge { display:inline-flex;align-items:center;justify-content:center;width:28px;height:28px;border-radius:999px;background:#059669;color:#fff;font-weight:700;font-size:12px; }
        .center { text-align: center; }
        .mt-1 { margin-top: 4px; }
        .mt-2 { margin-top: 8px; }
        .mt-3 { margin-top: 12px; }
        .mt-4 { margin-top: 16px; }
        .table { width:100%;border-collapse:collapse;margin-top:8px; }
        .table th, .table td { padding:4px 0;font-size:11px;text-align:left; }
        .table th { color:#6b7280;font-weight:500;border-bottom:1px solid #e5e7eb; }
        .table td { text-align:right; }
        .table th:first-child, .table td:first-child { text-align:left; }
        .tour-info { display: flex; gap: 12px; align-items: flex-start; }
        .tour-image { width: 64px; height: 64px; border-radius: 6px; object-fit: cover; }
        .tour-details { flex: 1; }
        .message-box { background-color: #f9fafb; border: 1px solid #e5e7eb; border-radius: 6px; padding: 12px; margin: 16px 0; }
    </style>
</head>
<body>
    <div class="card">
        <div class="row">
            <div class="col">
                <div style="display:flex;align-items:flex-start;gap:8px;">
                    <img src="{{ public_path('images/logo.png') }}" alt="Greenbus Location Tours" style="height:28px;width:auto;display:block;">
                    <div>
                        <div style="font-size:13px;font-weight:600;">Greenbus Location Tours</div>
                        @if($location)
                            <div class="muted">{{ $location->name }}</div>
                        @endif
                    </div>
                </div>
            </div>
            <div class="col" style="text-align:right;">
                <div class="muted">Booking reference</div>
                <div class="value">{{ $booking->reference }}</div>
            </div>
        </div>

        <div class="mt-3">
            <p class="heading">Tour ticket</p>
            <p class="muted">Please show this ticket or your confirmation email to the guide when boarding the bus.</p>
        </div>

        <div class="mt-3">
            <div class="tour-info">
                <div>
                    @if($tour->image)
                    <img src="{{ Storage::url($tour->image) }}" class="tour-image" alt="{{ $tour->title }}">
                    @else
                    <div style="width:64px; height:64px; border-radius:6px; background-color:#e5e7eb; display:flex; align-items:center; justify-content:center;">
                        <svg width="24" height="24" fill="none" stroke="#9ca3af" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    @endif
                </div>
                <div class="tour-details">
                    <div style="font-weight: 600; font-size: 14px; color: #111827;">
                        {{ $location->name }}: {{ $tour->title }}
                    </div>
                    @if($tour->category)
                    <div class="muted" style="margin-top: 2px;">{{ $tour->category->name }}</div>
                    @endif
                </div>
            </div>
        </div>

        <div class="mt-3">
            <table class="table">
                <tr>
                    <th>Lead guest</th>
                    <td style="text-align:right;">{{ $booking->customer_name }}</td>
                </tr>
                <tr>
                    <th>Guests</th>
                    <td style="text-align:right;">
                        {{ $booking->adults }} {{ Str::plural('adult', $booking->adults) }}
                        @if($booking->seniors)
                            · {{ $booking->seniors }} {{ Str::plural('senior', $booking->seniors) }}
                        @endif
                        @if($booking->youth)
                            · {{ $booking->youth }} {{ Str::plural('youth', $booking->youth) }}
                        @endif
                        @if($booking->children)
                            · {{ $booking->children }} {{ Str::plural('child', $booking->children) }}
                        @endif
                        @if($booking->infants)
                            · {{ $booking->infants }} {{ Str::plural('infant', $booking->infants) }}
                        @endif
                    </td>
                </tr>
                <tr>
                    <th>Date</th>
                    <td style="text-align:right;">{{ optional($booking->date)->format('D, j M Y') }}
                        @if($booking->time)
                            , {{ $booking->time }}
                        @endif
                    </td>
                </tr>
                <tr>
                    <th>Total amount</th>
                    <td style="text-align:right; font-weight: 600;">{{ number_format($booking->total_amount, 0) }} {{ $booking->currency }}</td>
                </tr>
            </table>
        </div>

        @if($tour->meeting_point)
        <div class="message-box">
            <div style="font-weight: 600; color: #374151; margin-bottom: 4px;">Meeting Point:</div>
            <div style="font-size: 14px; line-height: 1.5;">{{ $tour->meeting_point }}</div>
        </div>
        @endif

        <div class="mt-4">
            <div class="label">On the day of your tour</div>
            <ul class="muted" style="margin:4px 0 0 16px;padding:0;">
                <li>Arrive 10–15 minutes before departure at the agreed meeting point.</li>
                <li>Have this ticket or the email ready on your phone.</li>
                <li>We may contact you on {{ $booking->customer_phone ?: 'the phone number you provided' }} if needed.</li>
            </ul>
        </div>

        <div class="mt-4 center muted">
            Free cancellation up to 24 hours before departure. To change or cancel, contact us quoting your reference {{ $booking->reference }}.
        </div>
    </div>
</body>
</html>
