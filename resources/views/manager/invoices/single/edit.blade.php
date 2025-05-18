@extends('layouts.app')

@section('content')
    <div class="container mt-5">
        <h4 class="text-center mb-4">ویرایش صورتحساب تکی</h4>

        <form action="{{ route('manager.single-invoices.update', $invoice->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label for="unit_id" class="form-label">واحد</label>
                <select name="unit_id" id="unit_id" class="form-select" required title="انتخاب واحد">
                    <option value="">انتخاب واحد</option>
                    @foreach ($units as $unit)
                        <option value="{{ $unit->id }}" {{ $invoice->unit_id == $unit->id ? 'selected' : '' }}>
                            {{ $unit->unit_number }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label for="title" class="form-label">عنوان صورتحساب</label>
                <input type="text" name="title" id="title" class="form-control"
                    value="{{ old('title', $invoice->title) }}" placeholder="مثلاً شارژ یا تعمیر آسانسور" required>
            </div>
            <div class="mb-3">
                <label for="type" class="form-label">نوع صورتحساب</label>
                <select name="type" id="type" class="form-select" required>
                    <option value="current" {{ old('type', $invoice->type) == 'current' ? 'selected' : '' }}>
                        جاری</option>
                    <option value="fixed" {{ old('type', $invoice->type) == 'fixed' ? 'selected' : '' }}>ثابت
                    </option>
                </select>
            </div>
            <div class="mb-3">
                <label for="amount" class="form-label">مبلغ</label>
                <input type="number" name="amount" id="amount" class="form-control"
                    value="{{ old('amount', $invoice->amount) }}" placeholder="مبلغ به تومان" required>
            </div>

            <div class="mb-3">
                <label for="due_date" class="form-label">مهلت پرداخت</label>
                <input type="date" name="due_date" id="due_date" class="form-control"
                    value="{{ old('due_date', \Carbon\Carbon::parse($invoice->due_date)->format('Y-m-d')) }}" required>
            </div>

            <div class="mb-3">
                <label for="description" class="form-label">توضیحات (اختیاری)</label>
                <textarea name="description" id="description" class="form-control" rows="2" placeholder="توضیحات اضافه">{{ old('description', $invoice->description) }}</textarea>
            </div>


            <button type="submit" class="btn btn-success w-100">ذخیره تغییرات</button>
        </form>
    </div>
@endsection
