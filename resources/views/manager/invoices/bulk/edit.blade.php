@extends('layouts.app')

@section('content')
<div class="container mt-3">
    <div class="admin-header d-flex justify-content-between align-items-center mb-4" style="background-color: #4e3cb3;">
        <h6 class="mb-0 fw-bold text-white py-2 px-3">
            <i class="bi bi-receipt me-2"></i>ویرایش صورتحساب کلی
        </h6>
    </div>

    <div class="admin-table-card p-4">
        <form action="{{ route('manager.bulk_invoices.update', $bulkInvoice->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="row g-3">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="title" class="form-label small">عنوان صورتحساب</label>
                        <input type="text" name="title" id="title" class="form-control form-control-sm"
                            value="{{ old('title', $bulkInvoice->title) }}" required>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label for="base_amount" class="form-label small">مبلغ پایه</label>
                        <input type="number" name="base_amount" id="base_amount" class="form-control form-control-sm"
                            value="{{ old('base_amount', $bulkInvoice->base_amount) }}" required>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label for="due_date" class="form-label small">تاریخ سررسید</label>
                        <input type="date" name="due_date" id="due_date" class="form-control form-control-sm"
                            value="{{ old('due_date', \Carbon\Carbon::parse($bulkInvoice->due_date)->format('Y-m-d')) }}" required>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label for="type" class="form-label small">نوع صورتحساب</label>
                        <select name="type" id="type" class="form-select form-select-sm" required>
                            <option value="current" {{ old('type', $bulkInvoice->type) == 'current' ? 'selected' : '' }}>جاری</option>
                            <option value="fixed" {{ old('type', $bulkInvoice->type) == 'fixed' ? 'selected' : '' }}>ثابت</option>
                        </select>
                    </div>
                </div>

                <div class="col-12">
                    <div class="form-group">
                        <label for="description" class="form-label small">توضیحات (اختیاری)</label>
                        <textarea name="description" id="description" class="form-control form-control-sm" rows="3">{{ old('description', $bulkInvoice->description) }}</textarea>
                    </div>
                </div>

                <div class="col-12 mt-4">
                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('bulk_invoices.index') }}" class="btn btn-sm filter-btn">
                            <i class="bi bi-x-circle me-1"></i> انصراف
                        </a>
                        <button type="submit" class="btn btn-sm add-btn">
                            <i class="bi bi-check-circle me-1"></i> ذخیره تغییرات
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
