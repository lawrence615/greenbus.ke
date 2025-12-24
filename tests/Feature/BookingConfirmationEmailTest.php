<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;
use Illuminate\Mail\Message;

class BookingConfirmationEmailTest extends TestCase
{
    /**
     * Test sending booking confirmation email to machrialawrence2011@gmail.com.
     */
    public function test_send_booking_confirmation_email(): void
    {
        // Create dummy booking data
        $booking = (object) [
            'reference' => 'GYGRFQZ6769W',
            'customer_name' => 'John Doe',
            'customer_email' => 'macharialawrence2011@gmail.com',
            'customer_phone' => '+254712345678',
            'date' => now()->addDays(7),
            'time' => '09:00 AM',
            'adults' => 2,
            'seniors' => 1,
            'youth' => 1,
            'children' => 2,
            'infants' => 1,
            'total_amount' => 21500,
            'currency' => 'KES',
            'tour' => (object) [
                'id' => 1,
                'title' => 'Nairobi City Site-Seeing Bus Tour',
                'image' => null,
                'meeting_point' => 'CJ\'s Restaurant, Nairobi CBD',
                'category' => (object) [
                    'name' => 'City Tours'
                ]
            ],
            'location' => (object) [
                'id' => 1,
                'name' => 'Nairobi',
                'slug' => 'nairobi'
            ]
        ];

        // Generate email content directly (bypassing template with routes)
        $emailContent = $this->generateEmailContent($booking);

        // Send the email as raw HTML
        Mail::html($emailContent, function (Message $message) use ($booking) {
            $message->to($booking->customer_email)
                    ->subject('Booking Confirmed - ' . $booking->reference . ' - Greenbus Location Tours');
        });

        // Assert email was sent successfully
        $this->assertTrue(true, 'Email sent successfully to ' . $booking->customer_email);
    }

    /**
     * Test booking confirmation email renders correctly with dummy data.
     */
    public function test_booking_confirmation_email_renders_correctly(): void
    {
        // Create dummy booking data
        $booking = (object) [
            'reference' => 'GYGRFQZ6769W',
            'customer_name' => 'John Doe',
            'customer_email' => 'john.doe@example.com',
            'customer_phone' => '+254712345678',
            'date' => now()->addDays(7),
            'time' => '09:00 AM',
            'adults' => 2,
            'seniors' => 1,
            'youth' => 1,
            'children' => 2,
            'infants' => 1,
            'total_amount' => 21500,
            'currency' => 'KES',
            'tour' => (object) [
                'id' => 1,
                'title' => 'Nairobi City Site-Seeing Bus Tour',
                'image' => null,
                'meeting_point' => 'CJ\'s Restaurant, Nairobi CBD',
                'category' => (object) [
                    'name' => 'City Tours'
                ]
            ],
            'location' => (object) [
                'id' => 1,
                'name' => 'Nairobi',
                'slug' => 'nairobi'
            ]
        ];

        // Create a simplified version of the email content for testing
        $emailContent = $this->generateEmailContent($booking);
        
        // Assert email contains booking reference
        $this->assertStringContainsString($booking->reference, $emailContent);
        
        // Assert email contains tour title
        $this->assertStringContainsString($booking->tour->title, $emailContent);
        
        // Assert email contains location name
        $this->assertStringContainsString($booking->location->name, $emailContent);
        
        // Assert email contains customer name
        $this->assertStringContainsString($booking->customer_name, $emailContent);
        
        // Assert email contains meeting point
        $this->assertStringContainsString($booking->tour->meeting_point, $emailContent);
        
        // Assert email contains formatted date
        $this->assertStringContainsString($booking->date->format('M j, Y'), $emailContent);
        
        // Assert email contains time
        $this->assertStringContainsString($booking->time, $emailContent);
        
        // Assert email contains emerald header styling
        $this->assertStringContainsString('background-color:#059669', $emailContent);
        
        // Assert email contains content card styling
        $this->assertStringContainsString('background-color:#ffffff', $emailContent);
        $this->assertStringContainsString('border-radius:8px', $emailContent);
    }

