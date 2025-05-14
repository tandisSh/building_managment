@extends('layouts.app')

@section('content')
    <form method="POST" action="{{ route('invoices.single.store') }}">
        @csrf

        {{-- اگر unit_id از URL اومده، این hidden باشه --}}
        @if (request()->has('unit_id'))
            <input type="hidden" name="unit_id" value="{{ request('unit_id') }}">
        @else
            {{-- اگر مستقیماً از لیست صورتحساب اومده، انتخاب واحد --}}
            <div class="mb-3">
                <label for="unit_id">واحد</label>
                <select name="unit_id" class="form-select" required>
                    @foreach ($units as $unit)
                        <option value="{{ $unit->id }}">{{ $unit->id }}
                        </option>
                    @endforeach
                </select>
            </div>
        @endif
        <div class="mb-3">
            <label for="type">نوع صورتحساب</label>
            <select name="type" class="form-select" required>
                <option value="current">جاری</option>
                <option value="fixed">ثابت</option>
            </select>
        </div>
        <div class="mb-3">
            <label for="title">عنوان هزینه</label>
            <input type="text" name="title" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="amount">مبلغ</label>
            <input type="number" name="amount" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="due_date">تاریخ سررسید</label>
            <input type="date" name="due_date" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="description">توضیحات</label>
            <textarea name="description" class="form-control"></textarea>
        </div>

        <button type="submit" class="btn btn-primary w-100">ثبت صورتحساب</button>
    </form>

@endsection
