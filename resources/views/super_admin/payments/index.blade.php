@extends('layouts.app')

@section('content')
    <div class="admin-header d-flex justify-content-between align-items-center mb-3 shadow-sm rounded flex-wrap">
        <h6 class="mb-0 fw-bold text-white text-center"><i class="bi bi-receipt"></i> لیست پرداخت‌ها</h6>
    </div>

    <div class="card search-filter-card mb-3">
        <div class="card-body">
            <form method="GET" action="{{ route('superadmin.payments.index') }}"
                class="d-flex flex-wrap align-items-center gap-2 text-center">
                <input type="text" name="search" value="{{ request('search') }}"
                    class="form-control form-control-sm w-auto search-input" placeholder="نام ساکن یا عنوان صورتحساب"
                    style="max-width: 200px;">
                <button type="submit" class="btn btn-sm btn-outline-primary filter-btn">جستجو</button>
                @if (request()->has('search') && request('search') != '')
                    <a href="{{ route('superadmin.payments.index') }}" class="btn btn-sm btn-outline-secondary filter-btn">حذف</a>
                @endif
            </form>
        </div>
    </div>

    <div class="card admin-table-card">
        <div class="card-body table-responsive">
            @if (session('success'))
                <div class="alert alert-success text-center">{{ session('success') }}</div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger text-center">{{ session('error') }}</div>
            @endif

            <table class="table table-bordered table-striped align-middle text-center table-units">
                <thead>
                    <tr>
                        <th>ردیف</th>
                        <th>عنوان صورتحساب</th>
                        <th>مبلغ (تومان)</th>
                        <th>تاریخ پرداخت</th>
                        <th>عملیات</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($payments as $index => $payment)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $payment->invoice->title ?? '-' }}</td>
                            <td>{{ number_format($payment->amount) }}</td>
                            <td>{{ jdate($payment->paid_at)->format('Y/m/d') }}</td>
                            <td>
                                <div class="d-flex justify-content-center gap-1">
                                    <a href="{{ route('superadmin.payments.show', $payment->id) }}"
                                        class="btn btn-sm btn-outline-primary" title="نمایش">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-3">هیچ پرداختی ثبت نشده است.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
