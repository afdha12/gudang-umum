<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Spatie\MediaLibrary\HasMedia;
use Illuminate\Notifications\Notifiable;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements HasMedia
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'username',
        'division_id',
        'email',
        'role',
        'password',
        'password_changed',
    ];

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

    use InteractsWithMedia;

    // Fungsi untuk menentukan konversi media jika diperlukan
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('signature')->singleFile(); 
    }

    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function hasChangedPassword()
    {
        return !is_null($this->password_changed_at); // Misalnya, ada kolom `password_changed_at`
    }

    public function division()
    {
        return $this->belongsTo(Division::class, 'division_id');
    }

}
