@extends('layouts.app')

@section('content')
<div class="card">
    <div class="card-header text-white py-3">
        <h5 class="mb-0">افزودن واحد جدید</h5>
    </div>

    <div class="card-body">
        <form action="{{ route('units.store', $building->id) }}" method="POST">
            @csrf

            <div class="mb-3">
                <label class="form-label">شماره واحد</label>
                <input type="text" name="unit_number" class="form-control" value="{{ old('unit_number') }}" >
            </div>

            <div class="mb-3">
                <label class="form-label">طبقه</label>
                <input type="number" name="floor" class="form-control" value="{{ old('floor') }}">
            </div>

            <div class="mb-3">
                <label class="form-label">متراژ</label>
                <input type="number" step="0.01" name="area" class="form-control" value="{{ old('area') }}">
            </div>

            <div class="mb-3">
                <label class="form-label">تعداد جای پارک</label>
                <input type="number" name="parking_slots" class="form-control" value="{{ old('parking_slots', 0) }}">
            </div>

            <div class="mb-3">
                <label class="form-label">تعداد انباری</label>
                <input type="number" name="storerooms" class="form-control" value="{{ old('storerooms', 0) }}">
            </div>

            <button type="submit" class="btn btn-primary">ثبت</button>
            <a href="{{ route('units.index', $building->id) }}" class="btn btn-secondary">بازگشت</a>
        </form>
    </div>
</div>
@endsection
