@php($tour = $booking->tour)
@php($city = $booking->city)
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
    </style>
</head>
<body>
    <div class="card">
        <div class="row">
            <div class="col">
                <div style="display:flex;align-items:center;gap:8px;">
                    <span class="badge">GB</span>
                    <div>
                        <div style="font-size:13px;font-weight:600;">Greenbus City Tours</div>
                        @if($city)
                            <div class="muted">{{ $city->name }}</div>
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

        <div class="mt-3 row">
            <div class="col">
                <div class="label">Tour</div>
                <div class="value">{{ $tour->title ?? 'Nairobi city tour' }}</div>
                @if($city)
                    <div class="muted mt-1">{{ $city->name }}</div>
                @endif
            </div>
            <div class="col" style="text-align:right;">
                <div class="label">Date & time</div>
                <div class="value">{{ optional($booking->date)->format('D, j M Y') }}</div>
                @if($booking->time)
                    <div class="muted mt-1">Start: {{ $booking->time }}</div>
                @endif
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
                        {{ $booking->adults }} adult{{ $booking->adults == 1 ? '' : 's' }}
                        @if($booking->children)
                            · {{ $booking->children }} child{{ $booking->children == 1 ? '' : 'ren' }}
                        @endif
                        @if($booking->infants)
                            · {{ $booking->infants }} infant{{ $booking->infants == 1 ? '' : 's' }}
                        @endif
                    </td>
                </tr>
                <tr>
                    <th>Total amount</th>
                    <td style="text-align:right;">{{ number_format($booking->total_amount, 0) }} {{ $booking->currency }}</td>
                </tr>
                @if($booking->pickup_location)
                    <tr>
                        <th>Pickup</th>
                        <td style="text-align:right;">{{ $booking->pickup_location }}</td>
                    </tr>
                @endif
                @if($booking->special_requests)
                    <tr>
                        <th>Notes</th>
                        <td style="text-align:right;white-space:pre-line;">{{ $booking->special_requests }}</td>
                    </tr>
                @endif
            </table>
        </div>

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
