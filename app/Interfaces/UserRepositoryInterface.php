<?php

namespace App\Interfaces;

use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface UserRepositoryInterface
{
    /**
     * Get paginated list of users with optional filters.
     */
    public function index(array $filters = [], int $perPage = 15): LengthAwarePaginator;

    /**
     * Find a user by ID.
     */
    public function find(int $id): ?User;

    /**
     * Find a user by email.
     */
    public function findByEmail(string $email): ?User;

    /**
     * Find a user by invite token.
     */
    public function findByInviteToken(string $token): ?User;

    /**
     * Create a new user.
     */
    public function create(array $data): User;

    /**
     * Update an existing user.
     */
    public function update(User $user, array $data): User;

    /**
     * Delete a user.
     */
    public function delete(User $user): void;

    /**
     * Send invite to user.
     */
    public function sendInvite(User $user): void;

    /**
     * Resend invite to user.
     */
    public function resendInvite(User $user): void;
}
