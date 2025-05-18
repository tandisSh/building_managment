@extends('layouts.app')

@section('content')
<div class="container mt-3">
    <div class="admin-header d-flex justify-content-between align-items-center mb-4" style="background-color: #4e3cb3;">
        <h6 class="mb-0 fw-bold text-white py-2 px-3">
            <i class="bi bi-receipt me-2"></i>صدور صورتحساب کلی
        </h6>
    </div>

    <div class="admin-table-card p-4">
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

                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="title" class="form-label small">نوع هزینه جاری</label>
                                <select name="title" class="form-select form-select-sm" required>
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
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="base_amount" class="form-label small">مبلغ</label>
                                <input type="number" name="base_amount" class="form-control form-control-sm" required>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="due_date" class="form-label small">مهلت پرداخت</label>
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
                                <i class="bi bi-check-circle me-1"></i> ثبت صورتحساب جاری
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            {{-- صورتحساب ثابت --}}
            <div class="tab-pane fade" id="fixed">
                <form action="{{ route('manager.invoices.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="type" value="fixed">

                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="title" class="form-label small">عنوان صورتحساب ثابت</label>
                                <input type="text" name="title" class="form-control form-control-sm" required>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="base_amount" class="form-label small">مبلغ</label>
                                <input type="number" name="base_amount" class="form-control form-control-sm" required>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="due_date" class="form-label small">مهلت پرداخت</label>
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
                                <i class="bi bi-check-circle me-1"></i> ثبت صورتحساب ثابت
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