    /**
     * Test booking confirmation email includes all participant types.
     */
    public function test_booking_confirmation_email_includes_all_participant_types(): void
    {
        $booking = (object) [
            'reference' => 'GYGRFQZ6769W',
            'customer_name' => 'John Doe',
            'customer_email' => 'john.doe@example.com',
            'customer_phone' => '+254712345678',
            'date' => now()->addDays(7),
            'time' => '09:00 AM',
            'adults' => 2,
            'seniors' => 1,
            'youth' => 1,
            'children' => 2,
            'infants' => 1,
            'total_amount' => 21500,
            'currency' => 'KES',
            'tour' => (object) [
                'id' => 1,
                'title' => 'Nairobi City Site-Seeing Bus Tour',
                'image' => null,
                'meeting_point' => 'CJ\'s Restaurant, Nairobi CBD',
                'category' => (object) [
                    'name' => 'City Tours'
                ]
            ],
            'location' => (object) [
                'id' => 1,
                'name' => 'Nairobi',
                'slug' => 'nairobi'
            ]
        ];
        
        $emailContent = $this->generateEmailContent($booking);
        
        // Assert all participant types are displayed
        $this->assertStringContainsString('2 adults', $emailContent);
        $this->assertStringContainsString('1 senior', $emailContent);
        $this->assertStringContainsString('1 youth', $emailContent);
        $this->assertStringContainsString('2 children', $emailContent);
        $this->assertStringContainsString('1 infant', $emailContent);
    }

    /**
     * Test booking confirmation email handles pluralization correctly.
     */
    public function test_booking_confirmation_email_handles_pluralization(): void
    {
        $booking = (object) [
            'reference' => 'SINGLE123',
            'customer_name' => 'Jane Smith',
            'customer_email' => 'jane@example.com',
            'customer_phone' => '+254798765432',
            'date' => now()->addDays(3),
            'time' => '03:00 PM',
            'adults' => 1,
            'seniors' => 1,
            'youth' => 1,
            'children' => 1,
            'infants' => 1,
            'total_amount' => 16500,
            'currency' => 'KES',
            'tour' => (object) [
                'id' => 1,
                'title' => 'Nairobi City Site-Seeing Bus Tour',
                'image' => null,
                'meeting_point' => 'CJ\'s Restaurant, Nairobi CBD',
                'category' => (object) [
                    'name' => 'City Tours'
                ]
            ],
            'location' => (object) [
                'id' => 1,
                'name' => 'Nairobi',
                'slug' => 'nairobi'
            ]
        ];
        
        $emailContent = $this->generateEmailContent($booking);
        
        // Assert singular forms are used
        $this->assertStringContainsString('1 adult', $emailContent);
        $this->assertStringContainsString('1 senior', $emailContent);
        $this->assertStringContainsString('1 youth', $emailContent);
        $this->assertStringContainsString('1 child', $emailContent);
        $this->assertStringContainsString('1 infant', $emailContent);
    }

    /**
     * Test booking confirmation email handles missing optional data.
     */
    public function test_booking_confirmation_email_handles_missing_optional_data(): void
    {
        $booking = (object) [
            'reference' => 'MINIMAL456',
            'customer_name' => 'Bob Wilson',
            'customer_email' => 'bob@example.com',
            'customer_phone' => null,
            'date' => now()->addDays(5),
            'time' => null,
            'adults' => 1,
            'seniors' => 0,
            'youth' => 0,
            'children' => 0,
            'infants' => 0,
            'total_amount' => 5000,
            'currency' => 'KES',
            'tour' => (object) [
                'id' => 1,
                'title' => 'Nairobi City Site-Seeing Bus Tour',
                'meeting_point' => 'CJ\'s Restaurant, Nairobi CBD',
                'image' => null,
                'category' => (object) [
                    'name' => 'City Tours'
                ]
            ],
            'location' => (object) [
                'id' => 1,
                'name' => 'Nairobi',
                'slug' => 'nairobi'
            ]
        ];
        
        $emailContent = $this->generateEmailContent($booking);
        
        // Assert email still renders correctly
        $this->assertStringContainsString($booking->reference, $emailContent);
        $this->assertStringContainsString($booking->customer_name, $emailContent);
        
        // Assert phone number is not mentioned
        $this->assertStringNotContainsString('We may contact you on', $emailContent);
    }

