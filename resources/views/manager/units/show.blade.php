@extends('layouts.app')

@section('content')
<div class="card">
    <div class="card-header bg-primary text-white">
        <h5 class="mb-0">مشاهده واحد {{ $unit->unit_number }}</h5>
    </div>

    <div class="card-body">

        {{-- مشخصات واحد --}}
        <div class="row g-3 mb-4">
            <div class="col-md-6">
                <div class="border rounded p-3 h-100">
                    <label class="fw-bold me-2">شماره واحد:</label>
                    <span>{{ $unit->unit_number }}</span>
                </div>
            </div>
            <div class="col-md-6">
                <div class="border rounded p-3 h-100">
                    <label class="fw-bold me-2">طبقه:</label>
                    <span>{{ $unit->floor ?? '-' }}</span>
                </div>
            </div>

            <div class="col-md-6">
                <div class="border rounded p-3 h-100">
                    <label class="fw-bold me-2">متراژ:</label>
                    <span>{{ $unit->area ?? '-' }} متر</span>
                </div>
            </div>
            <div class="col-md-6">
                <div class="border rounded p-3 h-100">
                    <label class="fw-bold me-2">تعداد جای پارک:</label>
                    <span>{{ $unit->parking_slots ?? 0 }}</span>
                </div>
            </div>

            <div class="col-md-6">
                <div class="border rounded p-3 h-100">
                    <label class="fw-bold me-2">تعداد انباری:</label>
                    <span>{{ $unit->storerooms ?? 0 }}</span>
                </div>
            </div>
        </div>

        {{-- اطلاعات مالک و مستاجر --}}
        <div class="border-top pt-4 mt-4">
            <h6 class="fw-bold mb-3">اطلاعات کاربران</h6>
            <div class="row g-3">
                <div class="col-md-6">
                    <div class="border rounded p-3 h-100">
                        <label class="fw-bold me-2">مالک:</label>
                        @if($unit->owner->first())
                            <span>{{ $unit->owner->first()->full_name ?? $unit->owner->first()->name }}</span>
                        @else
                            <span class="text-muted">ندارد</span>
                        @endif
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="border rounded p-3 h-100">
                        <label class="fw-bold me-2">ساکن (مستأجر):</label>
                        @if($unit->resident->first())
                            <span>{{ $unit->resident->first()->full_name ?? $unit->resident->first()->name }}</span>
                        @else
                            <span class="text-muted">ندارد</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- دکمه‌ها --}}
        <div class="mt-4">
            <a href="{{ route('units.edit', [$building->id, $unit->id]) }}" class="btn btn-primary">ویرایش</a>
            <a href="{{ route('units.index', $building->id) }}" class="btn btn-secondary">بازگشت</a>
        </div>
    </div>
</div>
@endsection
