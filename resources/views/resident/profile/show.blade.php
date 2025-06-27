{{-- @extends('layouts.app')

@section('content')
<div class="container mt-3">

    {{-- هدر صفحه --}}
    {{-- <div class="admin-header d-flex justify-content-between align-items-center mb-3 shadow-sm rounded" style="background-color: #e5ddfa;">
        <h6 class="mb-0 fw-bold text-white py-2 px-3">
            <i class="bi bi-person-vcard me-2"></i>پروفایل من
        </h6>
        <a href="{{ route('resident.profile.edit') }}" class="btn btn-sm add-btn px-3">
            <i class="bi bi-pencil-square me-1"></i>ویرایش اطلاعات
        </a>
    </div> --}}

    {{-- اطلاعات پروفایل --}}
    {{-- <div class="admin-table-card p-4 shadow-sm rounded border-0">
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
@endsection  --}}
@extends('layouts.app')

@section('content')
    <div class="container-fluid mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
                <div class="card card-primary card-outline mb-4 shadow-sm">
                    <div class="card-header text-white text-center" style="background-color: #4e3cb3">
                        <h3 class="card-title mb-0">پروفایل من</h3>
                    </div>
                    <div class="card-body text-center">
                        <div class="mb-3">
                            <img src="{{ asset('images/avatar.jpg') }}" class="rounded-circle shadow-sm" width="120"
                                height="120" alt="Profile Picture">
                        </div>

                        <table class="table table-bordered">
                            <tr>
                                <th>نام:</th>
                                <td>{{ $user->name }}</td>
                            </tr>
                            <tr>
                                <th>ایمیل:</th>
                                <td>{{$user->email }}</td>
                            </tr>
                            <tr>
                                <th>شماره تلفن:</th>
                                <td>{{ $user->phone }}</td>
                            </tr>
                             <tr>
                                <th> تاریخ عضویت:</th>
                                <td>{{ jdate($user->created_at)->format('Y/m/d') }}</td>
                            </tr>
                        </table>
                        <div class="card-footer bg-light d-flex justify-content-center">
                            <button type="submit" class="btn btn-lg" style="background-color: #4e3cb3">
                                <a class="text-white" href="{{ route('resident.profile.edit') }}">ویرایش پروفایل</a>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
