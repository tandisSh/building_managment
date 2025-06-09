@extends('layouts.app')

@section('content')
    <div class="admin-header d-flex justify-content-between align-items-center mb-3 shadow-sm rounded flex-wrap">
        <h6 class="mb-0 fw-bold text-dark text-center"><i class="bi bi-receipt"></i> گزارش صورتحساب‌ها -
            {{ $building->name ?? 'نامشخص' }}</h6>
    </div>

    <div class="card search-filter-card mb-3">
        <div class="card-body">
            <form method="GET" class="row g-2 align-items-center text-center">
                <div class="col-auto">
                    <input type="date" name="date_from" class="form-control form-control-sm search-input"
                        value="{{ $filters['date_from'] ?? '' }}" placeholder="از تاریخ" style="max-width: 200px;">
                </div>
                <div class="col-auto">
                    <input type="date" name="date_to" class="form-control form-control-sm search-input"
                        value="{{ $filters['date_to'] ?? '' }}" placeholder="تا تاریخ" style="max-width: 200px;">
                </div>
                <div class="col-auto">
                    <select name="status" class="form-select form-select-sm search-input" style="max-width: 200px;">
                        <option value="">همه وضعیت‌ها</option>
                        <option value="paid" @selected(($filters['status'] ?? '') === 'paid')>پرداخت شده</option>
                        <option value="unpaid" @selected(($filters['status'] ?? '') === 'unpaid')>پرداخت نشده</option>
                    </select>
                </div>
                <div class="col-auto">
                    <select name="type" class="form-select form-select-sm search-input" style="max-width: 200px;">
                        <option value="">همه نوع‌ها</option>
                        <option value="fixed" @selected(($filters['type'] ?? '') === 'fixed')>ثابت</option>
                        <option value="current" @selected(($filters['type'] ?? '') === 'current')>جاری</option>
                    </select>
                </div>
                <div class="col-auto">
                    <select name="unit_id" class="form-select form-select-sm search-input" style="max-width: 200px;">
                        <option value="">همه واحدها</option>
                        @foreach ($units as $unit)
                            <option value="{{ $unit->id }}" @selected(($filters['unit_id'] ?? '') == $unit->id)>
                                {{ $unit->unit_number ?? $unit->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-auto">
                    <button type="submit" class="btn btn-sm btn-outline-primary filter-btn">فیلتر</button>
                    <a href="{{ route('reports.invoices') }}" class="btn btn-sm btn-outline-secondary filter-btn">حذف
                        فیلتر</a>
                </div>
            </form>
        </div>
    </div>

    <div class="mb-3 d-flex justify-content-between align-items-center text-center">
        <div>
            <strong>مجموع مبلغ این صفحه:</strong> {{ number_format($totalAmount) }} تومان
        </div>
        <div>
            <a href="{{ route('reports.payments.print', request()->query()) }}" target="_blank"
                class="btn btn-info btn-sm">چاپ گزارش</a>
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

            @if ($invoices->count() > 0)
                <table class="table table-bordered table-striped align-middle text-center table-units">
                    <thead>
                        <tr>
                            <th>واحد</th>
                            <th>مبلغ</th>
                            <th>تاریخ سررسید</th>
                            <th>وضعیت</th>
                            <th>نوع</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($invoices as $invoice)
                            <tr>
                                <td>{{ $invoice->unit->unit_number ?? $invoice->unit->name }}</td>
                                <td>{{ number_format($invoice->amount) }}</td>
                                <td>{{ jdate($invoice->due_date)->format('Y/m/d') }}</td>
                                <td>{{ $invoice->status == 'paid' ? 'پرداخت شده' : 'پرداخت نشده' }}</td>
                                <td>{{ $invoice->type == 'fixed' ? 'ثابت' : 'جاری' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="mt-3 text-center">
                    {{ $invoices->withQueryString()->links() }}
                </div>
            @else
                <div class="alert alert-info text-center">
                    هیچ صورتحسابی یافت نشد.
                </div>
            @endif
        </div>
    </div>
@endsection
