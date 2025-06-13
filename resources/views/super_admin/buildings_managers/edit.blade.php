@extends('layouts.app')

@section('title', 'انتخاب مدیر ساختمان')

@section('content')
<div class="container mt-3">
    <div class="admin-header d-flex justify-content-between align-items-center mb-4" style="background-color: #4e3cb3;">
        <h6 class="mb-0 fw-bold text-white py-2 px-3">
            <i class="bi bi-person-badge me-2"></i> انتخاب مدیر برای ساختمان: {{ $building->name }}
        </h6>
    </div>

    <div class="admin-table-card p-4">
        <form action="{{ route('superadmin.building_managers.update', $building->id) }}" method="POST" class="w-50 mx-auto">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label for="manager_id" class="form-label small fw-bold">مدیر ساختمان *</label>
                <select name="manager_id" id="manager_id" class="form-select form-select-sm @error('manager_id') is-invalid @enderror" required>
                    <option value="">انتخاب مدیر</option>
                    @foreach ($managers as $manager)
                        <option value="{{ $manager->id }}" {{ $building->manager_id == $manager->id ? 'selected' : '' }}>
                            {{ $manager->name }} - {{ $manager->mobile }}
                        </option>
                    @endforeach
                </select>
                @error('manager_id')
                    <div class="invalid-feedback small">{{ $message }}</div>
                @enderror
            </div>

            <div class="d-flex justify-content-center gap-3 mt-4">
                <button type="submit" class="btn btn-sm add-btn px-4 py-2">
                    <i class="bi bi-save2 me-1"></i> ذخیره مدیر جدید
                </button>
                <a href="{{ route('superadmin.building_managers.index') }}" class="btn btn-sm btn-secondary px-4 py-2">
                    بازگشت
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
