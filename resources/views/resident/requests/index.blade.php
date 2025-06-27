@extends('layouts.app')

@section('content')
    {{-- هدر بالا: عنوان و دکمه درخواست جدید --}}
    <div class="admin-header d-flex justify-content-between align-items-center mb-3 shadow-sm rounded flex-wrap">
        <h6 class="mb-0 fw-bold text-white"><i class="bi bi-tools"></i> درخواست‌های تعمیر ثبت‌شده من</h6>

        <a href="{{ route('resident.requests.create') }}" class="btn add-btn">
            <i class="bi bi-plus-circle me-1"></i> درخواست جدید
        </a>
    </div>

    {{-- کادر فیلترها --}}
    <div class="card mb-3 shadow-sm rounded">
        <div class="card-body">
            <form method="GET" action="{{ route('resident.requests.index') }}" class="row g-2 align-items-center">
                <div class="col-auto">
                    <input type="text" name="search" class="form-control form-control-sm" placeholder="جستجوی عنوان..."
                        value="{{ $search ?? '' }}" />
                </div>
                <div class="col-auto">
                    <select name="status" class="form-select form-select-sm">
                        <option value="">همه وضعیت‌ها</option>
                        <option value="pending" {{ ($status ?? '') === 'pending' ? 'selected' : '' }}>در انتظار بررسی
                        </option>
                        <option value="in_progress" {{ ($status ?? '') === 'in_progress' ? 'selected' : '' }}>در حال انجام
                        </option>
                        <option value="done" {{ ($status ?? '') === 'done' ? 'selected' : '' }}>انجام‌شده</option>
                    </select>
                </div>
                <div class="col-auto">
                    <button type="submit" class="btn btn-sm btn-outline-primary">اعمال فیلتر</button>
                    <a href="{{ route('resident.requests.index') }}" class="btn btn-sm btn-outline-secondary">حذف فیلتر</a>
                </div>
            </form>
        </div>
    </div>

    {{-- جدول درخواست‌ها --}}
    <div class="card admin-table-card">
        <div class="card-body table-responsive">
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <table class="table table-bordered table-striped align-middle  small table-units text-center">
                <thead>
                    <tr>
                        <th>ردیف</th>
                        <th>عنوان</th>
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
                                <a href="{{ route('resident.requests.show', $request->id) }}"
                                    class="btn btn-sm btn-outline-primary" title="مشاهده">
                                    <i class="bi bi-eye"></i>
                                </a>
                                @if ($request->status === 'pending')
                                    <a href="{{ route('resident.requests.edit', $request->id) }}"
                                        class="btn btn-sm btn-outline-warning" title="ویرایش">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>
                                @else
                                    <button disabled class="btn btn-sm btn-outline-warning" title="ویرایش">
                                        <i class="bi bi-pencil-square"></i>
                                    </button>
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
