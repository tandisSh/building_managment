@extends('layouts.app')

@section('content')
<div class="card">
    <div class="card-header">صدور صورتحساب ماهانه</div>
    <div class="card-body">
        <form method="POST" action="{{ route('manager.invoices.store') }}">
            @csrf

            <div class="mb-3">
                <label for="base_amount" class="form-label">مبلغ پایه شارژ *</label>
                <input type="number" step="0.01" name="base_amount" class="form-control" required>
            </div>

            @if($building->shared_water)
                <div class="mb-3">
                    <label for="water_cost" class="form-label">هزینه آب مشترک</label>
                    <input type="number" step="0.01" name="water_cost" class="form-control">
                </div>
            @endif

            @if($building->shared_electricity)
                <div class="mb-3">
                    <label for="electricity_cost" class="form-label">هزینه برق مشترک</label>
                    <input type="number" step="0.01" name="electricity_cost" class="form-control">
                </div>
            @endif

            @if($building->shared_gas)
                <div class="mb-3">
                    <label for="gas_cost" class="form-label">هزینه گاز مشترک</label>
                    <input type="number" step="0.01" name="gas_cost" class="form-control">
                </div>
            @endif

            <div class="mb-3">
                <label for="due_date" class="form-label">تاریخ سررسید *</label>
                <input type="date" name="due_date" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="description" class="form-label">توضیحات (اختیاری)</label>
                <textarea name="description" class="form-control"></textarea>
            </div>

            <button type="submit" class="btn btn-success w-100">صدور صورتحساب</button>
        </form>
    </div>
</div>
@endsection
