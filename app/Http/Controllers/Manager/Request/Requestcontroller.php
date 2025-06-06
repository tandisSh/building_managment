<?php

namespace App\Http\Controllers\Manager\Request;

use App\Http\Controllers\Controller;
use App\Models\RepairRequest;
use Illuminate\Http\Request;

class RequestController extends Controller
{
    public function index(Request $request)
    {
        $manager = auth()->user();

        $query = RepairRequest::whereHas('unit', function ($unitQuery) use ($manager) {
            $unitQuery->whereHas('building', function ($buildingQuery) use ($manager) {
                $buildingQuery->where('manager_id', $manager->id);
            });
        })->with(['unit', 'user']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($q2) use ($search) {
                        $q2->where('name', 'like', "%{$search}%");
                    });
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $repairRequests = $query->latest()->paginate(10);

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

    public function show($id)
    {
        $request = RepairRequest::with(['user', 'unit'])->findOrFail($id);
        return view('manager.requests.show', compact('request'));
    }
}
