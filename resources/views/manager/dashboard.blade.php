@extends('layouts.app')

@section('title', 'داشبورد مدیر')

@section('content')
<div class="card shadow">
    <div class="card-header bg-primary text-white">
        <h5>درخواست‌های شما</h5>
    </div>
    <div class="card-body">
        <a href="{{ route('manager.buildings.create') }}" class="btn btn-success mb-3">
            درخواست جدید
        </a>

        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>نام ساختمان</th>
                        <th>وضعیت</th>
                        <th>عملیات</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($requests as $req)
                    <tr>
                        <td>{{ $req->building_name }}</td>
                        <td>
                            <span class="badge bg-{{ $req->status == 'pending' ? 'warning' : ($req->status == 'approved' ? 'success' : 'danger') }}">
                                {{ $req->status }}
                            </span>
                        </td>
                        <td>
                            <a href="#" class="btn btn-sm btn-info">مشاهده</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
