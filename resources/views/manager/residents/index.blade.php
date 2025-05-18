@extends('layouts.app')

@section('content')
    <div class="admin-header d-flex justify-content-between align-items-center mb-3 shadow-sm rounded flex-wrap">
        <h6 class="mb-0 fw-bold text-dark">لیست ساکنین ساختمان</h6>

        <div class="tools-box">
            <input type="text" class="form-control form-control-sm search-input" placeholder="جستجو..." />
            <button class="btn btn-sm filter-btn">فیلتر</button>
            <a href="{{ route('residents.create') }}" class="btn btn-sm add-btn">افزودن ساکن</a>
        </div>
    </div>

    <div class="card admin-table-card">
        <div class="card-body table-responsive">
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <table class="table table-bordered table-striped align-middle small table-units">
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
                                @if ($resident->role === 'owner')
                                    <span class="badge bg-success">مالک</span>
                                @else
                                    <span class="badge bg-info">ساکن (مستاجر)</span>
                                @endif
                            </td>
                            <td>{{ jdate($resident->created_at)->format('Y/m/d') }}</td>
                            <td>
                                <a href="{{ route('residents.show', $resident->user) }}"
                                    class="btn btn-sm btn-outline-primary" title="نمایش">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('residents.edit', $resident->user->id) }}"
                                    class="btn btn-sm btn-outline-warning" title="ویرایش">
                                    <i class="bi bi-pencil-square"></i>
                                </a>
                                <form action="{{ route('residents.destroy', $resident->user->id) }}" method="POST"
                                    class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger" title="حذف"
                                        onclick="return confirm('آیا مطمئنید؟')" title="حذف">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>

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
