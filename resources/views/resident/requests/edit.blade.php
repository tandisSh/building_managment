@extends('layouts.app')

@section('content')
<div class="container mt-3">
    <div class="admin-header d-flex justify-content-between align-items-center mb-4" style="background-color: #4e3cb3;">
        <h6 class="mb-0 fw-bold text-white py-2 px-3">
            <i class="bi bi-pencil-square me-2"></i>ویرایش درخواست تعمیر
        </h6>
    </div>

    <div class="admin-table-card p-4">
        <form action="{{ route('resident.requests.update', $request->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="row g-3">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label small">عنوان درخواست *</label>
                        <input type="text" name="title" class="form-control form-control-sm" value="{{ $request->title }}" required>
                    </div>
                </div>

                <div class="col-12">
                    <div class="form-group">
                        <label class="form-label small">توضیحات *</label>
                        <textarea name="description" rows="3" class="form-control form-control-sm" required>{{ $request->description }}</textarea>
                    </div>
                </div>

                <div class="col-12 mt-3">
                    <button type="submit" class="btn btn-sm btn-warning w-100 py-2">
                        <i class="bi bi-pencil me-1"></i> ویرایش درخواست
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
