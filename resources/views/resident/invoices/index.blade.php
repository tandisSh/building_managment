@extends('layouts.app')

@section('content')
    <div class="admin-header d-flex justify-content-between align-items-center mb-3 shadow-sm rounded flex-wrap">
        <h6 class="mb-0 fw-bold text-white">
            <i class="bi bi-receipt"></i> لیست صورتحساب‌ها
        </h6>
    </div>


    {{-- کادر فیلترها و جستجو --}}
    <div class="card search-filter-card mb-3">
        <div class="card-body">
            <form method="GET" action="{{ route('resident.invoices.index') }}"
                class="row g-2 align-items-center text-center">
                <div class="col-auto">
                    <input type="text" name="title" value="{{ request('title') }}"
                        class="form-control form-control-sm w-auto search-input" placeholder="  عنوان صورتحساب.."
                        style="max-width: 200px;">
                </div>
                <div class="col-auto">
                    <select name="status" class="form-select form-select-sm search-input" style="max-width: 150px;">
                        <option value="">وضعیت پرداخت</option>
                        <option value="paid" {{ request('status') === 'paid' ? 'selected' : '' }}>پرداخت‌شده</option>
                        <option value="unpaid" {{ request('status') === 'unpaid' ? 'selected' : '' }}>پرداخت‌نشده</option>
                    </select>
                </div>

                <div class="col-auto">
                    <button type="submit" class="btn btn-sm btn-outline-primary filter-btn">اعمال فیلتر</button>
                    <a href="{{ route('resident.invoices.index') }}" class="btn btn-sm btn-outline-secondary filter-btn">حذف
                        فیلتر</a>
                </div>
            </form>
        </div>
    </div>

    {{-- لیست صورتحساب‌ها --}}
    @foreach ($invoices as $group)
        <div class="card admin-table-card mb-4">
            <div class="card-header bg-light d-flex justify-content-between align-items-center">
                <span><i class="bi bi-house-door text-primary me-1"></i> واحد {{ $group['unit']->unit_number }}</span>
                <span class="text-muted small">{{ $group['role'] === 'owner' ? 'مالک' : 'ساکن' }}</span>
            </div>

            <div class="card-body table-responsive p-0">
                <table class="table table-bordered table-striped align-middle small mb-0">
                    <thead class="table-secondary">
                        <tr class="text-center">
                            <th>ردیف</th>
                            <th>عنوان</th>
                            <th>مبلغ</th>
                            <th>وضعیت</th>
                            <th>عملیات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($group['invoices'] as $index => $invoice)
                            <tr class="text-center">
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $invoice->title }}</td>
                                <td>{{ number_format($invoice->amount) }} تومان</td>
                                <td>
                                    @if ($invoice->status === 'paid')
                                        <span class="badge bg-success">پرداخت شده</span>
                                    @else
                                        <span class="badge bg-danger">پرداخت نشده</span>
                                    @endif
                                </td>
                                <td>
                                    @if ($invoice->status !== 'paid')
                                        <form method="GET"
                                            action="{{ route('resident.payment.fake.form.single', $invoice) }}"
                                            class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-outline-warning" title="پرداخت">
                                                <i class="bi bi-credit-card"></i>
                                            </button>
                                        </form>
                                    @endif
                                    @if ($invoice->status == 'paid')
                                        <button class="btn btn-sm btn-outline-warning" disabled title="پرداخت">
                                            <i class="bi bi-credit-card"></i>
                                        </button>
                                    @endif
                                    <a href="{{ route('resident.invoices.show', $invoice->id) }}"
                                        class="btn btn-sm btn-outline-primary" title="نمایش">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endforeach
@endsection
