@extends('layouts.app')

@section('content')
    <div class="admin-header d-flex justify-content-between align-items-center mb-3 shadow-sm rounded flex-wrap">
        <h6 class="mb-0 fw-bold text-white"><i class="bi bi-receipt"></i> لیست پرداخت ها</h6>

    </div>

    {{-- کادر فیلترها و جستجو --}}
    <div class="card search-filter-card mb-3">
        <div class="card-body">
            <form method="GET" action="{{ route('resident.payments.index') }}"
                class="row g-2 align-items-center text-center">
                <div class="col-auto">
                    <input type="text" name="search" value="{{ $search ?? '' }}"
                        class="form-control form-control-sm w-auto search-input" placeholder="  عنوان پرداخت.."
                        style="max-width: 200px;">
                </div>

                <div class="col-auto">
                    <button type="submit" class="btn btn-sm btn-outline-primary filter-btn">اعمال فیلتر</button>
                    <a href="{{ route('resident.payments.index') }}" class="btn btn-sm btn-outline-secondary filter-btn">حذف
                        فیلتر</a>
                </div>
            </form>
        </div>
    </div>

    <div class="card admin-table-card ">
        <div class="card-body table-responsive">
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <table class="table table-bordered table-striped align-middle  small table-units">
                <thead class="text-center">
                    <tr>
                        <th>ردیف</th>
                        <th>عنوان</th>
                        <th>مبلغ</th>
                        <th>تاریخ پرداخت</th>
                        <th>عملیات</th>
                    </tr>
                </thead>
                <tbody class="text-center">
                    @forelse($payments as $index => $payment)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $payment->invoice->title }}</td>
                            <td>{{ number_format($payment->amount) }} تومان</td>
                            <td>{{ jdate($payment->paid_at)->format('Y/m/d') }}</td>
                            <td>
                                <a href="{{ route('resident.payments.show', $payment->id) }}"
                                    class="btn btn-sm btn-outline-primary" title="نمایش">
                                    <i class="bi bi-eye"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">هیچ پرداختی ثبت نشده است.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
