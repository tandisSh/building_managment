@extends('layouts.app')

@section('title', 'مدیریت درخواست‌ها')

@section('content')
<div class="card shadow-lg">
    <div class="card-header bg-danger text-white py-3">
        <h5 class="mb-0"><i class="bi bi-list-check"></i> درخواست‌های ثبت ساختمان</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-light">
                    <tr>
                        <th>ردیف</th>
                        <th>نام ساختمان</th>
                        <th>مدیر</th>
                        <th>تاریخ ثبت</th>
                        <th>وضعیت</th>
                        <th>عملیات</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($requests as $request)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $request->building_name }}</td>
                        <td>{{ $request->user->name }}</td>
                        <td>{{ $request->created_at->toJalali()->format('Y/m/d') }}</td>
                        <td>
                            <span class="badge bg-{{ $request->status == 'pending' ? 'warning' : ($request->status == 'approved' ? 'success' : 'danger') }}">
                                {{ $request->status }}
                            </span>
                        </td>
                        <td>
                            <div class="d-flex gap-2">
                                <form method="POST" action="{{ route('admin.requests.approve', $request->id) }}">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-success">تایید</button>
                                </form>
                                <button class="btn btn-sm btn-danger" data-bs-toggle="modal"
                                    data-bs-target="#rejectModal{{ $request->id }}">رد</button>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal های رد درخواست -->
@foreach($requests as $request)
<div class="modal fade" id="rejectModal{{ $request->id }}" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">رد درخواست</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="{{ route('admin.requests.reject', $request->id) }}">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="reason" class="form-label">دلیل رد درخواست</label>
                        <textarea class="form-control" id="reason" name="reason" rows="3" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">انصراف</button>
                    <button type="submit" class="btn btn-danger">تایید رد درخواست</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach
@endsection
