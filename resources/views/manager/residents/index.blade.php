@extends('layouts.app')

@section('content')
    <div class="admin-header d-flex justify-content-between align-items-center mb-3 shadow-sm rounded flex-wrap">
        <h6 class="mb-0 fw-bold text-dark">لیست ساکنین ساختمان</h6>

        <div class="tools-box mb-3">
            <div class="d-flex flex-wrap align-items-center justify-content-between gap-2">
                <form method="GET" action="{{ route('residents.index') }}" class="d-flex flex-wrap align-items-center gap-2">
                    <input type="text" name="search" value="{{ request('search') }}" class="form-control form-control-sm"
                        placeholder="نام یا موبایل ساکن" style="width: 200px;">

                    <select name="role" class="form-select form-select-sm" style="width: 150px;">
                        <option value="">نقش</option>
                        <option value="owner" {{ request('role') == 'owner' ? 'selected' : '' }}>مالک</option>
                        <option value="tenant" {{ request('role') == 'tenant' ? 'selected' : '' }}>ساکن (مستاجر)</option>
                    </select>

                    <select name="unit_id" class="form-select form-select-sm" style="width: 150px;">
                        <option value="">واحد</option>
                        @foreach ($units as $unit)
                            <option value="{{ $unit->id }}" {{ request('unit_id') == $unit->id ? 'selected' : '' }}>
                                واحد {{ $unit->unit_number }}
                            </option>
                        @endforeach
                    </select>

                    <button type="submit" class="btn btn-sm btn-outline-primary"> جستجو</button>
                    <a href="{{ route('residents.index') }}" class="btn btn-sm btn-outline-secondary">حذف فیلتر</a>
                </form>

                <a href="{{ route('residents.create') }}" class="btn btn-sm add-btn">افزودن ساکن</a>
            </div>
        </div>

    </div>

    <div class="card admin-table-card">
        <div class="card-body table-responsive">
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <table class="table table-bordered table-striped align-middle small table-units">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>نام ساکن</th>
                        <th>شماره موبایل</th>
                        <th>واحد</th>
                        <th>نقش</th>
                        <th>تاریخ ثبت</th>
                        <th>عملیات</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($residents as $i => $resident)
                        <tr>
                            <td>{{ $i + 1 }}</td>
                            <td>{{ optional($resident->user)->name ?? '-' }}</td>
                            <td>{{ optional($resident->user)->phone ?? '-' }}</td>
                            <td>{{ optional($resident->unit)->unit_number ?? '-' }}</td>
                            <td>
                                @if ($resident->role === 'owner')
                                    <span class="badge bg-success">مالک</span>
                                @else
                                    <span class="badge bg-info">ساکن (مستاجر)</span>
                                @endif
                            </td>
                            <td>{{ jdate($resident->created_at)->format('Y/m/d') }}</td>
                            <td>
                                @if ($resident->user)
                                    <a href="{{ route('residents.show', $resident->user) }}"
                                        class="btn btn-sm btn-outline-primary" title="نمایش">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('residents.edit', $resident->user->id) }}"
                                        class="btn btn-sm btn-outline-warning" title="ویرایش">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>
                                @endif
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
