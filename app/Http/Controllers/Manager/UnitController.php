<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUnitRequest;
use App\Models\Building;
use Illuminate\Http\Request;

class UnitController extends Controller
{
    public function create(Building $building)
    {
        return view('manager.units.create', compact('building'));
    }

    public function store(StoreUnitRequest $request, Building $building)
    {
        $building->units()->create($request->validated());

        return redirect()->route('buildings.show', $building->id)
                         ->with('success', 'واحد با موفقیت ثبت شد.');
    }
}
