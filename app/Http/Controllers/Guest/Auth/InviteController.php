<?php

namespace App\Http\Controllers\Guest\Auth;

use App\Http\Controllers\Controller;
use App\Interfaces\UserRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class InviteController extends Controller
{
    public function __construct(
        protected UserRepositoryInterface $userRepository
    ) {}

    /**
     * Show the invite acceptance form.
     */
    public function show(string $token)
    {
        $user = $this->userRepository->findByInviteToken($token);

        if (!$user) {
            return redirect()->route('login')
                ->with('error', 'Invalid or expired invitation link.');
        }

        if (!$user->hasValidInvite()) {
            return redirect()->route('login')
                ->with('error', 'This invitation has expired. Please contact an administrator.');
        }

        if ($user->hasAcceptedInvite()) {
            return redirect()->route('login')
                ->with('info', 'You have already accepted this invitation. Please log in.');
        }

        return view('auth.accept-invite', compact('user', 'token'));
    }

    /**
     * Accept the invitation and set password.
     */
    public function accept(Request $request, string $token)
    {
        $user = $this->userRepository->findByInviteToken($token);

        if (!$user || !$user->hasValidInvite()) {
            return redirect()->route('login')
                ->with('error', 'Invalid or expired invitation link.');
        }

        $request->validate([
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        // Update password and accept invite
        $user->update([
            'password' => Hash::make($request->password),
        ]);
        $user->acceptInvite();

        // Log the user in
        Auth::login($user);

        return redirect()->route('dashboard.redirect')
            ->with('success', 'Welcome! Your account has been activated.');
    }
}
