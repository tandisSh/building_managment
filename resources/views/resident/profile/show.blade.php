@extends('layouts.app')

@section('content')
<div class="container mt-3">

    {{-- هدر صفحه --}}
    <div class="admin-header d-flex justify-content-between align-items-center mb-3 shadow-sm rounded" style="background-color: #e5ddfa;">
        <h6 class="mb-0 fw-bold text-white py-2 px-3">
            <i class="bi bi-person-vcard me-2"></i>پروفایل من
        </h6>
        <a href="{{ route('resident.profile.edit') }}" class="btn btn-sm add-btn px-3">
            <i class="bi bi-pencil-square me-1"></i>ویرایش اطلاعات
        </a>
    </div>

    {{-- اطلاعات پروفایل --}}
    <div class="admin-table-card p-4 shadow-sm rounded border-0">
        <div class="row mb-3">
            <div class="col-md-4">
                <div class="text-secondary small mb-1">نام کامل:</div>
                <div class="fw-bold">{{ $user->name }}</div>
            </div>

            <div class="col-md-4">
                <div class="text-secondary small mb-1">شماره تماس:</div>
                <div class="fw-bold">{{ $user->phone }}</div>
            </div>

            <div class="col-md-4">
                <div class="text-secondary small mb-1">ایمیل:</div>
                <div class="fw-bold">{{ $user->email }}</div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-4">
                <div class="text-secondary small mb-1">تاریخ عضویت:</div>
                <div class="fw-bold">{{ jdate($user->created_at)->format('Y/m/d') }}</div>
            </div>
        </div>
    </div>

</div>
@endsection
