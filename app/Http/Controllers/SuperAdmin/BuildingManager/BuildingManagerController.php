<?php

namespace App\Http\Controllers\SuperAdmin\BuildingManager;

use App\Http\Controllers\Controller;
use App\Services\Admin\BuildingManager\BuildingManagerService;
use Illuminate\Http\Request;

class BuildingManagerController extends Controller
{
    protected BuildingManagerService $service;

    public function __construct(BuildingManagerService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request)
    {
        $filters = $request->only(['search', 'manager_id']);
        $data = $this->service->getBuildingsWithManagers($filters);
        return view('super_admin.buildings_managers.index', $data);
    }

    public function edit($buildingId)
    {
        $data = $this->service->getBuildingWithAvailableManagers($buildingId);
        return view('super_admin.buildings_managers.edit', $data);
    }

    public function update(Request $request, $buildingId)
    {
        $request->validate([
            'manager_id' => 'required|exists:users,id',
        ]);

        $this->service->updateBuildingManager($buildingId, $request->manager_id);

        return redirect()->route('superadmin.building_managers.index')
            ->with('success', 'مدیر ساختمان با موفقیت به‌روزرسانی شد.');
    }
}
