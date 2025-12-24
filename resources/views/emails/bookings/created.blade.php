@php($tour = $booking->tour)
@php($location = $booking->location)
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Confirmation - {{ $tour->title }}</title>
</head>

<body style="background:#ebeef1;">

    <table align="center" border="0" cellpadding="0" cellspacing="0" role="presentation">
        <tbody>
            <tr style="background-color:#059669"><td rowspan="2" style="background-color:#059669;width:50%"></td><td style="background-color:#059669;font-size:0;min-width:496px;vertical-align:bottom"><table border="0" cellpadding="0" cellspacing="0" role="presentation" style="width:100%"><tbody><tr><td style="width:100%"><table border="0" cellpadding="0" cellspacing="0" role="presentation" style="width:100%"><tbody><tr><td style="width:100%"><img src="{{ asset('images/logo.png') }}" width="62" alt="logo" style="margin:32px 0 0 24px; display:block; border:0;" /></td></tr></tbody></table></td><td style="vertical-align:bottom"></td></tr></tbody></table></td><td rowspan="2" style="background-color:#059669;width:50%"></td></tr>
            <tr style="background-color:#059669">
                <td style="background-color:#059669;font-size:0;min-width:496px">
                    <table align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="background-color:#fff;border-radius:12px 12px 0 0;color:#1a2b49;margin:32px auto 0;padding:24px;width:496px">
                        <tbody>
                            <tr>
                                <td align="left" style="font-size:0;padding:24px 24px 0">
                                    <h2 style="font-family:g,Open Sans,Roboto,Segoe UI,Arial,sans-serif;font-size:28px;font-weight:700;line-height:normal;margin:0;margin-bottom:0">Booking Confirmed!</h2>
                                    <p style="margin:0 0 24px 0; font-size:14px; color:#6b7280;">
                                        Thank you for booking with {{ config('app.name', 'Greenbus Location Tours') }}. Your tour is confirmed and we're excited to see you!
                                    </p>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
            <tr><td style="width:50%"></td><td style="font-size:0;min-width:496px"><table align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="background-color:#fff;border-radius:0 0 12px 12px;color:#1a2b49;padding:24px;width:496px"><tbody><tr><td align="left" style="font-size:0;padding:8px 24px 24px"></td></tr></tbody></table></td><td style="width:50%"></td></tr>
        </tbody>
    </table>

    <table align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="background-color:#fff;border-radius:8px;color:#1a2b49;margin:32px auto;padding:24px;width:496px">
        <tbody>
            <tr>
                <td align="center" style="font-size:0">
                    <table style="margin-bottom:24px;margin-top:24px">
                        <tbody>
                            <tr>
                                <td style="padding:0;text-align:left;vertical-align:center">
                                    @if(isset($tour->cover_image_url) && $tour->cover_image_url)
                                    <img alt="{{ $tour->title }}" src="{{ $tour->cover_image_url }}" width="80" height="80" style="border-radius:8px; display:block; object-fit:cover;" />
                                    @elseif(isset($tour->image) && $tour->image)
                                    <img alt="{{ $tour->title }}" src="{{ Storage::url($tour->image) }}" width="80" height="80" style="border-radius:8px; display:block; object-fit:cover;" />
                                    @else
                                    <div style="width:80px; height:80px; border-radius:8px; background-color:#ebeef1;">&nbsp;</div>
                                    @endif
                                </td>
                                <td style="padding:0;text-align:left;vertical-align:center">
                                    <p style="font-family:g,Open Sans,Roboto,Segoe UI,Arial,sans-serif;font-size:1.25rem;font-weight:700;line-height:1.5rem;margin:0;margin-left:16px;margin-top:8px">{{ $location->name }}: {{ $tour->title }}</p>
                                    <p style="color:#63687a;font-family:g,Open Sans,Roboto,Segoe UI,Arial,sans-serif;font-size:1rem;font-weight:400;line-height:1.375rem;margin:0;margin-left:16px;margin-top:8px">{{ $tour->category?->name }}</p>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <table style="border-bottom:1px solid #ebeef1;margin-bottom:12px;margin-top:12px">
                        <tbody>
                            <tr>
                                <td style="padding:0;vertical-align:top">
                                    <div>
                                        <img src="https://ci3.googleusercontent.com/meips/ADKq_NbuVWvNGCdZTy-4y8cG4y3sL_S3fdDzTYhOiTunFmGmWtadb1hcr8VfxHUylTEDCj8-XJUXdA-luAeaRUGs5Vdz89YBwlwPnBVhotBtHP20KnWZYd8hKE1T=s0-d-e1-ft#https://cdn.getyourguide.com/assets/emails/icons/ticket-booking.png" alt="ticket-booking" width="24" height="24" class="CToWUd" data-bit="iit">
                                    </div>
                                </td>
                                <td style="padding:0;padding-right:16px;width:100%">
                                    <div style="display:flex;margin-left:16px;padding-bottom:12px">
                                        <div style="text-align:left;width:50%">
                                            <p style="font-family:g,Open Sans,Roboto,Segoe UI,Arial,sans-serif;font-size:1rem;font-weight:500;line-height:1.375rem;margin:0">Reference number</p>
                                        </div>
                                        <div style="text-align:right;width:50%">
                                            <span style="font-family:g,Open Sans,Roboto,Segoe UI,Arial,sans-serif;font-size:14px;font-weight:400;line-height:22px;margin:0"><strong>{{ $booking->reference }}</strong></span>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <table style="border-bottom:1px solid #ebeef1;margin-bottom:12px;margin-top:12px">
                        <tbody>
                            <tr>
                                <td style="padding:0;vertical-align:top">
                                    <div>
                                        <img src="https://ci3.googleusercontent.com/meips/ADKq_NbTDkIWOD0HE3i8glZcBw8G_t7o51bssp5oQNCmNzh9t3fsxexzBpmdUhGrkM-LV7GWAokfTppdX_nnbUKUEc8AvqJNitNqL1FxRha-QCvPW-uA=s0-d-e1-ft#https://cdn.getyourguide.com/assets/emails/icons/calendar.png" alt="calendar" width="24" height="24" class="CToWUd" data-bit="iit">
                                    </div>
                                </td>
                                <td style="padding:0;padding-right:16px;width:100%">
                                    <div style="display:flex;margin-left:16px;padding-bottom:12px">
                                        <div style="text-align:left;width:50%">
                                            <p style="font-family:g,Open Sans,Roboto,Segoe UI,Arial,sans-serif;font-size:1rem;font-weight:500;line-height:1.375rem;margin:0">Date</p>
                                        </div>
                                        <div style="text-align:right;width:50%"><span style="font-family:g,Open Sans,Roboto,Segoe UI,Arial,sans-serif;font-size:14px;font-weight:400;line-height:22px;margin:0"><strong>{{ optional($booking->date)->format('F j, Y') }}@if($booking->time) {{ $booking->time }}@endif</strong></span></div>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <table style="border-bottom:1px solid #ebeef1;margin-bottom:12px;margin-top:12px">
                        <tbody>
                            <tr>
                                <td style="padding:0;vertical-align:top">
                                    <div>
                                        <img src="https://ci3.googleusercontent.com/meips/ADKq_NZv7hSaT_kqc26iWHKuu1hWGIJFX_5ZHQ002NLMwW7HxiZuK0F1pdcCyOGdUkq7T2MSUPIFMwiC1cWR-qqIgYUc4LPclxFjx5yyLuH7sFRR=s0-d-e1-ft#https://cdn.getyourguide.com/assets/emails/icons/users.png" alt="users" width="24" height="24" class="CToWUd" data-bit="iit">
                                    </div>
                                </td>
                                <td style="padding:0;padding-right:16px;width:100%">
                                    <div style="display:flex;margin-left:16px;padding-bottom:12px">
                                        <div style="text-align:left;width:50%">
                                            <p style="font-family:g,Open Sans,Roboto,Segoe UI,Arial,sans-serif;font-size:1rem;font-weight:500;line-height:1.375rem;margin:0">Number of participants</p>
                                        </div>
                                        <div style="text-align:right;width:50%">
                                            <span style="font-family:g,Open Sans,Roboto,Segoe UI,Arial,sans-serif;font-size:14px;font-weight:400;line-height:22px;margin:0">
                                                <strong>{{ $booking->adults }} x</strong> {{ Str::plural('adult', $booking->adults) }}
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
                                            </span>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <table style="border-bottom:1px solid #ebeef1;margin-bottom:12px;margin-top:12px">
                        <tbody>
                            <tr>
                                <td style="padding:0;vertical-align:top">
                                    <div>
                                        <img src="https://ci3.googleusercontent.com/meips/ADKq_NbDiDPbpcKOBBons4H2TXKEn9kXfsXz8KaGhstpHIxV0BG__5TnnJDQJu16wOvZ6pBMP8rVlUTVh1FksWjW4616vmSM4EBQxFoIYHZu4PMVexb9=s0-d-e1-ft#https://cdn.getyourguide.com/assets/emails/icons/single-person.png" alt="single-person" width="24" height="24" class="CToWUd" data-bit="iit">
                                    </div>
                                </td>
                                <td style="padding:0;padding-right:16px;width:100%">
                                    <div style="display:flex;margin-left:16px;padding-bottom:12px">
                                        <div style="text-align:left;width:50%">
                                            <p style="font-family:g,Open Sans,Roboto,Segoe UI,Arial,sans-serif;font-size:1rem;font-weight:500;line-height:1.375rem;margin:0">Main customer</p>
                                        </div>
                                        <div style="text-align:right;width:50%">
                                            <span style="font-family:g,Open Sans,Roboto,Segoe UI,Arial,sans-serif;font-size:14px;font-weight:400;line-height:22px;margin:0">{{ $booking->customer_name }}</span>
                                            @if($booking->customer_email)
                                            <br><span style="font-family:g,Open Sans,Roboto,Segoe UI,Arial,sans-serif;font-size:14px;font-weight:400;line-height:22px;margin:0">{{ $booking->customer_email }}</span>
                                            <!-- <a href="mailto:{{ $booking->customer_email }}" style="font-family:g,Open Sans,Roboto,Segoe UI,Arial,sans-serif;font-size:14px;font-weight:400;line-height:22px;margin:0" target="_blank"><br> {{ $booking->customer_email }}</a> -->
                                            @endif
                                            @if($booking->customer_phone)
                                            <br><span style="font-family:g,Open Sans,Roboto,Segoe UI,Arial,sans-serif;font-size:14px;font-weight:400;line-height:22px;margin:0">Phone: {{ $booking->customer_phone }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <table style="border-bottom:1px solid #ebeef1;margin-bottom:12px;margin-top:12px">
                        <tbody>
                            <tr>
                                <td style="padding:0;vertical-align:top">
                                    <div>
                                        <img src="https://ci3.googleusercontent.com/meips/ADKq_NZSbvGTqPeT3qCzD8ym4YuhiQqun7Mj_cBZ9vxkjpOrjHweGXpUsQOuW-84ek-bWzsvinqcq5gLZnGYeFj3hKwsdv_0rv9jN5WLToERXfDf=s0-d-e1-ft#https://cdn.getyourguide.com/assets/emails/icons/globe.png" alt="globe" width="24" height="24" class="CToWUd" data-bit="iit">
                                    </div>
                                </td>
                                <td style="padding:0;padding-right:16px;width:100%">
                                    <div style="display:flex;margin-left:16px;padding-bottom:12px">
                                        <div style="text-align:left;width:50%">
                                            <p style="font-family:g,Open Sans,Roboto,Segoe UI,Arial,sans-serif;font-size:1rem;font-weight:500;line-height:1.375rem;margin:0">Country of origin</p>
                                        </div>
                                        <div style="text-align:right;width:50%">
                                            <span style="font-family:g,Open Sans,Roboto,Segoe UI,Arial,sans-serif;font-size:14px;font-weight:400;line-height:22px;margin:0">{{ (isset($booking->country_of_origin) && $booking->country_of_origin) ? $booking->country_of_origin : 'Not specified' }}</span>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <table style="border-bottom:1px solid #ebeef1;margin-bottom:12px;margin-top:12px">
                        <tbody>
                            <tr>
                                <td style="padding:0;vertical-align:top">
                                    <div>
                                        <img src="https://ci3.googleusercontent.com/meips/ADKq_Nbq9RcVARaTFcvt2qWqkhgs72mB1oeHHHX_5jNc097elrdf7gfMkDkjMEUXR4XgHrZNx1wfAIAB9T6bWIhbltkSx5a3C7qT9vaUiHLIuw=s0-d-e1-ft#https://cdn.getyourguide.com/assets/emails/icons/pin.png" alt="pin" width="24" height="24" class="CToWUd" data-bit="iit">
                                    </div>
                                </td>
                                <td style="padding:0;padding-right:16px;width:100%">
                                    <div style="display:flex;margin-left:16px;padding-bottom:12px">
                                        <div style="text-align:left;width:50%">
                                            <p style="font-family:g,Open Sans,Roboto,Segoe UI,Arial,sans-serif;font-size:1rem;font-weight:500;line-height:1.375rem;margin:0">Meeting point</p>
                                        </div>
                                        <div style="text-align:right;width:50%">
                                            @if($tour->meeting_point)
                                            <span style="font-family:g,Open Sans,Roboto,Segoe UI,Arial,sans-serif;font-size:14px;font-weight:400;line-height:22px;margin:0">{{ $tour->meeting_point }}</span>
                                            <a href="https://www.google.com/maps/search/?api=1&query={{ rawurlencode($tour->meeting_point) }}" style="font-family:g,Open Sans,Roboto,Segoe UI,Arial,sans-serif;font-size:14px;font-weight:400;line-height:22px;margin:0" target="_blank"><br> Open in Google Maps</a>
                                            @else
                                            <span style="font-family:g,Open Sans,Roboto,Segoe UI,Arial,sans-serif;font-size:14px;font-weight:400;line-height:22px;margin:0">To be confirmed</span>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <table style="border-bottom:1px solid #ebeef1;margin-bottom:12px;margin-top:12px">
                        <tbody>
                            <tr>
                                <td style="padding:0;vertical-align:top">
                                    <div>
                                        <img src="https://ci3.googleusercontent.com/meips/ADKq_NbDiDPbpcKOBBons4H2TXKEn9kXfsXz8KaGhstpHIxV0BG__5TnnJDQJu16wOvZ6pBMP8rVlUTVh1FksWjW4616vmSM4EBQxFoIYHZu4PMVexb9=s0-d-e1-ft#https://cdn.getyourguide.com/assets/emails/icons/currency.png" alt="currency" width="24" height="24" class="CToWUd" data-bit="iit">
                                    </div>
                                </td>
                                <td style="padding:0;padding-right:16px;width:100%">
                                    <div style="display:flex;margin-left:16px;padding-bottom:12px">
                                        <div style="text-align:left;width:50%">
                                            <p style="font-family:g,Open Sans,Roboto,Segoe UI,Arial,sans-serif;font-size:1rem;font-weight:500;line-height:1.375rem;margin:0">Total amount</p>
                                        </div>
                                        <div style="text-align:right;width:50%">
                                            <span style="font-family:g,Open Sans,Roboto,Segoe UI,Arial,sans-serif;font-size:14px;font-weight:400;line-height:22px;margin:0">{{ number_format($booking->total_amount, 0) }} {{ $booking->currency }}</span>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <a href="{{ route('tours.show', [$location, $tour]) }}" style="background:#0071eb;border-radius:22px;color:#fff;display:block;font-family:g,Open Sans,Roboto,Segoe UI,Arial,sans-serif;font-feature-settings:'calt' off;font-size:16px;font-style:normal;font-weight:500;line-height:40px;margin:0;padding:0 24px;text-align:center" target="_blank">View Tour Details</a>
                    </td>
                </tr>
            </tbody>
        </table>

        <table align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="background-color:#fff;border-radius:8px;color:#1a2b49;margin:32px auto;padding:24px;width:496px">
            <tbody>
                <tr>
                    <td align="left" style="font-size:0;padding:24px">
                        <h1 style="font-family:g,Open Sans,Roboto,Segoe UI,Arial,sans-serif;font-size:20px;font-weight:700;line-height:normal;margin:0;margin-bottom:16px">We’re here to help</h1>
                        <p style="font-family:g,Open Sans,Roboto,Segoe UI,Arial,sans-serif;font-size:16px;font-weight:400;line-height:22px;margin:.8em 0">If you have any questions, you can <a href="{{ route('home') }}#contact" style="font-family:g,Open Sans,Roboto,Segoe UI,Arial,sans-serif;font-size:16px;font-weight:400;line-height:22px;margin:0" target="_blank">contact our team</a>.</p>
                    </td>
                </tr>
            </tbody>
        </table>

        <table align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="background-color:#1a2b49;border-radius:0;color:#fff;margin-bottom:0;margin-top:32px;max-width:none;width:100%">
            <tbody>
                <tr>
                    <td style="padding:24px;text-align:center">
                        <a href="{{ route('home') }}" style="color: #d1d5db; text-decoration: none;">{{ config('app.name', 'Greenbus Location Tours') }}</a>
                        <span style="color:var(--surface-primary);font-family:g,Open Sans,Roboto,Segoe UI,Arial,sans-serif;font-size:14px;font-weight:400;line-height:22px;margin:0 8px">|</span>
                        <a href="{{ route('tours.index', $location) }}" style="color: #d1d5db; text-decoration: none;">More Tours</a>
                        <span style="color:var(--surface-primary);font-family:g,Open Sans,Roboto,Segoe UI,Arial,sans-serif;font-size:14px;font-weight:400;line-height:22px;margin:0 8px">|</span>
                        <a href="{{ route('home') }}#contact" style="color: #d1d5db; text-decoration: none;">Contact us</a>
                    </td>
                </tr>
                <tr>
                    <td colspan="2" style="padding:24px;text-align:center">
                        <span style="color:var(--surface-primary);font-family:g,Open Sans,Roboto,Segoe UI,Arial,sans-serif;font-size:14px;font-weight:400;line-height:22px;margin:0">This booking confirmation serves as your ticket. Please present it at the meeting point.</span>
                    </td>
                </tr>
                <tr>
                    <td style="padding:24px;text-align:center">
                        <span style="color:#fff;font-family:g,Open Sans,Roboto,Segoe UI,Arial,sans-serif;font-size:14px;font-weight:400;line-height:22px;margin:0">&copy; {{ date('Y') }} {{ config('app.name', 'Greenbus Location Tours') }} </span>
                    </td>
                </tr>
                <tr>
                    <td style="padding:24px;padding-bottom:48px;text-align:center">
                        <img src="{{ asset('images/logo.png') }}" width="184" alt="logo" style="display:block; border:0;" />
                    </td>
                </tr>
            </tbody>
        </table>

</body>

</html>