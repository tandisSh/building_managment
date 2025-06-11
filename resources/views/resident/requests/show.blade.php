@extends('layouts.app')

@section('content')
    <div class="admin-header d-flex justify-content-between align-items-center mb-3 shadow-sm rounded flex-wrap">
        <h6 class="mb-0 fw-bold text-white"><i class="bi bi-eye"></i> جزئیات درخواست تعمیر</h6>
        <a href="{{ route('resident.requests.index') }}" class="btn btn-sm btn-secondary">بازگشت</a>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="mb-3">
                <strong>عنوان:</strong>
                <span class="text-muted">{{ $request->title }}</span>
            </div>

            <div class="mb-3">
                <strong>توضیحات:</strong>
                <p class="text-muted">{{ $request->description }}</p>
            </div>

            <div class="mb-3">
                <strong>وضعیت:</strong>
                <span class="badge bg-{{ $request->status === 'done' ? 'success' : ($request->status === 'in_progress' ? 'info' : 'warning') }}">
                    {{ $request->status === 'pending'
                        ? 'در انتظار بررسی'
                        : ($request->status === 'in_progress'
                            ? 'در حال انجام'
                            : 'انجام‌شده') }}
                </span>
            </div>

            <div class="mb-3">
                <strong>تاریخ ثبت:</strong>
                <span class="text-muted">{{ jdate($request->created_at)->format('Y/m/d') }}</span>
            </div>
        </div>
    </div>
@endsection
