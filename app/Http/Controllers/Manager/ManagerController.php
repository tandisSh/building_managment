<?php

namespace App\Http\Controllers\Manager;

use App\Http\Requests\StoreBuildingRequest;

use App\Http\Controllers\Controller;
use App\Models\BuildingRequest;
use Illuminate\Http\Request;
use App\Models\User;

class ManagerController extends Controller
{
    public function dashboard()
    {
        $requests = BuildingRequest::where('user_id', auth()->id())->get();
        return view('manager.dashboard', compact('requests'));
    }

    public function createRequest()
    {
        $existingRequest = BuildingRequest::where('user_id', auth()->id())
            ->whereIn('status', ['pending', 'approved']) // فقط اگر در حال بررسی یا تایید شده باشه، نذاره
            ->first();

        if ($existingRequest) {
            return redirect()->route('manager.dashboard')
                ->with('error', 'شما قبلاً یک درخواست ثبت کرده‌اید که هنوز در حال بررسی یا تایید شده است.');
        }

        return view('manager.buildings.request');
    }

    public function storeRequest(StoreBuildingRequest $request)
    {
        $existingRequest = BuildingRequest::where('user_id', auth()->id())
            ->whereIn('status', ['pending', 'approved'])
            ->first();

        if ($existingRequest) {
            return redirect()->route('manager.dashboard')
                ->with('error', 'شما قبلاً یک درخواست ثبت کرده‌اید.');
        }

        $path = $request->file('document')->store('building_documents');

        BuildingRequest::create([
            'user_id' => auth()->id(),
            'building_name' => $request->building_name,
            'address' => $request->address,
            'number_of_floors' => $request->number_of_floors,
            'number_of_units' => $request->number_of_units,
            'shared_utilities' => $request->shared_utilities,
            'document_path' => $path,
        ]);

        return redirect()->route('manager.dashboard')
            ->with('success', 'درخواست با موفقیت ثبت شد');
    }
}
