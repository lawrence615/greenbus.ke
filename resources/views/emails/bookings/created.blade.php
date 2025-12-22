@php($tour = $booking->tour)
@php($location = $booking->location)
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Confirmation - {{ $tour->title }}</title>
</head>
<body style="margin:0; padding:0; background-color:#f3f4f6; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;">

<!-- Full-width wrapper -->
<table width="100%" cellpadding="0" cellspacing="0" style="background-color:#f3f4f6;">
    <tr>
        <td align="center">

            <!-- Emerald Header -->
            <table width="100%" cellpadding="0" cellspacing="0" style="background-color:#059669;">
                <tr>
                    <td height="79" align="center" valign="middle" style="padding:0 24px;">
                        <img src="{{ asset('images/logo.png') }}"
                             alt="Greenbus Location Tours"
                             height="28"
                             style="display:block;">
                    </td>
                </tr>
            </table>

            <!-- Content Card -->
            <table width="600" cellpadding="0" cellspacing="0" style="background-color:#ffffff; margin-top:-40px; margin-bottom:22px; border-radius:8px; overflow:hidden; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);">
                <tr>
                    <td style="padding:24px;">

                        <!-- Title -->
                        <h2 style="margin:0 0 16px 0; font-size:20px; color:#111827; font-weight: 600;">
                            Booking Confirmed!
                        </h2>
                        <p style="margin:0 0 24px 0; font-size:14px; color:#6b7280;">
                            Thank you for booking with {{ config('app.name', 'Greenbus Location Tours') }}. Your tour is confirmed and we're excited to see you!
                        </p>

                        <!-- Tour Info -->
                        <table width="100%" cellpadding="0" cellspacing="0" style="margin-bottom:16px;">
                            <tr>
                                <td width="72" valign="top">
                                    @if($tour->image)
                                    <img src="{{ Storage::url($tour->image) }}" 
                                         width="64" 
                                         height="64" 
                                         style="border-radius:6px; display:block; object-fit: cover;"
                                         alt="{{ $tour->title }}">
                                    @else
                                    <div style="width:64px; height:64px; border-radius:6px; background-color:#e5e7eb; display:flex; align-items:center; justify-content:center;">
                                        <svg width="24" height="24" fill="none" stroke="#9ca3af" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                    </div>
                                    @endif
                                </td>
                                <td valign="top" style="padding-left:12px;">
                                    <strong style="color:#111827; font-size:14px; font-weight: 600;">
                                        {{ $location->name }}: {{ $tour->title }}
                                    </strong><br>
                                    @if($tour->category)
                                    <span style="color:#6b7280; font-size:13px;">
                                        {{ $tour->category->name }}
                                    </span>
                                    @endif
                                </td>
                            </tr>
                        </table>

                        <!-- Booking Details -->
                        <table width="100%" cellpadding="6" cellspacing="0" style="font-size:14px; color:#374151; margin-bottom:16px;">
                            <tr>
                                <td style="padding:4px 0; font-size:13px; color:#6b7280;">Booking reference</td>
                                <td align="right" style="padding:4px 0; color:#2563eb; font-weight: 600;">
                                    {{ $booking->reference }}
                                </td>
                            </tr>
                            <tr>
                                <td style="padding:4px 0; font-size:13px; color:#6b7280;">Date</td>
                                <td align="right" style="padding:4px 0;">
                                    {{ optional($booking->date)->format('M j, Y') }}
                                    @if($booking->time)
                                    , {{ $booking->time }}
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td style="padding:4px 0; font-size:13px; color:#6b7280;">Participants</td>
                                <td align="right" style="padding:4px 0;">
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
                                <td style="padding:4px 0; font-size:13px; color:#6b7280;">Total amount</td>
                                <td align="right" style="padding:4px 0; font-weight: 600;">
                                    {{ number_format($booking->total_amount, 0) }} {{ $booking->currency }}
                                </td>
                            </tr>
                        </table>

                        <!-- Meeting Point Info -->
                        @if($tour->meeting_point)
                        <table width="100%" cellpadding="12" cellspacing="0" style="background-color:#f9fafb; border-radius:6px; margin:16px 0; border: 1px solid #e5e7eb;">
                            <tr>
                                <td style="color:#111827; font-size:14px; line-height: 1.5;">
                                    <strong style="font-weight: 600; color: #374151;">Meeting Point:</strong><br>
                                    {{ $tour->meeting_point }}
                                </td>
                            </tr>
                        </table>
                        @endif

                        <!-- Important Info -->
                        <table width="100%" cellpadding="0" cellspacing="0" style="margin:20px 0;">
                            <tr>
                                <td>
                                    <h3 style="margin:0 0 12px 0; font-size:16px; color:#111827; font-weight: 600;">Important Information</h3>
                                    <ul style="margin:0; padding-left:16px; color:#374151; font-size:14px; line-height:1.5;">
                                        <li style="margin-bottom:8px;">Arrive 10–15 minutes before departure at the agreed meeting point</li>
                                        <li style="margin-bottom:8px;">Have this confirmation email ready on your phone</li>
                                        @if($booking->customer_phone)
                                        <li style="margin-bottom:8px;">We may contact you on {{ $booking->customer_phone }} if needed</li>
                                        @endif
                                        <li>Free cancellation up to 24 hours before departure</li>
                                    </ul>
                                </td>
                            </tr>
                        </table>

                        <!-- Primary CTA -->
                        <table width="100%" cellpadding="0" cellspacing="0" style="margin-top:20px;">
                            <tr>
                                <td align="center">
                                    <a href="{{ route('tours.show', [$location, $tour]) }}"
                                       style="background-color:#2563eb; color:#ffffff; text-decoration:none;
                                              padding:12px 24px; border-radius:6px; font-weight:600;
                                              display:inline-block; font-size:14px;">
                                        View Tour Details
                                    </a>
                                </td>
                            </tr>
                        </table>

                        <!-- Helper Text -->
                        <p style="font-size:12px; color:#6b7280; margin:16px 0; text-align:center;">
                            Need help? Contact us with your booking reference: {{ $booking->reference }}
                        </p>

                    </td>
                </tr>
            </table>

            <!-- Footer -->
            <table width="100%" cellpadding="0" cellspacing="0" style="background-color:#1f2937;">
                <tr>
                    <td align="center" style="padding:24px; color:#d1d5db; font-size:12px;">
                        <p style="margin:0 0 8px 0;">
                            <a href="{{ route('home') }}" style="color: #d1d5db; text-decoration: none;">{{ config('app.name', 'Greenbus Location Tours') }}</a>
                            · 
                            <a href="{{ route('tours.index', $location) }}" style="color: #d1d5db; text-decoration: none;">More Tours</a>
                            · 
                            <a href="{{ route('home') }}#contact" style="color: #d1d5db; text-decoration: none;">Contact us</a>
                        </p>
                        <p style="max-width:600px; line-height:1.5; margin:0 0 8px 0;">
                            This booking confirmation serves as your ticket. Please present it at the meeting point.
                        </p>
                        <p style="margin:16px 0 0 0;">
                            © {{ date('Y') }} {{ config('app.name', 'Greenbus Location Tours') }}
                        </p>
                    </td>
                </tr>
            </table>

        </td>
    </tr>
</table>

</body>
</html>