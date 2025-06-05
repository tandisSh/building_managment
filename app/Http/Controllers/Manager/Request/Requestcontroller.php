<?php

namespace App\Http\Controllers\Manager\Request;

use App\Http\Controllers\Controller;
use App\Models\RepairRequest;
use Illuminate\Http\Request;

class RequestController extends Controller
{
    public function index()
    {
        $manager = auth()->user();
        $repairRequests = RepairRequest::whereHas('unit', function ($unitQuery) use ($manager) {
            $unitQuery->whereHas('building', function ($buildingQuery) use ($manager) {
                $buildingQuery->where('manager_id', $manager->id);
            });
        })
            ->with(['unit', 'user'])
            ->latest()
            ->get();
        return view('manager.requests.index', compact('repairRequests'));
    }

    public function updateStatus(Request $request, RepairRequest $repairRequest)
    {
        $request->validate([
            'status' => ['required', 'in:pending,in_progress,done'],
        ]);

        $repairRequest->update([
            'status' => $request->status,
        ]);

        return back()->with('success', 'وضعیت با موفقیت به‌روزرسانی شد.');
    }
}
