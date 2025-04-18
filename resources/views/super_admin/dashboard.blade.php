@extends('layouts.app')

@section('title', 'داشبورد ادمین')

@section('content')
<div class="card shadow">
    <div class="card-header bg-danger text-white">
        <h5>درخواست‌های pending</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>نام ساختمان</th>
                        <th>مدیر</th>
                        <th>عملیات</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($requests as $req)
                    <tr>
                        <td>{{ $req->building_name }}</td>
                        <td>{{ $req->user->name }}</td>
                        <td>
                            <form action="{{ route('admin.requests.approve', $req->id) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-success">تأیید</button>
                            </form>

                            <button class="btn btn-sm btn-danger" data-bs-toggle="modal"
                                data-bs-target="#rejectModal{{ $req->id }}">
                                رد
                            </button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal های رد درخواست -->
@endsection
