<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreResidentRequest;
use App\Models\Unit;
use App\Models\UnitUser;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class ResidentController extends Controller
{
    public function index()
    {
        $buildingId = auth()->user()->buildingUser->building_id;

        $residents = UnitUser::whereHas('unit', function ($q) use ($buildingId) {
            $q->where('building_id', $buildingId);
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
        $user = User::create([
            'name' => $request->name,
            'phone' => $request->phone,
            'password' => Hash::make('resident123'), 
        ]);

        $user->units()->attach($request->unit_id, [
            'role' => $request->role,
            'from_date' => $request->from_date,
            'to_date' => $request->to_date,
        ]);

        return redirect()->route('residents.index')->with('success', 'ساکن جدید با موفقیت اضافه شد.');
    }
}
