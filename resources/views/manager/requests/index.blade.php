@extends('layouts.app')

@section('content')
    <div class="admin-header d-flex justify-content-between align-items-center mb-3 shadow-sm rounded flex-wrap">
        <h6 class="mb-0 fw-bold text-dark text-center"><i class="bi bi-tools"></i> درخواست‌های تعمیر ثبت‌شده</h6>
    </div>

    <div class="card search-filter-card mb-3">
        <div class="card-body">
            <form method="GET" action="{{ route('requests.index') }}"
                class="d-flex flex-wrap align-items-center gap-2 text-center">
                <input type="text" name="search" value="{{ request('search') }}"
                    class="form-control form-control-sm w-auto search-input" placeholder="عنوان یا نام ساکن"
                    style="max-width: 200px;">
                <select name="status" class="form-select form-select-sm search-input" style="max-width: 180px;">
                    <option value="">همه وضعیت‌ها</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>در انتظار بررسی</option>
                    <option value="in_progress" {{ request('status') === 'in_progress' ? 'selected' : '' }}>در حال انجام
                    </option>
                    <option value="done" {{ request('status') === 'done' ? 'selected' : '' }}>انجام شده</option>
                </select>
                <button type="submit" class="btn btn-sm btn-outline-primary filter-btn">جستجو</button>
                <a href="{{ route('requests.index') }}" class="btn btn-sm btn-outline-secondary filter-btn">حذف فیلتر</a>
            </form>
        </div>
    </div>

    <div class="card admin-table-card">
        <div class="card-body table-responsive">
            @if (session('success'))
                <div class="alert alert-success text-center">{{ session('success') }}</div>
            @endif

            <table class="table table-bordered table-striped align-middle text-center table-units">
                <thead>
                    <tr>
                        <th>ردیف</th>
                        <th>واحد</th>
                        <th>ساکن</th>
                        <th>عنوان</th>
                        <th>تاریخ</th>
                        <th>عملیات</th>
                        <th>وضعیت</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($repairRequests as $index => $request)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $request->unit->unit_number ?? '-' }}</td>
                            <td>{{ $request->user->name ?? '-' }}</td>
                            <td>{{ $request->title }}</td>
                            <td>{{ jdate($request->created_at)->format('Y/m/d') }}</td>
                            <td>
                                <div class="d-flex justify-content-center gap-1">
                                    <a href="{{ route('requests.show', $request->id) }}"
                                        class="btn btn-sm btn-outline-primary" title="مشاهده جزئیات">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                </div>
                            </td>
                            <td>
                                <form action="{{ route('requests.update', $request) }}" method="POST"
                                    class="d-inline text-center">
                                    @csrf
                                    <select name="status" onchange="this.form.submit()"
                                        class="form-select form-select-sm text-center">
                                        <option value="pending" {{ $request->status === 'pending' ? 'selected' : '' }}>در
                                            انتظار بررسی</option>
                                        <option value="in_progress"
                                            {{ $request->status === 'in_progress' ? 'selected' : '' }}>در حال انجام
                                        </option>
                                        <option value="done" {{ $request->status === 'done' ? 'selected' : '' }}>انجام
                                            شده</option>
                                    </select>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-3">هیچ درخواستی ثبت نشده است.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            <div class="mt-3 text-center">
                {{ $repairRequests->withQueryString()->links() }}
            </div>
        </div>
    </div>
@endsection
