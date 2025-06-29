@extends('layouts.app')

@section('content')
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="bi bi-credit-card"></i> 
                            پرداخت {{ isset($invoiceId) ? 'تک صورتحساب' : 'گروهی' }}
                        </h5>
                    </div>
                    <div class="card-body">
                        @if (session('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                        @endif
                        @if (session('error'))
                            <div class="alert alert-danger">
                                {{ session('error') }}
                            </div>
                        @endif

                        @if(isset($invoiceIds) && !empty($invoiceIds))
                            <div class="alert alert-info">
                                <strong>تعداد صورتحساب‌های انتخاب شده:</strong> {{ count($invoiceIds) }} عدد
                            </div>
                        @endif

                        <form action="{{ route('resident.payment.fake.process') }}" method="POST">
                            @csrf
                            
                            @if(isset($invoiceId))
                                <input type="hidden" name="invoice_id" value="{{ $invoiceId }}">
                            @endif
                            
                            @if(isset($invoiceIds) && !empty($invoiceIds))
                                <input type="hidden" name="invoice_ids" value="{{ json_encode($invoiceIds) }}">
                            @endif

                            <div class="mb-3">
                                <label for="card_number" class="form-label">شماره کارت</label>
                                <input type="text" class="form-control" id="card_number" name="card_number" 
                                       placeholder="1234567890123456" maxlength="16" required>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="expiry_date" class="form-label">تاریخ انقضا</label>
                                        <input type="text" class="form-control" id="expiry_date" name="expiry_date" 
                                               placeholder="MM/YY" maxlength="5" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="cvv" class="form-label">CVV</label>
                                        <input type="text" class="form-control" id="cvv" name="cvv" 
                                               placeholder="123" maxlength="3" required>
                                    </div>
                                </div>
                            </div>

                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-check-circle"></i> پرداخت
                                </button>
                                <a href="{{ route('resident.invoices.index') }}" class="btn btn-secondary">
                                    <i class="bi bi-arrow-right"></i> بازگشت
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Format card number with spaces
    const cardNumber = document.getElementById('card_number');
    cardNumber.addEventListener('input', function(e) {
        let value = e.target.value.replace(/\s/g, '').replace(/\D/g, '');
        if (value.length > 16) value = value.substr(0, 16);
        e.target.value = value;
    });

    // Format expiry date
    const expiryDate = document.getElementById('expiry_date');
    expiryDate.addEventListener('input', function(e) {
        let value = e.target.value.replace(/\D/g, '');
        if (value.length >= 2) {
            value = value.substr(0, 2) + '/' + value.substr(2, 2);
        }
        e.target.value = value;
    });

    // Format CVV
    const cvv = document.getElementById('cvv');
    cvv.addEventListener('input', function(e) {
        let value = e.target.value.replace(/\D/g, '');
        if (value.length > 3) value = value.substr(0, 3);
        e.target.value = value;
    });
});
</script>
@endpush
