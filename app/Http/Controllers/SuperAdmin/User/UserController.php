<?php

namespace App\Http\Controllers\Superadmin\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\User\UserRequest;
use App\Models\Building;
use App\Services\Admin\User\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UserController extends Controller
{
    public function __construct(protected UserService $userService) {}

    public function index(Request $request): View
    {
        $filters = $request->only(['search', 'building_id', 'role']);
        $users = $this->userService->getFilteredUsers($filters);
        $buildings = $this->userService->getBuildingsForForm();

        return view('super_admin.users.index', compact('users', 'buildings'));
    }

 public function create()
{
    $buildings = Building::with('units')->get();
    $freeBuildings = Building::whereNull('manager_id')->get(); // یا doesn't have manager

    return view('super_admin.users.create', compact('buildings', 'freeBuildings'));
}



    public function store(UserRequest $request, UserService $service)
    {
        $service->create($request->validated());
        
        return redirect()->route('superadmin.users.index')->with('success', 'کاربر با موفقیت اضافه شد.');
    }



    public function getBuildingsWithoutManager(): JsonResponse
    {
        $buildings = Building::whereNull('manager_id')->get();
        return response()->json($buildings);
    }

    public function getBuildingUnits(Building $building): JsonResponse
    {
        $units = $building->units()->get(['id', 'unit_number', 'floor']);
        return response()->json($units);
    }
}
