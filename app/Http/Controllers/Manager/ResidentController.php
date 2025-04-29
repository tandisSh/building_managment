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

}
