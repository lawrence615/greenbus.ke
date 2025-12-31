<?php

namespace App\Http\Controllers\Admin\Tour;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Exception;
use Illuminate\Http\JsonResponse;

use App\Http\Controllers\Controller;
use App\Interfaces\Tour\ShareRepositoryInterface;
use App\Models\Tour;

class ShareController extends Controller
{

    public function __construct(private readonly ShareRepositoryInterface $shareRepository) {}

    // public function generateShareLink(Tour $tour)
    // {
    //     try {
    //         Log::info('Share link generated for tour: ' . $tour->id);
    //         $share = $this->shareRepository->store(['tour_id' => $tour->id]);
    //         return redirect()->back()->with('success', 'Tour marked as ready to share');
    //     } catch (Exception $e) {
    //         Log::error('Failed to generate share link for tour: ' . $tour->id . ' Error: ' . $e->getMessage());
    //         return redirect()->back()->with('error', 'Failed to generate share link for tour');
    //     }
    // }

    /**
     * Generate a share link for the bespoke tour
     */
    public function generateShareLink(Request $request, Tour $tour): JsonResponse
    {
        Log::info('Share link generated for tour1: ' . $tour->id);
        if (($tour->tour_type ?? 'standard') !== 'bespoke') {
            return response()->json([
                'success' => false,
                'message' => 'Share links are only available for bespoke tours.'
            ], 403);
        }
        Log::info('Share link generated for tour2: ' . $tour->id);

        try {
            $share = $this->shareRepository->store(['tour_id' => $tour->id]);
            Log::info('Share link generated for tour3: ',compact('share'));

            Log::info('Share link generated for tour4: '. $this->shareRepository->getShareUrl($share->share_token));
            return response()->json([
                'success' => true,
                'message' => 'Share link generated successfully.',
                'share_url' => $this->shareRepository->getShareUrl($share->share_token),
                'expires_at' => $share->expires_at->format('Y-m-d H:i:s')
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to generate share link: ' . $e->getMessage()
            ], 500);
        }
    }
}
