@extends('layouts.app')

@section('content')
    <div class="admin-header d-flex justify-content-between align-items-center mb-3 shadow-sm rounded flex-wrap">
        <h6 class="mb-0 fw-bold text-white text-center"><i class="bi bi-files"></i> لیست صورتحساب‌های کلی</h6>
        <div class="d-flex align-items-center gap-2 mb-3 text-center" style="flex-wrap: wrap;">
            <a href="{{ route('manager.invoices.create') }}" class="btn btn-sm add-btn d-flex align-items-center text-center">
                <i class="bi bi-plus-circle me-1"></i> افزودن صورتحساب کلی جدید
            </a>
        </div>
    </div>

    <div class="card search-filter-card mb-3">
        <div class="card-body">
            <form method="GET" action="{{ route('bulk_invoices.index') }}" class="row g-2 align-items-center text-center">
                <div class="col-auto">
                    <input type="text" name="search" value="{{ request('search') }}"
                        class="form-control form-control-sm w-auto search-input" placeholder="جستجو بر اساس عنوان یا مبلغ"
                        style="max-width: 200px;">
                </div>
                <div class="col-auto">
                    <select name="status" class="form-select form-select-sm search-input" style="max-width: 150px;">
                        <option value="">وضعیت</option>
                        <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>تایید شده</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>در انتظار تایید
                        </option>
                    </select>
                </div>
                <div class="col-auto">
                    <button type="submit" class="btn btn-sm btn-outline-primary filter-btn">اعمال فیلتر</button>
                    <a href="{{ route('bulk_invoices.index') }}" class="btn btn-sm btn-outline-secondary filter-btn">حذف
                        فیلتر</a>
                </div>
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
                                <span
                                    class="badge bg-{{ $bulkInvoice->status === 'approved' ? 'success' : 'warning' }} text-center">
                                    {{ $bulkInvoice->status === 'approved' ? 'تایید شده' : 'در انتظار تایید' }}
                                </span>
                            </td>
                            <td>
                                <div class="d-flex justify-content-center gap-1">
                                    @if ($bulkInvoice->status !== 'approved')
                                        <form action="{{ route('bulk_invoices.approve', $bulkInvoice->id) }}"
                                            method="POST"
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




                                            @if ($bulkInvoice->status !== 'approved')
                                        <form action="{{ route('manager.bulk_invoices.destroy', $bulkInvoice->id) }}"
                                            method="POST"
                                            onsubmit="return confirm('آیا مطمئن هستید می‌خواهید این صورتحساب کلی را حذف کنید؟  ');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger" title="حذف">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    @else
                                        <button class="btn btn-sm btn-outline-danger" disabled title=" تایید شده و قابل حذف نیست.">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    @endif



















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
    <div class="mt-3 d-flex justify-content-center">
        {{ $bulkInvoices->withQueryString()->links() }}
    </div>
@endsection
