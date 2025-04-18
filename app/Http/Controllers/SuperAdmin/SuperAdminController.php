<?php

namespace App\Http\Controllers\SuperAdmin;

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

    public function approveRequest($id)
    {
        $req = BuildingRequest::findOrFail($id);

        Building::create([
            'manager_id' => $req->user_id,
            'name' => $req->building_name,
            'address' => $req->address
        ]);

        $req->update(['status' => 'approved']);

        return back()->with('success', 'درخواست تأیید شد');
    }

    public function rejectRequest(Request $request, $id)
    {
        $request->validate(['reason' => 'required']);

        BuildingRequest::findOrFail($id)
            ->update([
                'status' => 'rejected',
                'rejection_reason' => $request->reason
            ]);

        return back()->with('success', 'درخواست رد شد');
    }
}
