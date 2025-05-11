<?php

namespace App\Http\Controllers\Manager\Resident;

use App\Http\Controllers\Controller;
use App\Http\Requests\Resident\ResidentRequest;
use App\Models\Unit;
use App\Models\UnitUser;
use App\Models\User;
use App\Services\Manager\Resident\ResidentService;
use Illuminate\Support\Facades\Auth;

class ResidentController extends Controller
{
    public function index()
    {
        $buildingId = Auth::user()->buildingUser->building_id;

        $residents = UnitUser::whereHas('unit', function ($q) use ($buildingId) {
            $q->where('building_id', $buildingId);
        })->with(['user', 'unit'])->get();

        return view('manager.residents.index', compact('residents'));
    }

    public function create()
    {
        $units = Unit::where('building_id', Auth::user()->buildingUser->building_id)->get();
        return view('manager.residents.create', compact('units'));
    }

    public function store(ResidentRequest $request, ResidentService $service)
    {
        $service->create($request->validated());
        return redirect()->route('residents.index')->with('success', 'ساکن جدید ثبت شد.');
    }

    public function edit(User $resident)
    {
        $units = Unit::where('building_id', Auth::user()->buildingUser->building_id)->get();
        return view('manager.residents.edit', compact('resident', 'units'));
    }

    public function update(ResidentRequest $request, User $resident, ResidentService $service)
    {
        $service->update($resident, $request->validated());
        return redirect()->route('residents.index')->with('success', 'ساکن بروزرسانی شد.');
    }


}


