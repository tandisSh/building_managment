@extends('layouts.app')

@section('content')
    <div class="admin-header d-flex justify-content-between align-items-center mb-3 shadow-sm rounded flex-wrap">
        <h6 class="mb-0 fw-bold text-dark"><i class="bi bi-tools"></i> درخواست‌های تعمیر ثبت‌شده من</h6>
        <div class="tools-box">
            <input type="text" class="form-control form-control-sm search-input" placeholder="جستجو..." />
            <button class="btn btn-sm filter-btn">فیلتر</button>
            <a href="{{ route('resident.requests.create') }}" class="btn btn-sm add-btn">افزودن درخواست جدید</a>
        </div>
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
                        <th>عنوان</th>
                        <th>توضیحات</th>
                        <th>وضعیت</th>
                        <th>تاریخ ثبت</th>
                        <th>عملیات</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($requests as $index => $request)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $request->title }}</td>
                            <td>{{ Str::limit($request->description, 50) }}</td>
                            <td>
                                <span
                                    class="badge bg-{{ $request->status === 'done' ? 'success' : ($request->status === 'in_progress' ? 'info' : 'warning') }}">
                                    {{ $request->status === 'pending'
                                        ? 'در انتظار بررسی'
                                        : ($request->status === 'in_progress'
                                            ? 'در حال انجام'
                                            : 'انجام‌شده') }}
                                </span>
                            </td>
                            <td>{{ jdate($request->created_at)->format('Y/m/d') }}</td>
                            <td>
                                @if ($request->status === 'pending')
                                    <a href="{{ route('resident.requests.edit', $request->id) }}"
                                        class="btn btn-sm btn-outline-warning" title="ویرایش">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>
                                @else
                                    <span class="text-muted small">غیرقابل ویرایش</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">هیچ درخواستی ثبت نشده است.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
