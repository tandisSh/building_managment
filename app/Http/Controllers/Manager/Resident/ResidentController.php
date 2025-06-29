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
public function index(ResidentService $residentService)
{
    $buildingId = Auth::user()->buildingUser->building_id;

    $filters = request()->only(['search', 'role', 'unit_id', 'status']); // اضافه کردن status
    $residents = $residentService->getFilteredResidents($filters, $buildingId);
    $units = Unit::where('building_id', $buildingId)->get();

    return view('manager.residents.index', compact('residents', 'units'));
}

    public function show(User $resident)
    {
        $unitUser = UnitUser::with('unit.building')
            ->where('user_id', $resident->id)
            ->orderByDesc('from_date')
            ->first();


        return view('manager.residents.show', compact('resident', 'unitUser'));
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

    public function destroy(User $resident)
    {
        try {
            // بررسی اینکه آیا ساکن قابل حذف است
            if (!$resident->isDeletable()) {
                return redirect()->back()->with('error', 'امکان حذف این ساکن وجود ندارد.');
            }

            $resident->delete();
            return redirect()->route('residents.index')
                ->with('success', 'ساکن با موفقیت حذف شد.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'خطا در حذف ساکن: ' . $e->getMessage());
        }
    }
}
