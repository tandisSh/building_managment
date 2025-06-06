@extends('layouts.app')

@section('content')
    <div class="admin-header d-flex justify-content-between align-items-center mb-3 shadow-sm rounded flex-wrap">
        <h6 class="mb-0 fw-bold text-dark"><i class="bi bi-tools"></i> درخواست‌های تعمیر ثبت‌شده</h6>
    </div>
    <div class="tools-box bg-light p-3 rounded mb-3">
        <form method="GET" action="{{ route('requests.index') }}"
            class="d-flex flex-nowrap gap-2 align-items-center flex-wrap flex-md-nowrap w-100">
            <input type="text" name="search" value="{{ request('search') }}" class="form-control form-control-sm"
                placeholder="عنوان یا نام ساکن" style="min-width: 150px; max-width: 200px;">

            <select name="status" class="form-select form-select-sm" style="min-width: 150px; max-width: 180px;">
                <option value="">همه وضعیت‌ها</option>
                <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>در انتظار بررسی</option>
                <option value="in_progress" {{ request('status') === 'in_progress' ? 'selected' : '' }}>در حال انجام
                </option>
                <option value="done" {{ request('status') === 'done' ? 'selected' : '' }}>انجام شده</option>
            </select>

            <button type="submit" class="btn btn-sm btn-outline-primary">جستجو</button>
            <a href="{{ route('requests.index') }}" class="btn btn-sm btn-outline-secondary">حذف فیلتر</a>
        </form>
    </div>


    <div class="card admin-table-card">
        <div class="card-body table-responsive">
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <table class="table table-bordered table-striped align-middle small">
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
                            <td class="text-center">
                                <a href="{{ route('requests.show', $request->id) }}" class="btn btn-sm btn-outline-primary"
                                    title="مشاهده جزئیات">
                                    <i class="bi bi-eye"></i>
                                </a>
                            </td>
                            <td>
                                <form action="{{ route('requests.update', $request) }}" method="POST" class="d-inline">
                                    @csrf
                                    <select name="status" onchange="this.form.submit()" class="form-select form-select-sm">
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
                            <td colspan="7" class="text-center">هیچ درخواستی ثبت نشده است.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            <div class="mt-3">
                {{ $repairRequests->withQueryString()->links() }}
            </div>
        </div>
    </div>
@endsection
