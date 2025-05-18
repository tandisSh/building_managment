@extends('layouts.app')

@section('content')
    <div class="admin-header d-flex justify-content-between align-items-center mb-3 shadow-sm rounded flex-wrap">
        <h6 class="mb-0 fw-bold text-dark"><i class="bi bi-files"></i> لیست صورتحساب‌های کلی</h6>

        <div class="tools-box">
            <input type="text" class="form-control form-control-sm search-input" placeholder="جستجو..." />
            <button class="btn filter-btn">فیلتر</button>
            <a href="{{ route('manager.invoices.create') }}" class="btn add-btn">
                <i class="bi bi-plus-circle me-1"></i> افزودن صورتحساب کلی جدید
            </a>
        </div>
    </div>

    <div class="card admin-table-card">
        <div class="card-body table-responsive">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            <table class="table table-bordered table-striped align-middle small table-units">
                <thead>
                    <tr>
                        <th>ردیف</th>
                        <th>عنوان</th>
                        <th>مبلغ کل (تومان)</th>
                        <th>تاریخ سررسید</th>
                        <th>وضعیت</th>
                        <th class="text-center">عملیات</th>
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
                            <td class="text-center">
                                <div class="d-flex justify-content-center gap-1">
                                    @if($bulkInvoice->status !== 'approved')
                                        <form action="{{ route('bulk_invoices.approve', $bulkInvoice->id) }}" method="POST"
                                              onsubmit="return confirm('آیا مطمئن هستید می‌خواهید این صورتحساب کلی را تایید و ثبت کنید؟');">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-success" title="تایید">
                                                <i class="bi bi-check-circle"></i>
                                            </button>
                                        </form>
                                    @else
                                        <button class="btn btn-sm btn-secondary" disabled title="تایید شده">
                                            <i class="bi bi-check2-all"></i>
                                        </button>
                                    @endif

                                    <a href="{{ route('manager.bulk_invoices.show', $bulkInvoice->id) }}"
                                       class="btn btn-sm btn-outline-primary" title="نمایش">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('manager.bulk_invoices.edit', $bulkInvoice->id) }}"
                                       class="btn btn-sm btn-outline-warning" title="ویرایش">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-3">هیچ صورتحساب کلی ثبت نشده است.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
