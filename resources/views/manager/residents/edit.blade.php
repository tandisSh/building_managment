@extends('layouts.app')

@section('content')
<div class="card">
    <div class="card-header">
        <h5>ویرایش اطلاعات ساکن </h5>
    </div>

    <div class="card-body">
        <form action="{{ route('residents.update',$resident->id) }}" method="POST">
            @csrf

            <div class="mb-3">
                <label for="unit_id" class="form-label">انتخاب واحد</label>
                <select name="unit_id" id="unit_id" class="form-select select2">
                    <option value="">-- انتخاب کنید --</option>
                    @foreach($units as $unit)
                        <option value="{{ $unit->id }}">
                            واحد {{ $unit->unit_number }} - طبقه {{ $unit->floor }}
                        </option>
                    @endforeach
                </select>
                @error('unit_id') <div class="text-danger small">{{ $message }}</div> @enderror
            </div>

            <div class="mb-3">
                <label for="name" class="form-label">نام کامل</label>
                <input type="text" name="name" class="form-control" value="{{ old('name',$resident->name) }}">
                @error('name') <div class="text-danger small">{{ $message }}</div> @enderror
            </div>

            <div class="mb-3">
                <label for="phone" class="form-label">شماره موبایل</label>
                <input type="text" name="phone" class="form-control" value="{{ old('phone',$resident->phone) }}">
                @error('phone') <div class="text-danger small">{{ $message }}</div> @enderror
            </div>

            <div class="mb-3">
                <label for="email" class="form-label"> ایمیل</label>
                <input type="text" name="email" class="form-control" value="{{ old('email',$resident->email) }}">
                @error('email') <div class="text-danger small">{{ $message }}</div> @enderror
            </div>

            <div class="mb-3">
                <label for="role" class="form-label">نقش</label>
                <select name="role" class="form-select select2">
                    <option value="resident" {{ old('role') == 'resident' ? 'selected' : '' }}>ساکن (مستاجر)</option>
                    <option value="owner" {{ old('role') == 'owner' ? 'selected' : '' }}>مالک</option>
                </select>
                @error('role') <div class="text-danger small">{{ $message }}</div> @enderror
            </div>

            <div class="mb-3">
                <label for="from_date" class="form-label">تاریخ شروع سکونت</label>
                <input type="date" name="from_date" class="form-control" value="{{ old('from_date',$resident->from_date) }}">
                @error('from_date') <div class="text-danger small">{{ $message }}</div> @enderror
            </div>

            <div class="mb-3">
                <label for="to_date" class="form-label">تاریخ پایان سکونت (اختیاری)</label>
                <input type="date" name="to_date" class="form-control" value="{{ old('to_date',$resident->to_date) }}">
                @error('to_date') <div class="text-danger small">{{ $message }}</div> @enderror
            </div>

            <button type="submit" class="btn btn-primary">ویرایش ساکن</button>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        $('.select2').select2({
            placeholder: "انتخاب کنید",
            width: '100%'
        });
    });
</script>
@endsection
