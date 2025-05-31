<?php

namespace App\Services\Resident\Profile;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class ProfileService
{
    public function update(User $user, array $data): bool
    {
        $user->name = $data['name'];
        $user->email = $data['email'];
        $user->phone = $data['phone'] ?? null;


        return $user->save();
    }
}
