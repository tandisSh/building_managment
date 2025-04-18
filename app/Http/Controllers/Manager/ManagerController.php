<?php

namespace App\Http\Controllers\Manager;

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
        return view('manager.buildings.request');
    }

    public function storeRequest(Request $request)
    {
        $request->validate([
            'building_name' => 'required',
            'address' => 'required',
            'document' => 'required|file|mimes:pdf,jpg,png|max:2048'
        ]);

        $path = $request->file('document')->store('building_documents');

        BuildingRequest::create([
            'user_id' => auth()->id(),
            'building_name' => $request->building_name,
            'address' => $request->address,
            'document_path' => $path
        ]);

        return redirect()->route('manager.dashboard')
            ->with('success', 'درخواست با موفقیت ثبت شد');
    }
}
