@extends('layouts.app')

@section('content')
    <div class="admin-header d-flex justify-content-between align-items-center mb-3 shadow-sm rounded flex-wrap">
        <h6 class="mb-0 fw-bold text-dark"><i class="bi bi-receipt"></i> لیست صورتحساب‌های من</h6>
    </div>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @forelse($invoices as $unitItem)
        <div class="card admin-table-card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center bg-light">
                <span>
                    <i class="bi bi-house-door text-primary me-1"></i>
                    واحد: {{ $unitItem['unit']->name }}
                </span>
                <span class="badge bg-{{ $unitItem['role'] === 'owner' ? 'info' : 'secondary' }}">
                    نقش شما: {{ $unitItem['role'] === 'owner' ? 'مالک' : 'ساکن' }}
                </span>
            </div>

            <div class="card-body table-responsive p-0">
                <table class="table table-bordered table-striped align-middle small m-0">
                    <thead>
                        <tr>
                            <th>ردیف</th>
                            <th>عنوان</th>
                            <th>مبلغ</th>
                            <th>تاریخ سررسید</th>
                            <th>نوع</th>
                            <th>وضعیت</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($unitItem['invoices'] as $index => $invoice)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $invoice->title ?? '-' }}</td>
                                <td>{{ number_format($invoice->amount) }} تومان</td>
                                <td>{{ jdate($invoice->due_date)->format('Y/m/d') }}</td>
                                <td>
                                    <span class="badge bg-{{ $invoice->type === 'fixed' ? 'dark' : 'secondary' }}">
                                        {{ $invoice->type === 'fixed' ? 'ثابت' : 'جاری' }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-{{ $invoice->status === 'paid' ? 'success' : 'warning' }}">
                                        {{ $invoice->status === 'paid' ? 'پرداخت شده' : 'پرداخت نشده' }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center">هیچ صورتحسابی برای این واحد وجود ندارد.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    @empty
        <div class="alert alert-info text-center">شما در هیچ واحدی ثبت نشده‌اید.</div>
    @endforelse
@endsection
