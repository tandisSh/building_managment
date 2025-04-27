@extends('layouts.app')

@section('content')
<div class="card">
    <div class="card-header">افزودن واحد جدید به ساختمان {{ $building->building_name }}</div>
    <div class="card-body">
        <form action="{{ route('units.store', $building) }}" method="POST">
            @csrf

            <div class="mb-3">
                <label class="form-label">شماره واحد</label>
                <input type="text" name="unit_number" class="form-control" value="{{ old('unit_number') }}" required>
                @error('unit_number') <span class="text-danger small">{{ $message }}</span> @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">طبقه</label>
                <input type="number" name="floor" class="form-control" value="{{ old('floor') }}">
                @error('floor') <span class="text-danger small">{{ $message }}</span> @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">متراژ (مترمربع)</label>
                <input type="text" name="area" class="form-control" value="{{ old('area') }}">
                @error('area') <span class="text-danger small">{{ $message }}</span> @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">تعداد جای پارک</label>
                <input type="number" name="parking_slots" class="form-control" value="{{ old('parking_slots', 0) }}">
                @error('parking_slots') <span class="text-danger small">{{ $message }}</span> @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">تعداد انباری</label>
                <input type="number" name="storerooms" class="form-control" value="{{ old('storerooms', 0) }}">
                @error('storerooms') <span class="text-danger small">{{ $message }}</span> @enderror
            </div>

            <button type="submit" class="btn btn-primary">ثبت واحد</button>
            <a href="{{ route('buildings.show', $building->id) }}" class="btn btn-secondary">بازگشت</a>
        </form>
    </div>
</div>
@endsection
