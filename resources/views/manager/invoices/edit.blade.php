@extends('layouts.app')

@section('content')
    <div class="card">
        <div class="card-header">
            <h5>ویرایش صورتحساب کلی</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('manager.bulk_invoices.update', $bulkInvoice->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label for="title" class="form-label">عنوان</label>
                    <input type="text" name="title" id="title" class="form-control"
                        value="{{ old('title', $bulkInvoice->title) }}" required>
                </div>

                <div class="mb-3">
                    <label for="base_amount" class="form-label">مبلغ پایه</label>
                    <input type="number" name="base_amount" id="base_amount" class="form-control"
                        value="{{ old('base_amount', $bulkInvoice->base_amount) }}" required>
                </div>

                <div class="mb-3">
                    <label for="due_date" class="form-label">تاریخ سررسید</label>
                    <input type="date" name="due_date"
                        value="{{ old('due_date', \Carbon\Carbon::parse($bulkInvoice->due_date)->format('Y-m-d')) }}"
                        required>


                    <div class="mb-3">
                        <label for="type" class="form-label">نوع صورتحساب</label>
                        <select name="type" id="type" class="form-select" required>
                            <option value="current" {{ old('type', $bulkInvoice->type) == 'current' ? 'selected' : '' }}>
                                جاری</option>
                            <option value="fixed" {{ old('type', $bulkInvoice->type) == 'fixed' ? 'selected' : '' }}>ثابت
                            </option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">توضیحات (اختیاری)</label>
                        <textarea name="description" id="description" class="form-control">{{ old('description', $bulkInvoice->description) }}</textarea>
                    </div>

                    <button type="submit" class="btn btn-success">ذخیره تغییرات</button>
                    <a href="{{ route('bulk_invoices.index') }}" class="btn btn-secondary">انصراف</a>
            </form>
        </div>
    </div>
@endsection
