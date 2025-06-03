@extends('layouts.app')

@section('content')

    <div class="admin-header d-flex justify-content-between align-items-center mb-3 shadow-sm rounded flex-wrap">
        <h6 class="mb-0 fw-bold text-dark">واحدهای ساختمان {{ $building->name }}</h6>

        <div class="tools-box">
            <input type="text" class="form-control form-control-sm search-input" placeholder="جستجو..." />
            <button class="btn btn-sm filter-btn">فیلتر</button>
            <a href="{{ route('units.create', $building->id) }}" class="btn btn-sm add-btn">افزودن واحد جدید</a>
        </div>
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
