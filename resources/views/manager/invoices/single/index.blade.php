@extends('layouts.app')

@section('content')
    {{-- هدر بالا: عنوان + دکمه افزودن --}}
    <div class="admin-header d-flex justify-content-between align-items-center mb-3 shadow-sm rounded flex-wrap">
        <h6 class="mb-0 fw-bold text-white text-center">
            <i class="bi bi-receipt"></i> لیست صورتحساب‌ها
        </h6>
        <div class="d-flex align-items-center gap-2 mb-3 text-center" style="flex-wrap: wrap;">
            <div class="dropdown">
                <button class="btn add-btn dropdown-toggle pe-3 text-center" type="button" data-bs-toggle="dropdown"
                    aria-expanded="false">
                    <i class="bi bi-plus-circle me-1"></i> افزودن صورتحساب
                    <i class="bi bi-chevron-down dropdown-arrow ms-1"></i>
                </button>
                <ul class="dropdown-menu dropdown-menu-end shadow border-0 text-center">
                    <li>
                        <a class="dropdown-item py-2 d-flex align-items-center justify-content-center"
                            href="{{ route('manager.invoices.create') }}">
                            <i class="bi bi-building me-2 text-primary"></i>
                            <span>صورتحساب کل ساختمان</span>
                        </a>
                    </li>
                    <li>
                        <hr class="dropdown-divider my-1">
                    </li>
                    <li>
                        <a class="dropdown-item py-2 d-flex align-items-center justify-content-center"
                            href="{{ route('invoices.single.create') }}">
                            <i class="bi bi-house-door me-2 text-success"></i>
                            <span>صورتحساب واحد خاص</span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    {{-- کادر فیلترها و جستجو --}}
    <div class="card search-filter-card mb-3">
        <div class="card-body">
            <form method="GET" action="{{ route('manager.invoices.index') }}"
                class="row g-2 align-items-center text-center">
                <div class="col-auto">
                    <input type="text" name="search" value="{{ request('search') }}"
                        class="form-control form-control-sm w-auto search-input" placeholder="نام ساکن یا شماره واحد"
                        style="max-width: 200px;">
                </div>
                <div class="col-auto">
                    <select name="status" class="form-select form-select-sm search-input" style="max-width: 150px;">
                        <option value="">وضعیت پرداخت</option>
                        <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>پرداخت‌شده</option>
                        <option value="unpaid" {{ request('status') == 'unpaid' ? 'selected' : '' }}>پرداخت‌نشده</option>
                    </select>
                </div>
                <div class="col-auto">
                    <select name="type" class="form-select form-select-sm search-input" style="max-width: 150px;">
                        <option value="">نوع صورتحساب</option>
                        <option value="current" {{ request('type') == 'current' ? 'selected' : '' }}>جاری</option>
                        <option value="fixed" {{ request('type') == 'fixed' ? 'selected' : '' }}>ثابت</option>
                    </select>
                </div>
                <div class="col-auto">
                    <select name="unit_id" class="form-select form-select-sm search-input" style="max-width: 150px;">
                        <option value="">همه واحدها</option>
                        @foreach ($units as $unit)
                            <option value="{{ $unit->id }}" {{ request('unit_id') == $unit->id ? 'selected' : '' }}>
                                واحد {{ $unit->unit_number }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-auto">
                    <button type="submit" class="btn btn-sm btn-outline-primary filter-btn">اعمال فیلتر</button>
                    <a href="{{ route('manager.invoices.index') }}" class="btn btn-sm btn-outline-secondary filter-btn">حذف
                        فیلتر</a>
                </div>
            </form>
        </div>
    </div>

    {{-- جدول صورتحساب‌ها --}}
    <div class="card admin-table-card">
        <div class="card-body table-responsive">
            @if (session('success'))
                <div class="alert alert-success text-center">{{ session('success') }}</div>
            @endif

            <table class="table table-bordered table-striped align-middle text-center table-units">
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
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $invoice->unit->unit_number ?? '-' }}</td>
                            <td>{{ number_format($invoice->amount) }} تومان</td>
                            <td>{{ jdate($invoice->due_date)->format('Y/m/d') }}</td>
                            <td>
                                <span
                                    class="badge bg-{{ $invoice->status === 'paid' ? 'success' : 'warning' }} text-center">
                                    {{ $invoice->status === 'paid' ? 'پرداخت شده' : 'پرداخت نشده' }}
                                </span>
                            </td>
                            <td>
                                <div class="d-flex justify-content-center gap-2">
                                    <a href="{{ route('manager.invoices.show', $invoice->id) }}"
                                        class="btn btn-sm btn-outline-primary" title="نمایش">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('manager.single-invoices.edit', $invoice->id) }}"
                                        class="btn btn-sm btn-outline-warning" title="ویرایش">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">هیچ صورتحسابی ثبت نشده است.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="mt-3 d-flex justify-content-center">
        {{ $invoices->withQueryString()->links('vendor.pagination.default') }}
    </div>
@endsection
