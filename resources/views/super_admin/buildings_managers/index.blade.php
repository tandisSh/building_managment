@extends('layouts.app')

@section('content')
    {{-- هدر بالا --}}
    <div class="admin-header d-flex justify-content-between align-items-center mb-3 shadow-sm rounded flex-wrap">
        <h6 class="mb-0 fw-bold text-white text-center">
            <i class="bi bi-building"></i> لیست ساختمان‌ها و مدیران
        </h6>
    </div>

    {{-- کادر فیلتر --}}
    <div class="card search-filter-card mb-3">
        <div class="card-body">
            <form method="GET" action="{{ route('superadmin.building_managers.index') }}"
                class="row g-2 align-items-center text-center">
                <div class="col-auto">
                    <input type="text" name="search" value="{{ request('search') }}"
                        class="form-control form-control-sm w-auto search-input" placeholder="نام یا کد ساختمان"
                        style="max-width: 200px;">
                </div>

                <div class="col-auto">
                    <select name="manager_id" class="form-select form-select-sm search-input" style="max-width: 200px;">
                        <option value="">همه مدیران</option>
                        @foreach ($managers as $m)
                            <option value="{{ $m->id }}" {{ request('manager_id') == $m->id ? 'selected' : '' }}>
                                {{ $m->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-auto">
                    <button type="submit" class="btn btn-sm btn-outline-primary filter-btn">اعمال فیلتر</button>
                    <a href="{{ route('superadmin.building_managers.index') }}"
                        class="btn btn-sm btn-outline-secondary filter-btn">حذف فیلتر</a>
                </div>
            </form>
        </div>
    </div>

    {{-- جدول --}}
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
                        <th>کد ساختمان</th>
                        <th>مدیر فعلی</th>
                        <th>عملیات</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($buildings as $index => $building)
                        <tr>
                            <td>{{ $index + 1 + ($buildings->currentPage() - 1) * $buildings->perPage() }}</td>
                            <td>{{ $building->name }}</td>
                            <td>{{ $building->id }}</td>
                            <td>
                                {{ optional($building->manager)->name ?? 'مدیر ندارد' }}
                            </td>
                            <td>
                                <a href="{{ route('superadmin.building_managers.edit', $building->id) }}"
                                    class="btn btn-sm btn-outline-warning">تغییر مدیر</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center">هیچ ساختمانی یافت نشد.</td>
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
