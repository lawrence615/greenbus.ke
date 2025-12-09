<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\StoreRequest;
use App\Http\Requests\User\UpdateRequest;
use App\Interfaces\UserRepositoryInterface;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function __construct(
        protected UserRepositoryInterface $userRepository
    ) {}

    public function index(Request $request)
    {
        $users = $this->userRepository->index([
            'role' => $request->role,
            'search' => $request->search,
        ]);
        
        $roles = Role::all();

        return view('admin.users.index', compact('users', 'roles'));
    }

    public function create()
    {
        $roles = Role::all();

        return view('admin.users.create', compact('roles'));
    }

    public function store(StoreRequest $request)
    {
        $user = $this->userRepository->create($request->validated());

        // Send invite email if requested
        if ($request->boolean('send_invite')) {
            $this->userRepository->sendInvite($user);
            return redirect()->route('console.users.index')
                ->with('success', 'User created and invitation sent successfully.');
        }

        return redirect()->route('console.users.index')
            ->with('success', 'User created successfully.');
    }

    public function edit(User $user)
    {
        $roles = Role::all();

        return view('admin.users.edit', compact('user', 'roles'));
    }

    public function update(UpdateRequest $request, User $user)
    {
        $this->userRepository->update($user, $request->validated());

        return redirect()->route('console.users.index')
            ->with('success', 'User updated successfully.');
    }

    public function destroy(User $user)
    {
        // Prevent self-deletion
        if ($user->id === auth()->id()) {
            return back()->with('error', 'You cannot delete your own account.');
        }

        $this->userRepository->delete($user);

        return redirect()->route('console.users.index')
            ->with('success', 'User deleted successfully.');
    }

    /**
     * Resend invitation to user.
     */
    public function resendInvite(User $user)
    {
        if ($user->hasAcceptedInvite()) {
            return back()->with('error', 'This user has already accepted their invitation.');
        }

        $this->userRepository->resendInvite($user);

        return back()->with('success', 'Invitation resent successfully.');
    }
}
