<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'invite_token',
        'invite_sent_at',
        'invite_accepted_at',
        'must_change_password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'invite_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'invite_sent_at' => 'datetime',
            'invite_accepted_at' => 'datetime',
            'must_change_password' => 'boolean',
            'password' => 'hashed',
        ];
    }

    /**
     * Generate a unique invite token.
     */
    public function generateInviteToken(): string
    {
        $this->invite_token = Str::random(64);
        $this->invite_sent_at = now();
        $this->save();

        return $this->invite_token;
    }

    /**
     * Check if the invite is still valid (within 7 days).
     */
    public function hasValidInvite(): bool
    {
        return $this->invite_token 
            && $this->invite_sent_at 
            && $this->invite_sent_at->addDays(7)->isFuture();
    }

    /**
     * Check if the user has accepted their invite.
     */
    public function hasAcceptedInvite(): bool
    {
        return $this->invite_accepted_at !== null;
    }

    /**
     * Mark the invite as accepted.
     */
    public function acceptInvite(): void
    {
        $this->invite_token = null;
        $this->invite_accepted_at = now();
        $this->must_change_password = false;
        $this->email_verified_at = now();
        $this->save();
    }

    /**
     * Check if user needs to change their password.
     */
    public function mustChangePassword(): bool
    {
        return $this->must_change_password === true;
    }

}
