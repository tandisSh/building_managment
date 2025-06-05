@extends('layouts.app')

@section('content')

    <div class="admin-header d-flex justify-content-between align-items-center mb-3 shadow-sm rounded flex-wrap">
        <h6 class="mb-0 fw-bold text-dark">واحدهای ساختمان {{ $building->name }}</h6>

   <form method="GET" class="d-flex align-items-center gap-2 mb-3" style="flex-wrap: wrap;">
    <input type="text" name="search" class="form-control form-control-sm w-auto"
           placeholder="شماره واحد یا نام ساکن" value="{{ request('search') }}" style="max-width: 200px;">

    <button class="btn btn-sm btn-outline-primary">جستجو</button>

    <a href="{{ route('units.index', $building->id) }}" class="btn btn-sm btn-outline-secondary">ریست</a>

    <a href="{{ route('units.create', $building->id) }}" class="btn btn-sm add-btn">+ افزودن واحد</a>
</form>


    </div>


    <div class="card admin-table-card">
        <div class="card-body table-responsive">
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <table class="table table-hover align-middle text-center table-units">
                <thead>
                    <tr>
                        <th>شماره واحد</th>
                        <th>طبقه</th>
                        <th>متراژ</th>
                        <th>ساکنین</th>
                        <th>عملیات</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($units as $unit)
                        <tr>
                            <td>{{ $unit->unit_number }}</td>
                            <td>{{ $unit->floor ?? '-' }}</td>
                            <td>{{ $unit->area ?? '-' }}</td>
                            <td>
                                @if ($unit->users && $unit->users->count())
                                    @foreach ($unit->users as $resident)
                                        <div>{{ $resident->name }}
                                            ({{ $resident->pivot->role == 'owner' ? 'مالک' : 'ساکن' }})
                                        </div>
                                    @endforeach
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>
                            <td class="d-flex justify-content-center gap-2 flex-wrap">
                                <a href="{{ route('manager.units.invoices', $unit->id) }}"
                                    class="btn btn-sm btn-outline-secondary" title="مشاهده صورتحساب ها">
                                    <i class="bi bi-file-earmark-plus"></i>
                                </a>
                                <a href="{{ route('units.show', [$building->id, $unit->id]) }}"
                                    class="btn btn-sm btn-outline-primary" title="مشاهده">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('units.edit', [$building->id, $unit->id]) }}"
                                    class="btn btn-sm btn-outline-warning" title="ویرایش">
                                    <i class="bi bi-pencil-square"></i>
                                </a>
                                <form action="{{ route('units.destroy', [$building->id, $unit->id]) }}" method="POST"
                                    class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger" title="حذف"
                                        onclick="return confirm('آیا مطمئنید؟')">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center">هیچ واحدی ثبت نشده.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
