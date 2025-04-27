@extends('layouts.app')

@section('content')
<div class="card">
    <div class="card-header text-white py-3">
        <h5 class="mb-0">ویرایش واحد {{ $unit->unit_number }}</h5>
    </div>

    <div class="card-body">
        <form action="{{ route('units.update', [$building->id, $unit->id]) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label class="form-label">شماره واحد</label>
                <input type="text" name="unit_number" class="form-control" value="{{ old('unit_number', $unit->unit_number) }}" required>
            </div>

            <div class="mb-3">
                <label class="form-label">طبقه</label>
                <input type="number" name="floor" class="form-control" value="{{ old('floor', $unit->floor) }}">
            </div>

            <div class="mb-3">
                <label class="form-label">متراژ</label>
                <input type="number" step="0.01" name="area" class="form-control" value="{{ old('area', $unit->area) }}">
            </div>

            <div class="mb-3">
                <label class="form-label">تعداد جای پارک</label>
                <input type="number" name="parking_slots" class="form-control" value="{{ old('parking_slots', $unit->parking_slots) }}">
            </div>

            <div class="mb-3">
                <label class="form-label">تعداد انباری</label>
                <input type="number" name="storerooms" class="form-control" value="{{ old('storerooms', $unit->storerooms) }}">
            </div>

            <button type="submit" class="btn btn-success">ذخیره تغییرات</button>
            <a href="{{ route('units.index', $building->id) }}" class="btn btn-secondary">بازگشت</a>
        </form>
    </div>
</div>
@endsection
