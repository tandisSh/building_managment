@extends('layouts.app')

@section('content')
    <div class="admin-header d-flex justify-content-between align-items-center flex-wrap mb-4 p-3 shadow-sm rounded">
        <h6 class="mb-0 fw-bold text-dark">لیست ساکنین ساختمان</h6>

        <div class="tools-box mt-3 mt-md-0">
            <form method="GET" action="{{ route('residents.index') }}" class="d-flex flex-wrap align-items-center gap-2">
                <input type="text" name="search" value="{{ request('search') }}" class="form-control form-control-sm"
                    placeholder="نام یا موبایل ساکن" style="width: 200px;">

                <select name="role" class="form-select form-select-sm" style="width: 150px;">
                    <option value="">نقش</option>
                    <option value="owner" {{ request('role') == 'owner' ? 'selected' : '' }}>مالک</option>
                    <option value="resident" {{ request('role') == 'tenant' ? 'selected' : '' }}>ساکن (مستاجر)</option>
                </select>

                <select name="unit_id" class="form-select form-select-sm" style="width: 150px;">
                    <option value="">واحد</option>
                    @foreach ($units as $unit)
                        <option value="{{ $unit->id }}" {{ request('unit_id') == $unit->id ? 'selected' : '' }}>
                            واحد {{ $unit->unit_number }}
                        </option>
                    @endforeach
                </select>

           <select name="status" class="form-select form-select-sm" style="width: 150px;">
    <option value="">وضعیت</option>
    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>فعال</option>
    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>غیرفعال</option>
</select>

                <button type="submit" class="btn btn-sm btn-outline-primary">جستجو</button>
                <a href="{{ route(name: 'residents.index') }}" class="btn btn-sm btn-outline-secondary">حذف فیلتر</a>
                <a href="{{ route('residents.create') }}" class="btn btn-sm btn-success ms-2">افزودن ساکن</a>
            </form>
        </div>
    </div>

    <div class="card admin-table-card">
        <div class="card-body table-responsive">
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <table class="table table-bordered table-hover align-middle text-center small">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>نام ساکن</th>
                        <th>شماره موبایل</th>
                        <th>واحد</th>
                        <th>نقش</th>
                        <th>تاریخ ثبت</th>
                        <th>وضعیت</th>
                        <th>عملیات</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($residents as $index => $resident)
                        @php $user = $resident->user; @endphp
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $user?->name ?? '-' }}</td>
                            <td>{{ $user?->phone ?? '-' }}</td>
                            <td>{{ $resident->unit?->unit_number ?? '-' }}</td>
                            <td>
                                @if ($resident->roles === 'مالک')
                                    <span class="badge bg-success">مالک</span>
                                @elseif ($resident->roles === 'ساکن')
                                    <span class="badge bg-info">ساکن (مستاجر)</span>
                                @elseif ($resident->roles === 'مالک و ساکن')
                                    <span class="badge bg-primary">مالک و ساکن</span>
                                @else
                                    <span class="badge bg-secondary">نامشخص</span>
                                @endif
                            </td>

                            <td>{{ jdate($resident->created_at)->format('Y/m/d') }}</td>
                            <td>
                                @if ($resident->status === 'active')
                                    <span class="badge bg-success">فعال</span>
                                @elseif ($resident->status === 'inactive')
                                    <span class="badge bg-danger">غیرفعال</span>
                                @endif
                            </td>

                            <td>
                                <div class="d-flex justify-content-center gap-2">
                                    @if ($user)
                                        <a href="{{ route('residents.show', $user) }}"
                                            class="btn btn-sm btn-outline-primary" title="نمایش">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="{{ route('residents.edit', $user->id) }}"
                                            class="btn btn-sm btn-outline-warning" title="ویرایش">
                                            <i class="bi bi-pencil-square"></i>
                                        </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted">ساکنی یافت نشد.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
