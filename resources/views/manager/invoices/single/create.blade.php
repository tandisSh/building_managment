@extends('layouts.app')

@section('content')
<div class="container mt-3">
    <div class="admin-header d-flex justify-content-between align-items-center mb-4" style="background-color: #4e3cb3;">
        <h6 class="mb-0 fw-bold text-white py-2 px-3">
            <i class="bi bi-receipt me-2"></i>صدور صورتحساب تکی برای یک واحد
        </h6>
    </div>

    <div class="admin-table-card p-4">
        <form action="{{ route('invoices.single.store') }}" method="POST">
            @csrf

            <div class="row g-3">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="unit_id" class="form-label small">واحد *</label>
                        <select name="unit_id" class="form-select form-select-sm" required>
                            <option value="">انتخاب واحد</option>
                            @foreach ($units as $unit)
                                <option value="{{ $unit->id }}">{{ $unit->unit_number }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label for="title" class="form-label small">عنوان صورتحساب *</label>
                        <input type="text" name="title" class="form-control form-control-sm" required>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label for="type" class="form-label small">نوع صورتحساب *</label>
                        <select name="type" class="form-select form-select-sm" required>
                            <option value="current">جاری</option>
                            <option value="fixed">ثابت</option>
                        </select>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label for="amount" class="form-label small">مبلغ *</label>
                        <input type="number" name="amount" class="form-control form-control-sm" required>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label for="due_date" class="form-label small">مهلت پرداخت *</label>
                        <input type="date" name="due_date" class="form-control form-control-sm" required>
                    </div>
                </div>

                <div class="col-12">
                    <div class="form-group">
                        <label for="description" class="form-label small">توضیحات (اختیاری)</label>
                        <textarea name="description" class="form-control form-control-sm" rows="2"></textarea>
                    </div>
                </div>

                <div class="col-12 mt-3">
                    <button type="submit" class="btn btn-sm add-btn w-100 py-2">
                        <i class="bi bi-check-circle me-1"></i> ثبت صورتحساب تکی
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
