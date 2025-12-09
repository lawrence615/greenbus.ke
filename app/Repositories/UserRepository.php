<?php

namespace App\Repositories;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Collection;

use App\Interfaces\UserRepositoryInterface;
use App\Models\User;
use App\Notifications\UserInviteNotification;

class UserRepository implements UserRepositoryInterface
{
    public function index(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        $query = User::with('roles')->latest();

        if (!empty($filters['role'])) {
            $query->role($filters['role']);
        }

        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if (isset($filters['verified'])) {
            if ($filters['verified']) {
                $query->whereNotNull('email_verified_at');
            } else {
                $query->whereNull('email_verified_at');
            }
        }

        return $query->paginate($perPage)->withQueryString();
    }

    public function find(int $id): ?User
    {
        return User::with('roles')->find($id);
    }

    public function findByEmail(string $email): ?User
    {
        return User::where('email', $email)->first();
    }

    public function findByInviteToken(string $token): ?User
    {
        return User::where('invite_token', $token)->first();
    }

    public function create(array $data): User
    {
        // Determine password and must_change_password based on password_option
        $passwordOption = $data['password_option'] ?? 'auto';

        if ($passwordOption === 'auto') {
            // Auto-generate a strong password - user won't need to change it
            $password = Str::random(32);
            $mustChangePassword = false;
        } else {
            // Manual password - check if user should change it on first login
            $password = $data['password'];
            $mustChangePassword = $data['require_password_change'] ?? true;
        }

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($password),
            'must_change_password' => $mustChangePassword,
        ]);

        // Assign role
        if (!empty($data['role'])) {
            $user->assignRole($data['role']);
        }

        return $user;
    }

    public function update(User $user, array $data): User
    {
        $updateData = [
            'name' => $data['name'],
            'email' => $data['email'],
        ];

        $user->update($updateData);

        // Update password if provided
        if (!empty($data['password'])) {
            $user->update(['password' => Hash::make($data['password'])]);
        }

        // Sync role if provided
        if (!empty($data['role'])) {
            $user->syncRoles([$data['role']]);
        }

        return $user->fresh(['roles']);
    }

    public function delete(User $user): void
    {
        $user->delete();
    }

    public function sendInvite(User $user): void
    {
        $user->generateInviteToken();
        $user->notify(new UserInviteNotification());
    }

    public function resendInvite(User $user): void
    {
        // Don't resend if already accepted
        if ($user->hasAcceptedInvite()) {
            return;
        }

        $this->sendInvite($user);
    }

    public function getAdminAndManagers(): Collection
    {
        return User::role(['admin', 'manager'])->get();
    }
}
