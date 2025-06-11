@extends('layouts.app')

@section('content')
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">پرداخت </div>
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

                        <form action="{{ route('resident.payment.fake.process') }}" method="POST">
                            @csrf
                            <input type="hidden" name="invoice_id" value="{{ $invoiceId ?? '' }}">
                            <input type="hidden" name="invoice_ids" value="{{ json_encode($invoiceIds ?? []) }}">
                            <div class="mb-3">
                                <label for="card_number" class="form-label">شماره کارت</label>
                                <input type="text" class="form-control" id="card_number" name="card_number" required>
                            </div>
                            <div class="mb-3">
                                <label for="expiry_date" class="form-label">تاریخ انقضا (MM/YY)</label>
                                <input type="text" class="form-control" id="expiry_date" name="expiry_date" required>
                            </div>
                            <div class="mb-3">
                                <label for="cvv" class="form-label">CVV</label>
                                <input type="text" class="form-control" id="cvv" name="cvv" required>
                            </div>
                            <button type="submit" class="btn btn-primary">پرداخت</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
