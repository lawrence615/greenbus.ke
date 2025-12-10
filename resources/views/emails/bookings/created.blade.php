@php($tour = $booking->tour)
@php($city = $booking->city)
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Your Greenbus booking – {{ $booking->reference }}</title>
</head>
<body style="margin:0;padding:0;background-color:#f8fafc;font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',sans-serif;color:#0f172a;">
<table width="100%" cellpadding="0" cellspacing="0" role="presentation" style="background-color:#f8fafc;padding:24px 0;">
    <tr>
        <td align="center">
            <table width="100%" cellpadding="0" cellspacing="0" role="presentation" style="max-width:640px;background-color:#ffffff;border-radius:16px;border:1px solid #e2e8f0;overflow:hidden;">
                <tr>
                    <td style="padding:24px 24px 16px 24px;border-bottom:1px solid #e2e8f0;">
                        <table width="100%" cellpadding="0" cellspacing="0" role="presentation">
                            <tr>
                                <td style="vertical-align:middle;">
                                    <div style="display:inline-flex;align-items:center;gap:8px;">
                                        <img src="{{ asset('images/logo.png') }}" alt="Greenbus City Tours" style="width:100px;height:auto;display:block;">
                                        <div>
                                            <div style="font-weight:600;font-size:15px;">Greenbus City Tours</div>
                                            @if($city)
                                                <div style="font-size:11px;color:#047857;">{{ $city->name }}</div>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td style="text-align:right;vertical-align:middle;">
                                    <div style="font-size:11px;color:#6b7280;">Booking reference</div>
                                    <div style="font-weight:600;font-size:14px;">{{ $booking->reference }}</div>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>

                <tr>
                    <td style="padding:24px 24px 8px 24px;">
                        <h1 style="margin:0 0 8px 0;font-size:18px;font-weight:600;">Your booking is confirmed</h1>
                        <p style="margin:0 0 12px 0;font-size:14px;color:#4b5563;">Thank you for booking with Greenbus. Please keep this email and the attached ticket – you can show either on your phone on the day of your tour.</p>
                    </td>
                </tr>

                <tr>
                    <td style="padding:0 24px 16px 24px;">
                        <div style="border-radius:12px;border:1px solid #e2e8f0;padding:16px;">
                            <table width="100%" cellpadding="0" cellspacing="0" role="presentation">
                                <tr>
                                    <td style="vertical-align:top;padding-right:12px;">
                                        <div style="font-size:12px;color:#6b7280;margin-bottom:4px;">Tour</div>
                                        <div style="font-size:14px;font-weight:600;">{{ $tour->title ?? 'Nairobi city tour' }}</div>
                                        @if($city)
                                            <div style="font-size:12px;color:#6b7280;margin-top:2px;">{{ $city->name }}</div>
                                        @endif
                                    </td>
                                    <td style="vertical-align:top;padding-left:12px;text-align:right;">
                                        <div style="font-size:12px;color:#6b7280;margin-bottom:4px;">Date</div>
                                        <div style="font-size:14px;font-weight:500;">{{ optional($booking->date)->format('D, j M Y') }}</div>
                                        @if($booking->time)
                                            <div style="font-size:12px;color:#6b7280;margin-top:2px;">Start time: {{ $booking->time }}</div>
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </td>
                </tr>

                <tr>
                    <td style="padding:0 24px 16px 24px;">
                        <table width="100%" cellpadding="0" cellspacing="0" role="presentation" style="font-size:13px;color:#4b5563;">
                            <tr>
                                <td style="padding:8px 0;font-size:12px;color:#6b7280;">Lead guest</td>
                                <td style="padding:8px 0;text-align:right;font-weight:500;">{{ $booking->customer_name }}</td>
                            </tr>
                            <tr>
                                <td style="padding:4px 0;font-size:12px;color:#6b7280;">Guests</td>
                                <td style="padding:4px 0;text-align:right;">
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
                                <td style="padding:4px 0;font-size:12px;color:#6b7280;">Total amount</td>
                                <td style="padding:4px 0;text-align:right;font-weight:600;">{{ number_format($booking->total_amount, 0) }} {{ $booking->currency }}</td>
                            </tr>
                            @if($booking->pickup_location)
                                <tr>
                                    <td style="padding:4px 0;font-size:12px;color:#6b7280;">Pickup</td>
                                    <td style="padding:4px 0;text-align:right;">{{ $booking->pickup_location }}</td>
                                </tr>
                            @endif
                            @if($booking->special_requests)
                                <tr>
                                    <td style="padding:4px 0;font-size:12px;color:#6b7280;vertical-align:top;">Notes</td>
                                    <td style="padding:4px 0;text-align:right;white-space:pre-line;">{{ $booking->special_requests }}</td>
                                </tr>
                            @endif
                        </table>
                    </td>
                </tr>

                <tr>
                    <td style="padding:0 24px 20px 24px;">
                        <div style="border-radius:12px;background-color:#ecfdf3;padding:14px 16px;font-size:12px;color:#166534;">
                            <strong style="display:block;margin-bottom:4px;">On the day of your tour</strong>
                            <ul style="margin:0;padding-left:16px;">
                                <li>Arrive at the meeting point 10–15 minutes before departure.</li>
                                <li>Show this email or the attached PDF ticket to your guide.</li>
                                <li>Have your phone reachable on {{ $booking->customer_phone ?: 'the number you provided' }} in case we need to contact you.</li>
                            </ul>
                        </div>
                    </td>
                </tr>

                <tr>
                    <td style="padding:0 24px 20px 24px;font-size:11px;color:#6b7280;">
                        If you need to change or cancel your booking, reply to this email at least 24 hours before your tour time.
                    </td>
                </tr>

                <tr>
                    <td style="padding:12px 24px 20px 24px;border-top:1px solid #e2e8f0;font-size:11px;color:#9ca3af;text-align:center;">
                        &copy; {{ date('Y') }} Greenbus City Tours. Licensed local operator in Nairobi.
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
</body>
</html>
