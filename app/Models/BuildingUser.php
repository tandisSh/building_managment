<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BuildingUser extends Model
{
    protected $table = 'building_user';

    protected $fillable = [
        'building_id',
        'user_id',
        'role',
    ];

    public $timestamps = false;

    public function building()
    {
        return $this->belongsTo(Building::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
