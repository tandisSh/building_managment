@extends('layouts.app')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">لیست ساکنین ساختمان</h5>
        <a href="{{ route('residents.create') }}" class="btn btn-primary btn-sm">
            افزودن ساکن جدید
        </a>
    </div>

    <div class="card-body table-responsive">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <table class="table table-bordered table-striped align-middle small">
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
                        <td>{{ $resident->user->name }}</td>
                        <td>{{ $resident->user->phone }}</td>
                        <td>{{ $resident->unit->unit_number }}</td>
                        <td>
                            @if($resident->role === 'owner')
                                <span class="badge bg-success">مالک</span>
                            @else
                                <span class="badge bg-info">ساکن (مستاجر)</span>
                            @endif
                        </td>
                        <td>{{ jdate($resident->created_at)->format('Y/m/d') }}</td>

                        <td>
                            <a href="{{ route('residents.show', $resident->user) }}" class="btn btn-info btn-sm">نمایش</a>

                            <a href="{{ route('residents.edit',$resident->user->id) }}" class="btn btn-warning btn-sm">ویرایش</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center">ساکنی ثبت نشده است.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
