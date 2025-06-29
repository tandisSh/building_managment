@extends('layouts.app')

@section('content')
    <div class="admin-header d-flex justify-content-between align-items-center mb-3 shadow-sm rounded flex-wrap">
        <h6 class="mb-0 fw-bold text-white">
            <i class="bi bi-house-door me-1"></i> داشبورد ساکن
        </h6>
        <div class="text-white">
            <small>خوش آمدید {{ auth()->user()->name }}</small>
        </div>
    </div>
{{-- @if($unit)
    {{ $unit->name }}
@endif --}}

    {{-- اطلاعیه‌ها --}}
    {{-- <div class="card admin-table-card mb-4">
        <div class="card-header bg-light fw-bold">
            <i class="bi bi-bell-fill me-1"></i> اطلاعیه‌های جدید
        </div>
        <div class="card-body p-3">
            @if($notices->isEmpty())
                <div class="alert alert-info">
                    <i class="bi bi-info-circle me-2"></i> اطلاعیه‌ای ثبت نشده است.
                </div>
            @else
                <ul class="list-group small">
                    @foreach($notices as $notice)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <strong>{{ $notice->title }}</strong>
                            </div>
                            <span class="text-muted">{{ \Morilog\Jalali\Jalalian::fromDateTime($notice->created_at)->format('Y/m/d') }}</span>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>
    </div> --}}

    <!-- کارت‌های آمار کلی -->
    <div class="row mb-4">
        @php
            $user = auth()->user();
            $userUnits = $user->unitUsers()->with('unit')->get();

            $totalInvoices = 0;
            $paidInvoices = 0;
            $unpaidInvoices = 0;
            $totalAmount = 0;

            foreach ($userUnits as $userUnit) {
                $unitId = $userUnit->unit_id;
                $userRole = $userUnit->role;
                $invoiceType = ($userRole === 'resident') ? 'current' : 'fixed';

                // کل صورتحساب‌ها
                $totalInvoices += \App\Models\Invoice::where('unit_id', $unitId)
                    ->where('type', $invoiceType)
                    ->count();

                // صورتحساب‌های پرداخت شده
                $paidInvoices += \App\Models\Invoice::where('unit_id', $unitId)
                    ->where('type', $invoiceType)
                    ->where('status', 'paid')
                    ->count();

                // صورتحساب‌های پرداخت نشده
                $unpaidInvoices += \App\Models\Invoice::where('unit_id', $unitId)
                    ->where('type', $invoiceType)
                    ->where('status', 'unpaid')
                    ->count();

                // مجموع بدهی
                $totalAmount += \App\Models\Invoice::where('unit_id', $unitId)
                    ->where('type', $invoiceType)
                    ->where('status', 'unpaid')
                    ->sum('amount');
            }
        @endphp

        <div class="col-md-3 mb-3">
            <div class="card text-center bg-purple-300 text-dark shadow rounded">
                <div class="card-body">
                    <i class="bi bi-receipt fs-2"></i>
                    <div class="mt-2">کل صورتحساب‌ها</div>
                    <h4 class="fw-bold">{{ $totalInvoices }}</h4>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="card text-center bg-purple-500 text-dark shadow rounded">
                <div class="card-body">
                    <i class="bi bi-check-circle fs-2"></i>
                    <div class="mt-2">پرداخت شده</div>
                    <h4 class="fw-bold">{{ $paidInvoices }}</h4>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="card text-center bg-purple-700 text-dark shadow rounded">
                <div class="card-body">
                    <i class="bi bi-exclamation-triangle fs-2"></i>
                    <div class="mt-2">پرداخت نشده</div>
                    <h4 class="fw-bold">{{ $unpaidInvoices }}</h4>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="card text-center bg-purple-900 text-dark shadow rounded">
                <div class="card-body">
                    <i class="bi bi-currency-exchange fs-2"></i>
                    <div class="mt-2">مجموع بدهی</div>
                    <h4 class="fw-bold">{{ number_format($totalAmount) }} تومان</h4>
                </div>
            </div>
        </div>
    </div>

    <!-- اطلاعات واحدها -->
    @if($userUnits->count() > 0)
        <div class="row mb-4">
            <div class="col-12">
                <div class="card admin-table-card">
                    <div class="card-header bg-light fw-bold">
                        <i class="bi bi-house me-1"></i> واحدهای شما
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped align-middle small mb-0">
                                <thead class="table-secondary">
                                    <tr>
                                        <th>واحد</th>
                                        <th>ساختمان</th>
                                        <th>نوع</th>
                                        <th>وضعیت</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($userUnits as $unitUser)
                                        <tr>
                                            <td>
                                                <strong>{{ $unitUser->unit->unit_number ?? 'نامشخص' }}</strong>
                                            </td>
                                            <td>{{ $unitUser->unit->building->name ?? 'نامشخص' }}</td>
                                            <td>
                                                @if($unitUser->role === 'owner')
                                                    <span class="badge bg-primary">مالک</span>
                                                @else
                                                    <span class="badge bg-success">ساکن</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($unitUser->status === 'active')
                                                    <span class="badge bg-success">فعال</span>
                                                @else
                                                    <span class="badge bg-warning text-dark">غیرفعال</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- صورتحساب‌های پرداخت‌نشده -->
    <div class="row">
        <div class="col-md-8">
            <div class="card admin-table-card">
                <div class="card-header bg-light fw-bold d-flex justify-content-between align-items-center">
                    <span><i class="bi bi-receipt-cutoff me-1"></i> صورتحساب‌های پرداخت‌نشده</span>
                    @if($unpaidInvoices > 0)
                        <a href="{{ route('resident.invoices.unpaid') }}" class="btn btn-sm btn-outline-primary">
                            <i class="bi bi-credit-card"></i> پرداخت گروهی
                        </a>
                    @endif
                </div>
                <div class="card-body p-3">
                    @if($Invoices->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped align-middle small mb-0">
                                <thead class="table-secondary">
                                    <tr>
                                        <th>#</th>
                                        <th>عنوان</th>
                                        <th>مبلغ</th>
                                        <th>مهلت پرداخت</th>
                                        <th>وضعیت</th>
                                        <th>عملیات</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($Invoices as $index => $invoice)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ $invoice->title }}</td>
                                            <td>{{ number_format($invoice->amount) }} تومان</td>
                                            <td>
                                                @if($invoice->due_date)
                                                    {{ \Morilog\Jalali\Jalalian::fromDateTime($invoice->due_date)->format('Y/m/d') }}
                                                    @if($invoice->due_date < now())
                                                        <span class="badge bg-danger ms-1">معوق</span>
                                                    @endif
                                                @else
                                                    <span class="text-muted">تعیین نشده</span>
                                                @endif
                                            </td>
                                            <td>
                                                <span class="badge bg-danger py-1 px-3 rounded-pill">پرداخت نشده</span>
                                            </td>
                                            <td>
                                                <a href="{{ route('resident.payment.fake.form.single', $invoice->id) }}"
                                                   class="btn btn-sm btn-outline-warning">
                                                    <i class="bi bi-credit-card"></i> پرداخت
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @if($unpaidInvoices > 5)
                            <div class="text-center mt-3">
                                <a href="{{ route('resident.invoices.index') }}" class="btn btn-outline-primary">
                                    مشاهده همه صورتحساب‌ها
                                </a>
                            </div>
                        @endif
                    @else
                        <div class="alert alert-success text-center">
                            <i class="bi bi-check-circle me-2"></i>
                            صورتحساب پرداخت‌نشده‌ای ندارید.
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- پنل کناری -->
        <div class="col-md-4">
            <!-- آخرین پرداخت‌ها -->
            <div class="card admin-table-card mb-3">
                <div class="card-header bg-light fw-bold">
                    <i class="bi bi-credit-card me-1"></i> آخرین پرداخت‌ها
                </div>
                <div class="card-body p-3">
                    @php
                        $recentPayments = \App\Models\Payment::where('user_id', $user->id)
                            ->with('invoice.unit.building')
                            ->latest('paid_at')
                            ->take(5)
                            ->get();
                    @endphp

                    @if($recentPayments->count() > 0)
                        <div class="list-group list-group-flush">
                            @foreach($recentPayments as $payment)
                                <div class="list-group-item d-flex justify-content-between align-items-center p-2">
                                    <div>
                                        <small class="text-muted">{{ $payment->invoice->title ?? 'نامشخص' }}</small>
                                        <br>
                                        <small>{{ number_format($payment->amount) }} تومان</small>
                                    </div>
                                    <small class="text-muted">
                                        {{ \Morilog\Jalali\Jalalian::fromDateTime($payment->paid_at)->format('m/d') }}
                                    </small>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center text-muted">
                            <i class="bi bi-info-circle"></i>
                            <small>هیچ پرداختی یافت نشد</small>
                        </div>
                    @endif
                </div>
            </div>

            <!-- آخرین درخواست‌های تعمیر -->
            <div class="card admin-table-card">
                <div class="card-header bg-light fw-bold">
                    <i class="bi bi-tools me-1"></i> آخرین درخواست‌های تعمیر
                </div>
                <div class="card-body p-3">
                    @php
                        $recentRequests = \App\Models\RepairRequest::where('user_id', $user->id)
                            ->latest('created_at')
                            ->take(5)
                            ->get();
                    @endphp

                    @if($recentRequests->count() > 0)
                        <div class="list-group list-group-flush">
                            @foreach($recentRequests as $request)
                                <div class="list-group-item d-flex justify-content-between align-items-center p-2">
                                    <div>
                                        <small class="text-muted">{{ Str::limit($request->title, 20) }}</small>
                                        <br>
                                        @php
                                            $statusClass = match($request->status) {
                                                'pending' => 'bg-warning text-dark',
                                                'in_progress' => 'bg-info',
                                                'completed' => 'bg-success',
                                                'cancelled' => 'bg-danger',
                                                default => 'bg-secondary'
                                            };
                                            $statusText = match($request->status) {
                                                'pending' => 'در انتظار',
                                                'in_progress' => 'در حال انجام',
                                                'completed' => 'تکمیل شده',
                                                'cancelled' => 'لغو شده',
                                                default => 'نامشخص'
                                            };
                                        @endphp
                                        <span class="badge {{ $statusClass }} small">{{ $statusText }}</span>
                                    </div>
                                    <small class="text-muted">
                                        {{ \Morilog\Jalali\Jalalian::fromDateTime($request->created_at)->format('m/d') }}
                                    </small>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center text-muted">
                            <i class="bi bi-info-circle"></i>
                            <small>هیچ درخواستی یافت نشد</small>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
