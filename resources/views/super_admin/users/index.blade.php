@extends('layouts.app')

@section('content')
    <div class="admin-header d-flex justify-content-between align-items-center mb-3 shadow-sm rounded flex-wrap">
        <h6 class="mb-0 fw-bold text-white">لیست کاربران سیستم</h6>
        <div class="d-flex align-items-center gap-2 mb-3" style="flex-wrap: wrap;">
            <a href="{{ route('superadmin.users.create') }}" class="btn btn-sm add-btn">افزودن کاربر +</a>
        </div>
    </div>

    <div class="card search-filter-card mb-3">
        <div class="card-body">
            <form method="GET" action="{{ route('superadmin.users.index') }}" class="d-flex flex-wrap align-items-center gap-2">
                <input type="text" name="search" value="{{ request('search') }}"
                    class="form-control form-control-sm w-auto search-input" placeholder="نام یا موبایل"
                    style="max-width: 200px;">
                <select name="building_id" class="form-select form-select-sm search-input" style="max-width: 150px;">
                    <option value="">ساختمان</option>
                    @foreach ($buildings as $building)
                        <option value="{{ $building->id }}" {{ request('building_id') == $building->id ? 'selected' : '' }}>
                            {{ $building->name }}
                        </option>
                    @endforeach
                </select>
                <select name="role" class="form-select form-select-sm search-input" style="max-width: 150px;">
                    <option value="">نقش در واحد</option>
                    <option value="owner" {{ request('role') == 'owner' ? 'selected' : '' }}>مالک</option>
                    <option value="resident" {{ request('role') == 'resident' ? 'selected' : '' }}>ساکن</option>
                    <option value="resident_owner" {{ request('role') == 'resident_owner' ? 'selected' : '' }}>مالک و ساکن</option>
                </select>
                <button type="submit" class="btn btn-sm btn-outline-primary filter-btn">جستجو</button>
                <a href="{{ route('superadmin.users.index') }}" class="btn btn-sm btn-outline-secondary filter-btn">حذف فیلتر</a>
            </form>
        </div>
    </div>

    <div class="card admin-table-card">
        <div class="card-body table-responsive">
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <table class="table table-bordered table-hover align-middle text-center table-units">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>نام</th>
                        <th>ساختمان</th>
                        <th>واحد</th>
                        <th>نقش در واحد</th>
                        <th>نقش در سیستم</th>
                        <th>تاریخ ثبت</th>
                        <th>وضعیت</th>
                        <th>عملیات</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $index => $user)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $user->name ?? '-' }}</td>
                            <td>
                                @foreach($user->units as $unit)
                                    {{ $unit->building->name ?? '-' }}
                                    @if(!$loop->last) , @endif
                                @endforeach
                            </td>
                            <td>
                                @foreach($user->units as $unit)
                                    {{ $unit->unit_number ?? '-' }}
                                    @if(!$loop->last) , @endif
                                @endforeach
                            </td>
                            <td>
                                @foreach($user->unitUsers as $unitUser)
                                    @if($unitUser->role === 'owner')
                                        <span class="badge bg-success">مالک</span>
                                    @elseif($unitUser->role === 'resident')
                                        <span class="badge bg-info">ساکن</span>
                                    @else
                                        <span class="badge bg-secondary">نامشخص</span>
                                    @endif
                                    @if(!$loop->last) , @endif
                                @endforeach
                            </td>
                            <td>
                                @foreach($user->roles as $role)
                                    <span class="badge bg-secondary">{{ $role->name }}</span>
                                    @if(!$loop->last) , @endif
                                @endforeach
                            </td>
                            <td>{{ jdate($user->created_at)->format('Y/m/d') }}</td>
                            <td>
                                @if ($user->status === 'active')
                                    <span class="badge bg-success">فعال</span>
                                @elseif ($user->status === 'inactive')
                                    <span class="badge bg-danger">غیرفعال</span>
                                @endif
                            </td>
                            <td>
                                <div class="d-flex justify-content-center gap-2">
                                    <a href="{{ route('superadmin.users.show', $user->id) }}"
                                        class="btn btn-sm btn-outline-primary" title="نمایش">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('superadmin.users.edit', $user->id) }}"
                                        class="btn btn-sm btn-outline-warning" title="ویرایش">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>
                                    <form action="{{ route('superadmin.users.destroy', $user->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" title="حذف">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="11" class="text-center text-muted">هیچ کاربری یافت نشد.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
