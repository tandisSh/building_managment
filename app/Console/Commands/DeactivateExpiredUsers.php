<?php

namespace App\Console\Commands;

// app/Console/Commands/DeactivateExpiredUsers.php
use Illuminate\Console\Command;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DeactivateExpiredUsers extends Command
{
    protected $signature = 'users:deactivate-expired';
    protected $description = 'Deactivate users whose stay has ended';

    public function handle()
    {
        $today = Carbon::today();

        // تمام یوزرهایی که در unit_user تاریخ پایان سکونت‌شون گذشته و status هنوز active هست
        $expiredUserIds = DB::table('unit_user')
            ->whereNotNull('to_date')
            ->where('to_date', '<', $today)
            ->where('status', 'active')
            ->pluck('user_id')
            ->unique();

        // غیرفعال کردن یوزرها در جدول users
        User::whereIn('id', $expiredUserIds)->update(['status' => 'inactive']);

        // همچنین وضعیت ردیف unit_user رو هم غیرفعال کنیم
        DB::table('unit_user')
            ->whereIn('user_id', $expiredUserIds)
            ->where('status', 'active')
            ->update(['status' => 'inactive']);

        $this->info("Done. Deactivated users: " . $expiredUserIds->count());
    }
}
