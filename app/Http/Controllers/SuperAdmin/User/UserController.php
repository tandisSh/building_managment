<?php

namespace App\Http\Controllers\SuperAdmin\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\User\UserRequest;
use App\Models\Building;
use App\Models\UnitUser;
use App\Models\User;
use App\Services\Admin\User\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
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

    public function create(): View
    {
        $buildings = Building::all();
        $freeBuildings = Building::whereNull('manager_id')->get();

        return view('super_admin.users.create', compact('buildings', 'freeBuildings'));
    }

    public function store(UserRequest $request): RedirectResponse
    {
        $this->userService->create($request->validated());

        return redirect()->route('superadmin.users.index')->with('success', 'کاربر با موفقیت اضافه شد.');
    }

    public function edit($id): View
    {
        $user = $this->userService->getUserForEdit($id);
        $buildings = Building::all();
        $freeBuildings = Building::whereNull('manager_id')->get();

        return view('super_admin.users.edit', compact('user', 'buildings', 'freeBuildings'));
    }

    public function update(UserRequest $request, $id): RedirectResponse
    {
        $this->userService->updateUser($id, $request->validated());

        return redirect()->route('superadmin.users.index')->with('success', 'کاربر با موفقیت به‌روزرسانی شد.');
    }
   public function show($id)
    {
$resident=User::with('unit')->where('id',$id);
        // $user = $this->userService->getUserForEdit($id);
        // $unitUser = UnitUser::with('unit.building')
        //     ->where('user_id', $resident->id)
        //     ->orderByDesc('from_date')
        //     ->first();

        return view('super_admin.users.show', compact('resident'));
    }
    public function getBuildingUnits(Building $building): JsonResponse
    {
        try {
            $units = $building->units()
                ->whereDoesntHave('unitUsers', function ($query) {
                    $query->where('status', 'active');
                })
                ->get(['id', 'unit_number', 'floor']);

            return response()->json($units);
        } catch (\Exception $e) {
            Log::error('Error fetching units: ' . $e->getMessage());
            return response()->json(['error' => 'خطا در بارگذاری واحدها'], 500);
        }
    }
}
