@extends('layouts.app')

@section('content')
    <div class="container mt-5">
        <h4 class="text-center mb-4">صدور صورتحساب تکی برای یک واحد</h4>

        <form action="{{ route('invoices.single.store') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label for="unit_id" class="form-label">واحد</label>
                <select name="unit_id" class="form-select" required>
                    <option value="">انتخاب واحد</option>
                    @foreach ($units as $unit)
                        <option value="{{ $unit->id }}">{{ $unit->unit_number }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label for="title" class="form-label">عنوان صورتحساب</label>
                <input type="text" name="title" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="type" class="form-label">نوع صورتحساب</label>
                <select name="type" id="type" class="form-select" required>
                    <option value="current" >
                        جاری</option>
                    <option value="fixed" >ثابت
                    </option>
                </select>
            </div>
            <div class="mb-3">
                <label for="amount" class="form-label">مبلغ</label>
                <input type="number" name="amount" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="due_date" class="form-label">مهلت پرداخت</label>
                <input type="date" name="due_date" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="description" class="form-label">توضیحات (اختیاری)</label>
                <textarea name="description" class="form-control" rows="2"></textarea>
            </div>


            <button type="submit" class="btn btn-success w-100">ثبت صورتحساب تکی</button>
        </form>
    </div>
@endsection
