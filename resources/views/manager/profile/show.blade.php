@extends('layouts.app')

@section('content')
    <div class="container-fluid mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
                <div class="card card-primary card-outline mb-4 shadow-sm">
                    <div class="card-header text-white text-center" style="background-color: #4e3cb3">
                        <h3 class="card-title mb-0">پروفایل مدیر</h3>
                    </div>
                    <div class="card-body text-center">
                        <div class="mb-3">
                            <img src="{{ asset('images/avatar.jpg') }}" class="rounded-circle shadow-sm" width="120"
                                height="120" alt="Profile Picture">
                        </div>

                        <table class="table table-bordered">
                            <tr>
                                <th>نام:</th>
                                <td>{{ $user->name }}</td>
                            </tr>
                            <tr>
                                <th>ایمیل:</th>
                                <td>{{$user->email }}</td>
                            </tr>
                            <tr>
                                <th>شماره تلفن:</th>
                                <td>{{ $user->phone }}</td>
                            </tr>
                             <tr>
                                <th> تاریخ عضویت:</th>
                                <td>{{ jdate($user->created_at)->format('Y/m/d') }}</td>
                            </tr>
                            <tr>
                                <th>نقش:</th>
                                <td>
                                    @foreach($user->roles as $role)
                                        <span class="badge bg-primary">{{ $role->name }}</span>
                                    @endforeach
                                </td>
                            </tr>
                        </table>
                        <div class="card-footer bg-light d-flex justify-content-center">
                            <button type="submit" class="btn btn-lg" style="background-color: #4e3cb3">
                                <a class="text-white" href="{{ route('manager.profile.edit') }}">ویرایش پروفایل</a>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection 