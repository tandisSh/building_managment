@extends('layouts.app')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5><i class="bi bi-files"></i> لیست صورتحساب‌های کلی</h5>

        <a href="{{ route('manager.invoices.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> افزودن صورتحساب کلی جدید
        </a>
    </div>

    <div class="card-body">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ردیف</th>
                    <th>عنوان</th>
                    <th>مبلغ کل (تومان)</th>
                    <th>تاریخ سررسید</th>
                    <th>وضعیت</th>
                    <th>عملیات</th>
                </tr>
            </thead>
            <tbody>
                @forelse($bulkInvoices as $index => $bulkInvoice)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $bulkInvoice->title }}</td>
                        <td>{{ number_format($bulkInvoice->base_amount) }}</td>
                        <td>{{ jdate($bulkInvoice->due_date)->format('Y/m/d') }}</td>
                        <td>
                            <span class="badge bg-{{ $bulkInvoice->status === 'approved' ? 'success' : 'warning' }}">
                                {{ $bulkInvoice->status === 'approved' ? 'تایید شده' : 'در انتظار تایید' }}
                            </span>
                        </td>
                        <td>
                            @if($bulkInvoice->status !== 'approved')
                                <form action="{{ route('bulk_invoices.approve', $bulkInvoice->id) }}" method="POST" onsubmit="return confirm('آیا مطمئن هستید می‌خواهید این صورتحساب کلی را تایید و ثبت کنید؟');">
                                    @csrf
                                    <button type="submit" class="btn btn-success btn-sm">تایید و ثبت برای همه</button>
                                </form>
                            @else
                                <button class="btn btn-secondary btn-sm" disabled>تایید شده</button>
                            @endif

                            <a href='' class="btn btn-info btn-sm mt-1">نمایش</a>
                            <a href="{{ route('manager.bulk_invoices.edit', $bulkInvoice->id) }}" class="btn btn-warning btn-sm mt-1">ویرایش</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center">هیچ صورتحساب کلی ثبت نشده است.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
