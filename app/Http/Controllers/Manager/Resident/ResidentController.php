<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreResidentRequest;
use App\Models\Unit;
use App\Models\UnitUser;
use App\Models\User;
use App\Services\ResidentService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ResidentController extends Controller
{
    public function index()
    {
        $buildingUser = Auth::user()->buildingUser;

        if (!$buildingUser) {
            abort(403, 'شما به هیچ ساختمانی متصل نیستید.');
        }

        $buildingId = $buildingUser->building_id;

        $residents = UnitUser::whereHas('unit', function ($query) use ($buildingId) {
            $query->where('building_id', $buildingId);
        })->with(['user', 'unit'])->get();

        return view('manager.residents.index', compact('residents'));
    }
    public function create()
    {
        $buildingId = auth()->user()->buildingUser->building_id;
        $units = Unit::where('building_id', $buildingId)->get();

        return view('manager.residents.create', compact('units'));
    }
    public function store(StoreResidentRequest $request)
    {
        $data = $request->validated(); 
        $unitId = $data['unit_id'];

        (new ResidentService())->createResident($data, $unitId);

        return redirect()->route('residents.index')->with('success', 'ساکن جدید با موفقیت اضافه شد.');
    }
    public function edit($resident)
    {
        $resident= User::findOrFail($resident);
        $buildingId = auth()->user()->buildingUser->building_id;
        $units = Unit::where('building_id', $buildingId)->get();

        return view('manager.residents.edit', compact('units','resident'));
    }
    public function update(StoreResidentRequest $request, $residentId)
    {
        $data = $request->validated();
        $unitId = $data['unit_id'];

        $user = User::findOrFail($residentId);

        // آپدیت اطلاعات کاربر
        $user->update([
            'name' => $data['name'],
            'phone' => $data['phone'],
            'email' => $data['email'],
        ]);

        // چک می‌کنیم که آیا کاربر قبلاً به این واحد متصل شده یا نه
        $unitUser = \App\Models\UnitUser::where('user_id', $user->id)
            ->where('unit_id', $unitId)
            ->first();

        if ($unitUser) {
            // آپدیت نقش و تاریخ‌ها
            $unitUser->update([
                'role' => $data['role'],
                'from_date' => $data['from_date'],
                'to_date' => $data['to_date'],
            ]);
        } else {
            // اگر اتصال جدید است، اتصال ایجاد کن ولی قبلش چک کن نقش تکراری نباشد
            $existingRole = \App\Models\UnitUser::where('unit_id', $unitId)
                ->where('role', $data['role'])
                ->where('user_id', '!=', $user->id)
                ->exists();

            if ($existingRole) {
                return redirect()->back()->withErrors(['role' => 'برای این واحد قبلاً یک ' . ($data['role'] == 'owner' ? 'مالک' : 'مستأجر') . ' ثبت شده است.']);
            }

            \App\Models\UnitUser::create([
                'unit_id' => $unitId,
                'user_id' => $user->id,
                'role' => $data['role'],
                'from_date' => $data['from_date'],
                'to_date' => $data['to_date'],
            ]);
        }

        // اتصال به building_user در صورت نیاز
        $unit = \App\Models\Unit::find($unitId);
        if ($unit && $unit->building_id) {
            \App\Models\BuildingUser::firstOrCreate([
                'building_id' => $unit->building_id,
                'user_id' => $user->id,
            ]);
        }

        return redirect()->route('residents.index')->with('success', 'اطلاعات ساکن با موفقیت بروزرسانی شد.');
    }

}

