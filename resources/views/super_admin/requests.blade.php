@extends('layouts.app')
@section('content')
<div class="card">
    <div class="card-header text-white py-3">
        <h5 class="mb-0"><i class="bi bi-clipboard-check"></i> درخواست‌های ثبت ساختمان</h5>
    </div>
    <div class="card-body table-responsive">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <table class="table table-bordered table-striped align-middle small">
            <thead>
                <tr>
                    <th>#</th>
                    <th>نام ساختمان</th>
                    <th>آدرس</th>
                    <th>مدیر</th>
                    <th>تاریخ ثبت</th>
                    <th>وضعیت</th>
                    <th>عملیات</th>
                </tr>
            </thead>
            <tbody>
                @forelse($requests as $i => $req)
                    <tr>
                        <td>{{ $i + 1 }}</td>
                        <td>{{ $req->building_name }}</td>
                        <td>{{ $req->address }}</td>
                        <td>{{ $req->user->name ?? '---' }}</td>
                        <td>{{ jdate($req->created_at)->format('Y/m/d') }}</td>
                        <td>
                            @if($req->status === 'pending')
                                <span class="badge bg-warning">در انتظار</span>
                            @elseif($req->status === 'approved')
                                <span class="badge bg-success">تأیید شده</span>
                            @else
                                <span class="badge bg-danger">رد شده</span>
                            @endif
                        </td>
                        <td>
                            @if($req->status === 'pending')
                                <form method="POST" action="{{ route('admin.requests.approve', $req->id) }}" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-success btn-sm">تأیید</button>
                                </form>

                                <!-- دکمه رد با مودال -->
                                <button class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#rejectModal{{ $req->id }}">رد</button>

                                <!-- مودال رد -->
                                <div class="modal fade" id="rejectModal{{ $req->id }}" tabindex="-1">
                                    <div class="modal-dialog">
                                        <form method="POST" action="{{ route('admin.requests.reject', $req->id) }}">
                                            @csrf
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">علت رد درخواست</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <textarea name="reason" class="form-control" required></textarea>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="submit" class="btn btn-danger">ثبت رد</button>
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">بستن</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            @else
                                <em class="text-muted">—</em>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center">درخواستی موجود نیست.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
