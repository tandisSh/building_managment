<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Unit extends Model
{
    protected $fillable = [
        'building_id',
        'unit_number',
        'floor',
        'area',
        'parking_slots',
        'storerooms',
    ];

    protected static function booted(): void
    {
        static::deleting(function (Unit $unit) {
            if ($unit->isDeletable()) {
                abort(403, 'این واحد دارای ساکن بوده و قابل حذف نمی‌باشد.');
            }
        });
    }
    public function isDeletable(): bool
    {
        return $this->users()->exists();
    }


    public function building()
    {
        return $this->belongsTo(Building::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class)->withPivot('role', 'from_date', 'to_date');
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }

    public function owner()
    {
        return $this->users()->wherePivot('role', 'owner');
    }

    public function resident()
    {
        return $this->users()->wherePivot('role', 'resident');
    }

    public function repairRequests()
    {
        return $this->hasMany(RepairRequest::class);
    }
    public function residents()
    {
        return $this->belongsToMany(User::class, 'unit_user')
            ->withPivot('resident_count')
            ->wherePivot('resident_count', '>', 0);
    }

    public function totalResidentsCount()
    {
        return $this->residents()->sum('unit_user.resident_count');
    }
    public function unitUsers()
    {
        return $this->hasMany(UnitUser::class);
    }
}
