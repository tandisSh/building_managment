@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h4 class="text-center mb-4">صدور صورتحساب کلی</h4>

    <ul class="nav nav-tabs justify-content-center" id="invoiceTabs">
        <li class="nav-item">
            <a class="nav-link active" id="current-tab" data-bs-toggle="tab" href="#current">صورتحساب جاری</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="fixed-tab" data-bs-toggle="tab" href="#fixed">صورتحساب ثابت</a>
        </li>
    </ul>

    <div class="tab-content mt-4">
        {{-- صورتحساب جاری --}}
        <div class="tab-pane fade show active" id="current">
            <form action="{{ route('manager.invoices.store') }}" method="POST">
                @csrf
                <input type="hidden" name="type" value="current">

                <div class="mb-3">
                    <label for="title" class="form-label">نوع هزینه جاری</label>
                    <select name="title" class="form-select" required>
                        <option value="">انتخاب کنید</option>
                        @if($building->shared_water)
                            <option value="آب">آب</option>
                        @endif
                        @if($building->shared_electricity)
                            <option value="برق">برق</option>
                        @endif
                        @if($building->shared_gas)
                            <option value="گاز">گاز</option>
                        @endif
                        <option value="شارژ ساختمان">شارژ ساختمان</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="base_amount" class="form-label">مبلغ</label>
                    <input type="number" name="base_amount" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label for="due_date" class="form-label">مهلت پرداخت</label>
                    <input type="date" name="due_date" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label for="description" class="form-label">توضیحات (اختیاری)</label>
                    <textarea name="description" class="form-control" rows="2"></textarea>
                </div>

                <button type="submit" class="btn btn-primary w-100">ثبت صورتحساب جاری</button>
            </form>
        </div>

        {{-- صورتحساب ثابت --}}
        <div class="tab-pane fade" id="fixed">
            <form action="{{ route('manager.invoices.store') }}" method="POST">
                @csrf
                <input type="hidden" name="type" value="fixed">

                <div class="mb-3">
                    <label for="title" class="form-label">عنوان صورتحساب ثابت</label>
                    <input type="text" name="title" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label for="base_amount" class="form-label">مبلغ</label>
                    <input type="number" name="base_amount" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label for="due_date" class="form-label">مهلت پرداخت</label>
                    <input type="date" name="due_date" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label for="description" class="form-label">توضیحات (اختیاری)</label>
                    <textarea name="description" class="form-control" rows="2"></textarea>
                </div>

                <button type="submit" class="btn btn-primary w-100">ثبت صورتحساب ثابت</button>
            </form>
        </div>
    </div>
</div>
@endsection
