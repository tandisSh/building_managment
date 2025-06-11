@extends('layouts.app')

@section('content')
    <div class="admin-header d-flex justify-content-between align-items-center mb-3 shadow-sm rounded flex-wrap">
        <h6 class="mb-0 fw-bold text-white"><i class="bi bi-wallet2"></i> پرداخت گروهی صورتحساب‌ها</h6>
        <a href="{{ route('resident.invoices.index') }}" class="btn btn-outline-secondary btn-sm">
            بازگشت
        </a>
    </div>

    <div class="card admin-table-card">
        <div class="card-body">
            <form method="POST" action="{{ route('resident.payment.fake.form.multiple') }}" id="bulk-payment-form">
                @csrf

                <div class="alert alert-info d-flex justify-content-between align-items-center">
                    <div>
                        صورتحساب‌های انتخاب‌شده:
                        <span id="selected-count" class="fw-bold text-primary">0</span> عدد -
                        مجموع:
                        <span id="selected-total" class="fw-bold text-success">0</span> تومان
                    </div>
                    <button type="submit" class="btn btn-success btn-sm" id="submit-payment" disabled>
                        پرداخت انتخابی
                    </button>
                </div>

                @forelse($invoices as $group)
                    <div class="card my-3 shadow-sm">
                        <div class="card-header bg-light fw-bold">
                            واحد: {{ $group['unit']->unit_number }} ({{ $group['role'] }})
                        </div>
                        <div class="card-body p-0">
                            <table class="table table-bordered table-hover mb-0 small">
                                <thead class="table-light">
                                    <tr>
                                        <th class="text-center">انتخاب</th>
                                        <th>عنوان</th>
                                        <th>مبلغ (تومان)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($group['invoices'] as $invoice)
                                        @if ($invoice->status === 'unpaid')
                                            <tr>
                                                <td class="text-center">
                                                    <input type="checkbox" name="invoice_ids[]" value="{{ $invoice->id }}"
                                                        class="form-check-input invoice-checkbox"
                                                        data-amount="{{ $invoice->amount }}">
                                                </td>
                                                <td>{{ $invoice->title }}</td>
                                                <td>{{ number_format($invoice->amount) }}</td>
                                            </tr>
                                        @endif
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @empty
                    <div class="alert alert-warning text-center">
                        هیچ صورتحساب پرداخت‌نشده‌ای یافت نشد.
                    </div>
                @endforelse
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const totalSpan = document.getElementById('selected-total');
            const countSpan = document.getElementById('selected-count');
            const submitButton = document.getElementById('submit-payment');

            function updateSummary() {
                let total = 0;
                let count = 0;
                const checkboxes = document.querySelectorAll('.invoice-checkbox');

                checkboxes.forEach(cb => {
                    if (cb.checked) {
                        count++;
                        const amount = Number(cb.dataset.amount || '0');
                        total += amount;
                    }
                });

                countSpan.textContent = count;
                totalSpan.textContent = total.toLocaleString('fa-IR');
                submitButton.disabled = count === 0; // غیرفعال کردن دکمه اگه هیچ‌کدوم انتخاب نشده
            }

            // گوش دادن به تغییرات چک‌باکس‌ها
            document.addEventListener('change', function(e) {
                if (e.target.classList.contains('invoice-checkbox')) {
                    updateSummary();
                }
            });

            // اجرای اولیه هنگام بارگذاری
            updateSummary();
        });
    </script>
@endpush
