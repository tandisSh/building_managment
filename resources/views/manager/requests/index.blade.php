@extends('layouts.app')

@section('content')
    <div class="admin-header d-flex justify-content-between align-items-center mb-3 shadow-sm rounded flex-wrap">
        <h6 class="mb-0 fw-bold text-dark"><i class="bi bi-tools"></i> درخواست‌های تعمیر ثبت‌شده</h6>
    </div>

    <div class="card admin-table-card">
        <div class="card-body table-responsive">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <table class="table table-bordered table-striped align-middle small">
                <thead>
                    <tr>
                        <th>ردیف</th>
                        <th>واحد</th>
                        <th>ساکن</th>
                        <th>عنوان</th>
                        <th>توضیحات</th>
                        <th>تاریخ</th>
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
                            <td>{{ Str::limit($request->description, 50) }}</td>
                            <td>{{ jdate($request->created_at)->format('Y/m/d') }}</td>
                            <td>
                                <form action="{{ route('requests.update', $request) }}" method="POST" class="d-inline">
                                    @csrf
                                    <select name="status" onchange="this.form.submit()" class="form-select form-select-sm">
                                        <option value="pending" {{ $request->status === 'pending' ? 'selected' : '' }}>در انتظار بررسی</option>
                                        <option value="in_progress" {{ $request->status === 'in_progress' ? 'selected' : '' }}>در حال انجام</option>
                                        <option value="done" {{ $request->status === 'done' ? 'selected' : '' }}>انجام شده</option>
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
        </div>
    </div>
@endsection
