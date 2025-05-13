@extends('layouts.app')
@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5><i class="bi bi-receipt"></i> لیست صورتحساب‌ها</h5>
            <a href="{{ route('manager.invoices.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle"></i> افزودن صورتحساب
            </a>
        </div>
        <div class="card-body">
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>ردیف</th>
                        <th>واحد</th>
                        <th>مبلغ</th>
                        <th>تاریخ سررسید</th>
                        <th>وضعیت</th>
                        <th>عملیات</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($invoices as $index => $invoice)
                        <tr>
                            <td> {{ $index + 1 }}</td>
                            <td> {{ $invoice->unit_id }}</td>
                            <td>{{ number_format($invoice->total_amount) }} تومان</td>
                            <td>{{ jdate($invoice->due_date)->format('Y/m/d') }}</td>
                            <td>
                                <span class="badge bg-{{ $invoice->status === 'paid' ? 'success' : 'warning' }}">
                                    {{ $invoice->status === 'paid' ? 'پرداخت شده' : 'پرداخت نشده' }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('manager.invoices.show', $invoice->id) }}"
                                    class="btn btn-info btn-sm">نمایش</a>

                                <a href="{{ route('residents.edit', $invoice->id) }}"
                                    class="btn btn-warning btn-sm">ویرایش</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5">هیچ صورتحسابی ثبت نشده است.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            {{-- <div class="mt-3">
            {{ $invoices->links() }}
        </div> --}}
        </div>
    </div>
@endsection
