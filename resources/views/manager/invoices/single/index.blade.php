@extends('layouts.app')

@section('content')
    <div class="admin-header d-flex justify-content-between align-items-center mb-3 shadow-sm rounded flex-wrap">
        <h6 class="mb-0 fw-bold text-dark"><i class="bi bi-receipt"></i> لیست صورتحساب‌ها</h6>

        <div class="tools-box">
            <input type="text" class="form-control form-control-sm search-input" placeholder="جستجو..." />
            <button class="btn filter-btn">فیلتر</button>
            <div class="dropdown">
                <button class="btn add-btn dropdown-toggle pe-3" type="button" data-bs-toggle="dropdown"
                    aria-expanded="false">
                    <i class="bi bi-plus-circle me-1"></i> افزودن صورتحساب
                    <i class="bi bi-chevron-down dropdown-arrow ms-1"></i>
                </button>
                <ul class="dropdown-menu dropdown-menu-end shadow border-0">
                    <li>
                        <a class="dropdown-item py-2 d-flex align-items-center"
                            href="{{ route('manager.invoices.create') }}">
                            <i class="bi bi-building me-2 text-primary"></i>
                            <span>صورتحساب کل ساختمان</span>
                        </a>
                    </li>
                    <li>
                        <hr class="dropdown-divider my-1">
                    </li>
                    <li>
                        <a class="dropdown-item py-2 d-flex align-items-center"
                            href="{{ route('invoices.single.create') }}">
                            <i class="bi bi-house-door me-2 text-success"></i>
                            <span>صورتحساب واحد خاص</span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <div class="card admin-table-card">
        <div class="card-body table-responsive">
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <table class="table table-bordered table-striped align-middle small table-units">
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
                            <td>{{ $invoice->unit_id }}</td>
                            <td>{{ number_format($invoice->amount) }} تومان</td>
                            <td>{{ jdate($invoice->due_date)->format('Y/m/d') }}</td>
                            <td>
                                <span class="badge bg-{{ $invoice->status === 'paid' ? 'success' : 'warning' }}">
                                    {{ $invoice->status === 'paid' ? 'پرداخت شده' : 'پرداخت نشده' }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('manager.invoices.show', $invoice->id) }}"
                                    class="btn btn-sm btn-outline-primary" title="نمایش">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('manager.single-invoices.edit', $invoice->id) }}"
                                    class="btn btn-sm btn-outline-warning" title="ویرایش">
                                    <i class="bi bi-pencil-square"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">هیچ صورتحسابی ثبت نشده است.</td>
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
