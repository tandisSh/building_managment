@extends('layouts.app')

@section('content')

    <div class="admin-header d-flex justify-content-between align-items-center mb-3 shadow-sm rounded flex-wrap">
        <h6 class="mb-0 fw-bold text-dark"><i class="bi bi-receipt"></i> لیست پرداخت‌ها</h6>
        <form method="GET" action="{{ route('payments.index') }}" class="d-flex align-items-center gap-2">
            <input type="text" name="search" value="{{ request('search') }}" class="form-control form-control-sm"
                placeholder="نام ساکن یا عنوان صورتحساب" style="min-width: 180px;">
            <button type="submit" class="btn btn-sm btn-outline-primary">جستجو</button>
            @if (request()->has('search') && request('search') != '')
                <a href="{{ route('payments.index') }}" class="btn btn-sm btn-outline-secondary">حذف </a>
            @endif
        </form>
    </div>


    <div class="card admin-table-card">
        <div class="card-body table-responsive">
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            <table class="table table-bordered table-striped align-middle small table-payments">
                <thead>
                    <tr>
                        <th>ردیف</th>
                        <th>عنوان صورتحساب</th>
                        <th>مبلغ (تومان)</th>
                        <th>تاریخ پرداخت</th>
                        <th class="text-center">عملیات</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($payments as $index => $payment)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $payment->invoice->title ?? '-' }}</td>
                            <td>{{ number_format($payment->amount) }}</td>
                            <td>{{ jdate($payment->paid_at)->format('Y/m/d') }}</td>
                            <td class="text-center">
                                <a href="{{ route('payments.show', $payment->id) }}" class="btn btn-sm btn-outline-primary"
                                    title="نمایش">
                                    <i class="bi bi-eye"></i>
                                </a>
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
