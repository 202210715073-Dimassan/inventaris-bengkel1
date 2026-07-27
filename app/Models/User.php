<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    /**
     * Helper untuk cek apakah user adalah Admin.
     */
    public function isAdmin(): bool
    {
        return ($this->role ?? 'admin') === 'admin';
    }

    /**
     * Helper untuk cek apakah user adalah Owner.
     */
    public function isOwner(): bool
    {
        return ($this->role ?? '') === 'owner';
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
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
            'password' => 'hashed',
        ];
    }

    /**
     * Sesuai Class Diagram: Hak akses kelola produk.
     */
    public function manageProduct(): bool
    {
        return true;
    }

    /**
     * Sesuai Class Diagram: Hak akses kelola supplier.
     */
    public function manageSupplier(): bool
    {
        return true;
    }

    /**
     * Sesuai Class Diagram: Hak akses kelola transaksi.
     */
    public function manageTransaction(): bool
    {
        return true;
    }

    /**
     * Sesuai Class Diagram: Hak akses monitoring stok.
     */
    public function monitoringStock(): bool
    {
        return true;
    }
}
