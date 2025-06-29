<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Auth\Notifications\ResetPassword;

class User extends Authenticatable
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
        'phone',
        'email',
        'password',
        'status'
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
    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }

    public function hasRole($roleName)
    {
        return $this->roles()->where('name', $roleName)->exists();
    }
    public function assignRole($roleName)
    {
        $role = Role::where('name', $roleName)->first();
        if ($role && !$this->hasRole($roleName)) {
            $this->roles()->attach($role->id);
        }
    }

    public function building()
    {
        return $this->hasOne(Building::class, 'manager_id');
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function repairRequests()
    {
        return $this->hasMany(RepairRequest::class);
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }
    public function managedBuildings()
    {
        return $this->hasMany(Building::class, 'manager_id');
    }
    public function buildingUser()
    {
        return $this->hasOne(\App\Models\BuildingUser::class);
    }
    public function sendPasswordResetNotification($token)
    {
        $url = url(route('password.reset', ['token' => $token, 'email' => $this->email], false));

        $this->notify(new ResetPassword($token));
    }
    public function units()
    {
        return $this->belongsToMany(Unit::class)->withPivot('role')->withTimestamps();
    }

     public function unitUsers()
    {
        return $this->hasMany(UnitUser::class);
    }
      public function buildingUsers()
    {
        return $this->hasMany(BuildingUser::class, 'user_id');
    }

    public function isDeletable(): bool
    {
        return !$this->payments()->exists() && !$this->units()->whereHas('invoices')->exists();
    }
}
