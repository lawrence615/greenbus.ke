<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\Contracts\TourShareRepositoryInterface;
use App\Models\Tour;

class TourShareController extends Controller
{
    protected TourShareRepositoryInterface $tourShareRepository;

    public function __construct(TourShareRepositoryInterface $tourShareRepository)
    {
        $this->tourShareRepository = $tourShareRepository;
    }

    public function createShare(Request $request, Tour $tour)
    {
        $share = $this->tourShareRepository->createShareToken($tour->id);
        
        return response()->json([
            'share_token' => $share->share_token,
            'share_url' => route('tours.shared', $share->share_token),
            'expires_at' => $share->expires_at,
        ]);
    }

    public function showShared($token)
    {
        $share = $this->tourShareRepository->findByToken($token);
        
        if (!$share || !$share->isActive()) {
            abort(404, 'Shared tour not found or expired');
        }

        // Mark as shared when first accessed
        if ($share->share_status === 'ready') {
            $this->tourShareRepository->markAsShared($share->id);
        }

        return view('tours.shared', compact('share'));
    }

    public function index()
    {
        $shares = $this->tourShareRepository->all();
        return view('admin.tour-shares.index', compact('shares'));
    }

    public function destroy($id)
    {
        $this->tourShareRepository->delete($id);
        return back()->with('success', 'Tour share deleted successfully');
    }
}
