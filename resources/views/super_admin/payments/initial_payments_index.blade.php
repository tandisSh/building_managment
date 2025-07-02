@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="card shadow-sm">
            <div class="card-header bg-light">
                <h5 class="mb-0">گزارش پرداخت‌های اولیه مدیران</h5>
            </div>
            <div class="card-body">
                @if ($payments->isEmpty())
                    <div class="alert alert-info text-center">
                        موردی برای نمایش وجود ندارد.
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-hover table-striped">
                            <thead class="table-light">
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">ساختمان</th>
                                    <th scope="col">مدیر ساختمان</th>
                                    <th scope="col">مبلغ (تومان)</th>
                                    <th scope="col">وضعیت</th>
                                    <th scope="col">شماره تراکنش</th>
                                    <th scope="col">تاریخ پرداخت</th>
                                    <th scope="col">تاریخ ایجاد</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($payments as $payment)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $payment->building->name ?? 'N/A' }}</td>
                                        <td>{{ $payment->building->manager->name ?? 'N/A' }}</td>
                                        <td>{{ number_format($payment->amount) }}</td>
                                        <td>
                                            @if ($payment->status === 'paid')
                                                <span class="badge bg-success">پرداخت موفق</span>
                                            @elseif ($payment->status === 'pending')
                                                <span class="badge bg-warning text-dark">در انتظار پرداخت</span>
                                            @else
                                                <span class="badge bg-danger">ناموفق</span>
                                            @endif
                                        </td>
                                        <td>{{ $payment->transaction_id ?? '-' }}</td>
                                        <td>{{ $payment->paid_at ? \Morilog\Jalali\Jalalian::fromCarbon(\Carbon\Carbon::parse($payment->paid_at))->format('Y/m/d H:i') : '-' }}</td>
                                        <td>{{ \Morilog\Jalali\Jalalian::fromCarbon(\Carbon\Carbon::parse($payment->created_at))->format('Y/m/d H:i') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-3">
                        {{ $payments->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection 