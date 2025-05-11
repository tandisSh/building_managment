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
                <input type="text" name="unit_number" class="form-control @error('unit_number') is-invalid @enderror" value="{{ old('unit_number', $unit->unit_number) }}">
                @error('unit_number')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">طبقه</label>
                <input type="number" name="floor" class="form-control @error('floor') is-invalid @enderror" value="{{ old('floor', $unit->floor) }}">
                @error('floor')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">متراژ</label>
                <input type="number" step="0.01" name="area" class="form-control @error('area') is-invalid @enderror" value="{{ old('area', $unit->area) }}">
                @error('area')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">تعداد جای پارک</label>
                <input type="number" name="parking_slots" class="form-control @error('parking_slots') is-invalid @enderror" value="{{ old('parking_slots', $unit->parking_slots) }}">
                @error('parking_slots')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">تعداد انباری</label>
                <input type="number" name="storerooms" class="form-control @error('storerooms') is-invalid @enderror" value="{{ old('storerooms', $unit->storerooms) }}">
                @error('storerooms')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="btn btn-success">ذخیره تغییرات</button>
            <a href="{{ route('units.index', $building->id) }}" class="btn btn-secondary">بازگشت</a>
        </form>
    </div>
</div>
@endsection
