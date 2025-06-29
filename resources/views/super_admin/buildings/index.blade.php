@extends('layouts.app')

@section('content')
    {{-- هدر بالا: عنوان + دکمه افزودن --}}
    <div class="admin-header d-flex justify-content-between align-items-center mb-3 shadow-sm rounded flex-wrap">
        <h6 class="mb-0 fw-bold text-white text-center">
            <i class="bi bi-building"></i> لیست ساختمان‌ها
        </h6>
        <div class="d-flex align-items-center gap-2 mb-3 text-center" style="flex-wrap: wrap;">
            <a href="{{ route('superadmin.buildings.create') }}" class="btn add-btn text-center">
                <i class="bi bi-plus-circle me-1"></i> افزودن ساختمان جدید
            </a>
        </div>
    </div>

    {{-- کادر فیلترها و جستجو --}}
    <div class="card search-filter-card mb-3">
        <div class="card-body">
            <form method="GET" action="{{ route('superadmin.buildings.index') }}"
                class="row g-2 align-items-center text-center">
                <div class="col-auto">
                    <input type="text" name="search" value="{{ request('search') }}"
                        class="form-control form-control-sm w-auto search-input" placeholder="نام، آدرس، استان یا شهر"
                        style="max-width: 250px;">
                </div>
                <div class="col-auto">
                    <select name="shared_water" class="form-select form-select-sm search-input" style="max-width: 120px;">
                        <option value="">آب مشترک</option>
                        <option value="1" {{ request('shared_water') === '1' ? 'selected' : '' }}>دارد</option>
                        <option value="0" {{ request('shared_water') === '0' ? 'selected' : '' }}>ندارد</option>
                    </select>
                </div>
                <div class="col-auto">
                    <select name="shared_gas" class="form-select form-select-sm search-input" style="max-width: 120px;">
                        <option value="">گاز مشترک</option>
                        <option value="1" {{ request('shared_gas') === '1' ? 'selected' : '' }}>دارد</option>
                        <option value="0" {{ request('shared_gas') === '0' ? 'selected' : '' }}>ندارد</option>
                    </select>
                </div>
                <div class="col-auto">
                    <select name="shared_electricity" class="form-select form-select-sm search-input"
                        style="max-width: 120px;">
                        <option value="">برق مشترک</option>
                        <option value="1" {{ request('shared_electricity') === '1' ? 'selected' : '' }}>دارد</option>
                        <option value="0" {{ request('shared_electricity') === '0' ? 'selected' : '' }}>ندارد</option>
                    </select>
                </div>
                <div class="col-auto">
                    <button type="submit" class="btn btn-sm btn-outline-primary filter-btn">اعمال فیلتر</button>
                    <a href="{{ route('superadmin.buildings.index') }}"
                        class="btn btn-sm btn-outline-secondary filter-btn">حذف فیلتر</a>
                </div>
            </form>
        </div>
    </div>

    {{-- جدول ساختمان‌ها --}}
    <div class="card admin-table-card">
        <div class="card-body table-responsive">
            @if (session('success'))
                <div class="alert alert-success text-center">{{ session('success') }}</div>
            @endif

            <table class="table table-bordered table-striped align-middle text-center table-units">
                <thead>
                    <tr>
                        <th>ردیف</th>
                        <th>نام ساختمان</th>
                        <th>آدرس</th>
                        <th>استان</th>
                        <th>شهر</th>
                        <th>تعداد طبقات</th>
                        <th>تعداد واحدها</th>
                        <th>مشترکات</th>
                        <th>عملیات</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($buildings as $index => $building)
                        <tr>
                            <td>{{ $index + 1 + ($buildings->currentPage() - 1) * $buildings->perPage() }}</td>
                            <td>{{ $building->name }}</td>
                            <td class="text-truncate" style="max-width: 150px;">{{ $building->address }}</td>
                            <td>{{ $building->province ?? 'تعریف نشده' }}</td>
                            <td>{{ $building->city ?? 'تعریف نشده' }}</td>
                            <td>{{ $building->number_of_floors }}</td>
                            <td>{{ $building->number_of_units }}</td>
                            <td>
                                <span
                                    class="badge bg-{{ $building->shared_water ? 'primary' : 'secondary' }} py-1 px-2 mx-1">آب</span>
                                <span
                                    class="badge bg-{{ $building->shared_gas ? 'primary' : 'secondary' }} py-1 px-2 mx-1">گاز</span>
                                <span
                                    class="badge bg-{{ $building->shared_electricity ? 'primary' : 'secondary' }} py-1 px-2 mx-1">برق</span>
                            </td>
                            <td>
                                <div class="d-flex justify-content-center gap-2">
                                    <a href="{{ route('superadmin.buildings.show', $building) }}"
                                        class="btn btn-sm btn-outline-primary" title="نمایش">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('superadmin.buildings.edit', $building->id) }}"
                                        class="btn btn-sm btn-outline-warning" title="ویرایش">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>
                                    <form action="{{ route('superadmin.buildings.destroy', $building->id) }}"
                                        method="POST" onsubmit="return confirm('آیا مطمئنید حذف شود؟');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" title="حذف" @disabled(!$building->isDeletable())>
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center">هیچ ساختمانی یافت نشد.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="mt-3 d-flex justify-content-center">
                {{ $buildings->withQueryString()->links() }}
            </div>
        </div>
    </div>
@endsection
