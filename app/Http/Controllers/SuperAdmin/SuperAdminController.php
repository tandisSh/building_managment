<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Requests\RejectBuildingRequest;

use App\Http\Controllers\Controller;
use App\Models\BuildingRequest;
use App\Models\Building;
use Illuminate\Http\Request;

class SuperAdminController extends Controller
{
    public function dashboard()
    {
        $requests = BuildingRequest::with('user')->where('status', 'pending')->get();
        return view('super_admin.dashboard', compact('requests'));
    }
    public function requests()
    {
        $requests = BuildingRequest::with('user')->latest()->get();
        return view('super_admin.requests', compact('requests'));
    }

    public function approveRequest($id)
    {
        $req = BuildingRequest::findOrFail($id);

        $building = Building::create([
            'manager_id' => $req->user_id,
            'name' => $req->building_name,
            'address' => $req->address,
            'shared_utilities' => $req->shared_utilities,
            'number_of_floors' => $req->number_of_floors,
            'number_of_units' => $req->number_of_units,
        ]);

        // اتصال مدیر به ساختمان جدید در جدول میانی
        $building->users()->attach($req->user_id, ['role' => 'manager']);

        $req->update(['status' => 'approved']);

        return back()->with('success', 'درخواست تأیید شد و ساختمان ثبت گردید.');
    }



    public function rejectRequest(RejectBuildingRequest $request, $id)
    {
        BuildingRequest::findOrFail($id)
            ->update([
                'status' => 'rejected',
                'rejection_reason' => $request->reason
            ]);

        return back()->with('success', 'درخواست رد شد');
    }

}
