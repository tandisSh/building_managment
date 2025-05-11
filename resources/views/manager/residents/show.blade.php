@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">نمایش اطلاعات ساکن</h5>
        </div>

        <div class="card-body">

            {{-- اطلاعات کاربر --}}
            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="border rounded p-3 h-100">
                        <h6 class="text-muted">اطلاعات شخصی</h6>
                        <p><strong>نام:</strong> {{ $resident->name }}</p>
                        <p><strong>ایمیل:</strong> {{ $resident->email ?? '—' }}</p>
                        <p><strong>تلفن:</strong> {{ $resident->phone ?? '—' }}</p>
                    </div>
                </div>

                {{-- اطلاعات واحد و ساختمان --}}
                <div class="col-md-6">
                    <div class="border rounded p-3 h-100">
                        <h6 class="text-muted">واحد و ساختمان</h6>
                        <p><strong>واحد:</strong> {{ $unitUser->unit->unit_number ?? '—' }}</p>
                        <p><strong>ساختمان:</strong> {{ $unitUser->unit->building->building_name ?? '—' }}</p>
                        <p><strong>نقش:</strong> {{ $unitUser->role == 'owner' ? 'مالک' : 'ساکن' }}</p>
                    </div>
                </div>
            </div>

            {{-- تاریخ‌ها --}}
            <div class="row">
                <div class="col-md-6">
                    <div class="border rounded p-3">
                        <h6 class="text-muted">تاریخ اقامت</h6>
                        <p><strong>از تاریخ:</strong> {{ jdate($unitUser->from_date)->format('Y/m/d') }}</p>
                        <p><strong>تا تاریخ:</strong> {{ $unitUser->to_date ? jdate($unitUser->to_date)->format('Y/m/d') : 'نامشخص' }}</p>
                    </div>
                </div>
            </div>

            <a href="{{ route('residents.index') }}" class="btn btn-secondary mt-4">بازگشت</a>
        </div>
    </div>
</div>
@endsection
