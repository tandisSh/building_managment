@extends('layouts.app')

@section('content')
<div class="container d-flex justify-content-center">
    <div class="card w-100" style="max-width: 600px;">
        <div class="card-header text-center">
            <ul class="nav nav-tabs card-header-tabs" id="invoiceTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="current-tab" data-bs-toggle="tab" data-bs-target="#current-form" type="button" role="tab">صورتحساب جاری</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="fixed-tab" data-bs-toggle="tab" data-bs-target="#fixed-form" type="button" role="tab">صورتحساب ثابت</button>
                </li>
            </ul>
        </div>
        <div class="card-body">
            <div class="tab-content">

                {{-- فرم صورتحساب جاری --}}
                <div class="tab-pane fade show active" id="current-form" role="tabpanel">
                    <form method="POST" action="{{ route('manager.invoices.store') }}">
                        @csrf
                        <input type="hidden" name="type" value="current">

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

                        <button type="submit" class="btn btn-success w-100">صدور صورتحساب جاری</button>
                    </form>
                </div>

                {{-- فرم صورتحساب ثابت --}}
                <div class="tab-pane fade" id="fixed-form" role="tabpanel">
                    <form method="POST" action="{{ route('manager.invoices.store') }}">
                        @csrf
                        <input type="hidden" name="type" value="fixed">

                        <div class="mb-3">
                            <label for="fixed_title" class="form-label">عنوان هزینه *</label>
                            <input type="text" name="fixed_title" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label for="base_amount" class="form-label">مبلغ کل *</label>
                            <input type="number" step="0.01" name="base_amount" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label for="due_date" class="form-label">تاریخ سررسید *</label>
                            <input type="date" name="due_date" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">توضیحات (اختیاری)</label>
                            <textarea name="description" class="form-control"></textarea>
                        </div>

                        <button type="submit" class="btn btn-primary w-100">صدور صورتحساب ثابت</button>
                    </form>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection
