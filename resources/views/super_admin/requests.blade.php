@extends('layouts.app')

@section('content')
    {{-- هدر بالا: عنوان + دکمه افزودن --}}
    <div class="admin-header d-flex justify-content-between align-items-center mb-3 shadow-sm rounded flex-wrap">
        <h6 class="mb-0 fw-bold text-white text-center">
            <i class="bi bi-building"></i> درخواست‌های ثبت ساختمان‌
        </h6>
    </div>

    {{-- کادر فیلترها و جستجو --}}
    <div class="card search-filter-card mb-3">
        <div class="card-body">
            <form method="GET" action="" class="row g-2 align-items-center text-center">
                <div class="col-auto">
                    <input type="text" name="search" value="{{ request('search') }}"
                        class="form-control form-control-sm w-auto search-input" placeholder="نام ساختمان  "
                        style="max-width: 250px;">
                </div>

                <div class="col-auto">
                    <select name="shared_electricity" class="form-select form-select-sm search-input"
                        style="max-width: 120px;">
                        <option value=""> وضعیت</option>
                        <option value="1" {{ request('') === '1' ? 'selected' : '' }}>تایید شده</option>
                        <option value="0" {{ request('') === '0' ? 'selected' : '' }}>رد شده</option>
                    </select>
                </div>
                <div class="col-auto">
                    <button type="submit" class="btn btn-sm btn-outline-primary filter-btn">اعمال فیلتر</button>
                    <a href="" class="btn btn-sm btn-outline-secondary filter-btn">حذف فیلتر</a>
                </div>
            </form>
        </div>
    </div>

    {{-- جدول ساختمان‌ها --}}
    <div class="card admin-table-card">
        <div class="card-body table-responsive">
            @if (session('success'))
                <div class="alert alert-success text-center">{{ session('success') }}</div>
            @endif

            <table class="table table-bordered table-striped align-middle text-center table-units">
                <thead>
                    <tr>
                        <th>ردیف</th>
                        <th>نام ساختمان</th>
                        <th>آدرس</th>
                        <th>مدیر </th>
                        <th>تاریخ ثبت</th>
                        <th>وضعیت</th>
                        <th>عملیات</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($requests as $i => $req)
                        <tr>


                            <td>{{ $i + 1 }}</td>
                            <td>{{ $req->name }}</td>
                            <td>{{ $req->address }}</td>
                            <td>{{ $req->user->name ?? '---' }}</td>
                            <td>{{ jdate($req->created_at)->format('Y/m/d') }}</td>

                            <td>
                                @if ($req->status === 'pending')
                                    <span class="badge bg-warning">در انتظار</span>
                                @elseif($req->status === 'approved')
                                    <span class="badge bg-success">تأیید شده</span>
                                @else
                                    <span class="badge bg-danger">رد شده</span>
                                @endif
                            </td>
                            <td>
                                @if ($req->status === 'pending')
                                    <form method="POST" action="{{ route('superadmin.requests.approve', $req->id) }}"
                                        class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-success btn-sm">تأیید</button>
                                    </form>

                                    <!-- دکمه رد با مودال -->
                                    <button class="btn btn-danger btn-sm" data-bs-toggle="modal"
                                        data-bs-target="#rejectModal{{ $req->id }}">رد</button>

                                    <!-- مودال رد -->
                                    <div class="modal fade" id="rejectModal{{ $req->id }}" tabindex="-1">
                                        <div class="modal-dialog">
                                            <form method="POST"
                                                action="{{ route('superadmin.requests.reject', $req->id) }}">
                                                @csrf
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">علت رد درخواست</h5>
                                                        <button type="button" class="btn-close"
                                                            data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <textarea name="reason" class="form-control"></textarea>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="submit" class="btn btn-danger">ثبت رد</button>
                                                        <button type="button" class="btn btn-secondary"
                                                            data-bs-dismiss="modal">بستن</button>
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
