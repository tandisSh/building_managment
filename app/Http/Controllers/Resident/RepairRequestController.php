<?php

namespace App\Http\Controllers\Resident;

use App\Http\Controllers\Controller;
use App\Http\Requests\Requests\RepairRequestFormRequest;
use App\Models\RepairRequest;
use Illuminate\Http\Request;

class RepairRequestController extends Controller
{
    public function create()
    {
        return view('resident.requests.create');
    }

    public function store(RepairRequestFormRequest $request)
    {
       


        $unit = auth()->user()->units()->first();

        if (!$unit) {
            return back()->withErrors(['unit' => 'هیچ واحدی برای این کاربر یافت نشد.']);
        }

        RepairRequest::create([
            'unit_id' => $unit->id,
            'user_id' => auth()->id(),
            'title'   => $request->title,
            'description' => $request->description,
        ]);
        return redirect()->route('resident.requests.index')->with('success', 'درخواست با موفقیت ثبت شد.');
    }

    public function index()
    {
        $requests = RepairRequest::where('user_id', auth()->id())->latest()->get();
        return view('resident.requests.index', compact('requests'));
    }


    public function edit(RepairRequest $request)
    {
        // فقط اگر درخواست متعلق به کاربر فعلی و در وضعیت "pending" باشد اجازه ویرایش بده
        if ($request->user_id !== auth()->id() || $request->status !== 'pending') {
            abort(403);
        }

        return view('resident.requests.edit', compact('request'));
    }

    public function update(RepairRequestFormRequest $formRequest, RepairRequest $request)
    {
        // بررسی مالکیت و وضعیت
        if ($request->user_id !== auth()->id() || $request->status !== 'pending') {
            abort(403);
        }

        $request->update([
            'title' => $formRequest->title,
            'description' => $formRequest->description,
        ]);

        return redirect()->route('resident.requests.index')->with('success', 'درخواست با موفقیت ویرایش شد.');
    }
}
