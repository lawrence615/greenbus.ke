<?php

namespace App\Http\Controllers;

use App\Models\Tour;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ShareController extends Controller
{
    /**
     * Show the shared tour view for clients
     */
    public function showTour(string $shareToken): View
    {
        $tour = Tour::where('share_token', $shareToken)
            ->with(['location', 'category', 'images', 'itineraryItems', 'pricings'])
            ->firstOrFail();

        // Check if the share link is valid
        if (!$tour->isShareLinkValid()) {
            abort(404, 'This share link has expired or is no longer valid.');
        }

        // Mark as shared when first accessed
        if ($tour->share_status === 'ready') {
            $tour->markAsShared();
        }

        return view('share.tour', [
            'tour' => $tour,
        ]);
    }

    /**
     * Handle booking from share link
     */
    public function bookTour(string $shareToken, Request $request): View
    {
        $tour = Tour::where('share_token', $shareToken)
            ->with(['location', 'category', 'images', 'itineraryItems', 'pricings'])
            ->firstOrFail();

        // Check if the share link is valid
        if (!$tour->isShareLinkValid()) {
            abort(404, 'This share link has expired or is no longer valid.');
        }

        return view('share.book', [
            'tour' => $tour,
            'shareToken' => $shareToken,
        ]);
    }
}
