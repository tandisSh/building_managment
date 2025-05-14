@extends('layouts.app')

@section('content')
    <div class="card">
        <div class="card-header text-white py-3">
            <h5 class="mb-0">واحدهای ساختمان {{ $building->name }}</h5>
        </div>

        <div class="card-body table-responsive">
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <a href="{{ route('units.create', $building->id) }}" class="btn btn-primary mb-3">افزودن واحد جدید</a>

            <table class="table table-bordered table-striped align-middle">
                <thead>
                    <tr>
                        <th>شماره واحد</th>
                        <th>طبقه</th>
                        <th>متراژ</th>
                        {{-- <th>جای پارک</th>
                    <th>انباری</th> --}}
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
                            {{-- <td>{{ $unit->parking_slots }}</td>
                        <td>{{ $unit->storerooms }}</td> --}}
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
                            <td>
                                <a href="{{ route('invoices.single.create', [$unit->id]) }}"
                                    class="btn btn-sm btn-warning">صدور صورتحساب</a>

                                <a href="{{ route('units.show', [$building->id, $unit->id]) }}"
                                    class="btn btn-sm btn-primary">مشاهده</a>

                                <a href="{{ route('units.edit', [$building->id, $unit->id]) }}"
                                    class="btn btn-sm btn-warning">ویرایش</a>

                                <form action="{{ route('units.destroy', [$building->id, $unit->id]) }}" method="POST"
                                    class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-danger"
                                        onclick="return confirm('آیا مطمئنید؟')">حذف</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center">هیچ واحدی ثبت نشده.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