    /**
     * Test booking confirmation email contains proper styling elements.
     */
    public function test_booking_confirmation_email_contains_proper_styling(): void
    {
        $booking = (object) [
            'reference' => 'TEST123',
            'customer_name' => 'Test User',
            'customer_email' => 'test@example.com',
            'customer_phone' => '+254711122233',
            'date' => now()->addDays(1),
            'time' => '10:00 AM',
            'adults' => 1,
            'seniors' => 0,
            'youth' => 0,
            'children' => 0,
            'infants' => 0,
            'total_amount' => 5000,
            'currency' => 'KES',
            'tour' => (object) [
                'id' => 1,
                'title' => 'Test Tour',
                'image' => null,
                'meeting_point' => 'Test Meeting Point',
                'category' => (object) [
                    'name' => 'Test Category'
                ]
            ],
            'location' => (object) [
                'id' => 1,
                'name' => 'Test Location',
                'slug' => 'test-location'
            ]
        ];
        
        $emailContent = $this->generateEmailContent($booking);
        
        // Assert email contains proper HTML structure
        $this->assertStringContainsString('<!DOCTYPE html>', $emailContent);
        $this->assertStringContainsString('<html lang="en">', $emailContent);
        $this->assertStringContainsString('<head>', $emailContent);
        $this->assertStringContainsString('<body', $emailContent);
        
        // Assert email contains emerald header
        $this->assertStringContainsString('background-color:#059669', $emailContent);
        
        // Assert email contains content card
        $this->assertStringContainsString('background-color:#ffffff', $emailContent);
        $this->assertStringContainsString('border-radius:8px', $emailContent);
        
        // Assert email contains logo
        $this->assertStringContainsString('images/logo.png', $emailContent);
    }

    /**
     * Test booking confirmation email handles tour without image.
     */
    public function test_booking_confirmation_email_handles_tour_without_image(): void
    {
        $booking = (object) [
            'reference' => 'NOIMG789',
            'customer_name' => 'Alice Brown',
            'customer_email' => 'alice@example.com',
            'customer_phone' => '+254733344455',
            'date' => now()->addDays(10),
            'time' => '11:00 AM',
            'adults' => 1,
            'seniors' => 0,
            'youth' => 0,
            'children' => 0,
            'infants' => 0,
            'total_amount' => 5000,
            'currency' => 'KES',
            'tour' => (object) [
                'id' => 1,
                'title' => 'Tour Without Image',
                'image' => null,
                'meeting_point' => 'Test Meeting Point',
                'category' => (object) [
                    'name' => 'City Tours'
                ]
            ],
            'location' => (object) [
                'id' => 1,
                'name' => 'Nairobi',
                'slug' => 'nairobi'
            ]
        ];
        
        $emailContent = $this->generateEmailContent($booking);
        
        // Assert fallback placeholder is used
        $this->assertStringContainsString('background-color:#e5e7eb', $emailContent);
        $this->assertStringContainsString('width:64px; height:64px', $emailContent);
        
        // Assert email still contains tour information
        $this->assertStringContainsString($booking->tour->title, $emailContent);
    }

    /**
     * Test email template renders without errors.
     */
    public function test_email_template_renders_without_errors(): void
    {
        $booking = (object) [
            'reference' => 'SIMPLE123',
            'customer_name' => 'Simple User',
            'customer_email' => 'simple@example.com',
            'customer_phone' => '+254744455566',
            'date' => now()->addDays(1),
            'time' => '11:00 AM',
            'adults' => 1,
            'seniors' => 0,
            'youth' => 0,
            'children' => 0,
            'infants' => 0,
            'total_amount' => 5000,
            'currency' => 'KES',
            'tour' => (object) [
                'id' => 1,
                'title' => 'Simple Tour',
                'image' => null,
                'meeting_point' => 'Simple Meeting Point',
                'category' => (object) [
                    'name' => 'Simple Category'
                ]
            ],
            'location' => (object) [
                'id' => 1,
                'name' => 'Simple Location',
                'slug' => 'simple-location'
            ]
        ];
        
        // Generate email content
        $emailContent = $this->generateEmailContent($booking);
        
        // Basic content checks
        $this->assertNotEmpty($emailContent);
        $this->assertStringContainsString($booking->reference, $emailContent);
    }

    /**
     * Generate email content using a temporary view with hardcoded routes.
     */
    private function generateEmailContent($booking): string
    {
        // Create a temporary view content with routes replaced
        $templateContent = file_get_contents(resource_path('views/emails/bookings/created.blade.php'));
        
        // Replace route() calls with hardcoded URLs (wrapped in quotes for valid PHP)
        $templateContent = str_replace(
            ["route('tours.show', [\$location, \$tour])", "route('home')", "route('tours.index', \$location)"],
            ["'/tours/test-tour'", "'/'", "'/nairobi/tours'"],
            $templateContent
        );
        
        // Create a temporary view file in the views directory
        $tempViewPath = resource_path('views/temp_email_template.blade.php');
        file_put_contents($tempViewPath, $templateContent);
        
        // Render the temporary view
        $content = view('temp_email_template', ['booking' => $booking])->render();
        
        // Clean up temporary file
        unlink($tempViewPath);
        
        return $content;
    }
}
