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
                                <label class="form-label small">نوع هزینه جاری</label>
                                <select name="title" class="form-select form-select-sm" >
                                    <option value="">انتخاب کنید</option>
                                    @if ($building->shared_water)
                                        <option value="آب">آب</option>
                                    @endif
                                    @if ($building->shared_electricity)
                                        <option value="برق">برق</option>
                                    @endif
                                    @if ($building->shared_gas)
                                        <option value="گاز">گاز</option>
                                    @endif
                                    <option value="شارژ ساختمان">شارژ ساختمان</option>
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label small">مبلغ</label>
                                <input type="number" name="base_amount" class="form-control form-control-sm" >
                            </div>

                            <div class="col-md-6">
                                <label class="form-label small">مهلت پرداخت</label>
                                <input type="date" name="due_date" class="form-control form-control-sm" >
                            </div>

<div class="row">
    <div class="col-md-6">
        <label for="distribution_type" class="form-label small">روش تقسیم هزینه بین واحدها</label>
        <select name="distribution_type" id="distribution_type" class="form-select form-select-sm">
            <option value="equal">تقسیم مساوی بین همه واحدها</option>
            <option value="per_person">تقسیم بر اساس تعداد نفرات ساکن در هر واحد</option>
        </select>
    </div>

    <div class="col-md-6">
        <label for="fixed_percent_input" class="form-label small">درصد پایه برای تقسیم (مثلاً 100)</label>
        <input type="number" name="fixed_percent" id="fixed_percent_input"
               class="form-control form-control-sm" min="1" max="100" disabled>
    </div>
</div>

                            <div class="col-12">
                                <label class="form-label small">توضیحات (اختیاری)</label>
                                <textarea name="description" class="form-control form-control-sm" rows="2"></textarea>
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
                                <label class="form-label small">عنوان صورتحساب ثابت</label>
                                <input type="text" name="title" class="form-control form-control-sm" >
                            </div>

                            <div class="col-md-6">
                                <label class="form-label small">مبلغ</label>
                                <input type="number" name="base_amount" class="form-control form-control-sm" >
                            </div>

                            <div class="col-md-6">
                                <label class="form-label small">مهلت پرداخت</label>
                                <input type="date" name="due_date" class="form-control form-control-sm" >
                            </div>
<input type="hidden" name="distribution_type" value="equal">

                            <div class="col-12">
                                <label class="form-label small">توضیحات (اختیاری)</label>
                                <textarea name="description" class="form-control form-control-sm" rows="2"></textarea>
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
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const distributionSelect = document.getElementById('distribution_type');
    const percentInput = document.getElementById('fixed_percent_input');

    if (!distributionSelect || !percentInput) {
        console.error('عناصر مورد نیاز یافت نشدند.');
        return;
    }

    function handleChange() {
        if (distributionSelect.value === 'per_person') {
            percentInput.disabled = false;
        } else {
            percentInput.disabled = true;
            percentInput.value = '';
        }
    }

    distributionSelect.addEventListener('change', handleChange);
    handleChange(); // برای بار اول هم بررسی کن
});
</script>
@endpush



@endsection
