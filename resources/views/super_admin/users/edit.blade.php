@extends('layouts.app')

  @section('content')
      <div class="container mt-3">
          <div class="admin-header d-flex justify-content-between align-items-center mb-4" style="background-color: #4e3cb3;">
              <h6 class="mb-0 fw-bold text-white py-2 px-3">
                  <i class="bi bi-pencil-square me-2"></i>ویرایش کاربر
              </h6>
          </div>

          <div class="admin-table-card p-4">
              <form action="{{ route('superadmin.users.update', $user->id) }}" method="POST">
                  @csrf
                  @method('PUT')
                  <input type="hidden" name="user_type" value="{{ $user->roles->first()->id === 2 ? 'manager' : 'normal' }}">

                  @if ($user->roles->first()->id === 2)
                      <!-- فرم ویرایش مدیر -->
                      <div class="row g-3">
                          <div class="col-md-6">
                              <label class="form-label small">نام و نام خانوادگی</label>
                              <input type="text" name="name" class="form-control form-control-sm" value="{{ old('name', $user->name) }}" required>
                          </div>
                          <div class="col-md-6">
                              <label class="form-label small">ایمیل</label>
                              <input type="email" name="email" class="form-control form-control-sm" value="{{ old('email', $user->email) }}">
                          </div>
                          <div class="col-md-6">
                              <label class="form-label small">شماره موبایل</label>
                              <input type="text" name="phone" class="form-control form-control-sm" value="{{ old('phone', $user->phone) }}" required>
                          </div>
                          <div class="col-md-6">
                              <label class="form-label small">ساختمان</label>
                              <select name="building_id" class="form-select form-select-sm" required>
                                  <option value="">انتخاب ساختمان</option>
                                  @foreach($freeBuildings as $building)
                                      <option value="{{ $building->id }}" {{ old('building_id', $user->buildingUsers->first()->building_id ?? '') == $building->id ? 'selected' : '' }}>
                                          {{ $building->name }}
                                      </option>
                                  @endforeach
                              </select>
                          </div>
                          <div class="col-12 mt-3">
                              <button type="submit" class="btn btn-sm btn-primary w-100 py-2">
                                  <i class="bi bi-check-circle me-1"></i> به‌روزرسانی مدیر
                              </button>
                          </div>
                      </div>
                  @else
                      <!-- فرم ویرایش کاربر عادی -->
                      <div class="row g-3">
                          <div class="col-md-6">
                              <label class="form-label small">نام و نام خانوادگی</label>
                              <input type="text" name="name" class="form-control form-control-sm" value="{{ old('name', $user->name) }}" required>
                          </div>
                          <div class="col-md-6">
                              <label class="form-label small">ایمیل</label>
                              <input type="email" name="email" class="form-control form-control-sm" value="{{ old('email', $user->email) }}">
                          </div>
                          <div class="col-md-6">
                              <label class="form-label small">شماره موبایل</label>
                              <input type="text" name="phone" class="form-control form-control-sm" value="{{ old('phone', $user->phone) }}" required>
                          </div>
                          <div class="col-md-6">
                              <label class="form-label small">ساختمان</label>
                              <select name="building_id" id="building_id" class="form-select form-select-sm" required>
                                  <option value="">انتخاب ساختمان</option>
                                  @foreach($buildings as $building)
                                      <option value="{{ $building->id }}" {{ old('building_id', $user->units->first()->building_id ?? '') == $building->id ? 'selected' : '' }}>
                                          {{ $building->name }}
                                      </option>
                                  @endforeach
                              </select>
                          </div>
                          <div class="col-md-6">
                              <label class="form-label small">واحد</label>
                              <select name="unit_id" id="unit_id" class="form-select form-select-sm" required>
                                  <option value="">ابتدا ساختمان را انتخاب کنید</option>
                              </select>
                          </div>
                          <div class="col-md-6">
                              <label class="form-label small">نقش</label>
                              <select name="role" id="role_select" class="form-select form-select-sm" required>
                                  <option value="">انتخاب نقش</option>
                                  <option value="resident" {{ old('role', $user->unitUsers->where('role', 'resident')->first() ? 'resident' : '') }}>ساکن</option>
                                  <option value="owner" {{ old('role', $user->unitUsers->where('role', 'owner')->first() ? 'owner' : '') }}>مالک</option>
                                  <option value="both" {{ old('role', $user->unitUsers->where('role', 'resident')->first() && $user->unitUsers->where('role', 'owner')->first() ? 'both' : '') }}>ساکن و مالک</option>
                              </select>
                          </div>
                          <div class="col-md-6">
                              <label class="form-label small">تعداد افراد خانوار</label>
                              <input type="number" name="resident_count" class="form-control form-control-sm" id="resident_count" value="{{ old('resident_count', $user->unitUsers->where('role', 'resident')->first()->resident_count ?? '') }}" disabled>
                          </div>
                          <div class="col-md-6">
                              <label class="form-label small">تاریخ شروع سکونت</label>
                              <input type="date" name="from_date" class="form-control form-control-sm" id="from_date" value="{{ old('from_date', $user->unitUsers->where('role', 'resident')->first()->from_date ?? '') }}" required>
                          </div>
                          <div class="col-md-6">
                              <label class="form-label small">تاریخ پایان سکونت</label>
                              <input type="date" name="to_date" class="form-control form-control-sm" id="to_date" value="{{ old('to_date', $user->unitUsers->where('role', 'resident')->first()->to_date ?? '') }}" disabled>
                          </div>
                          <div class="col-12 mt-3">
                              <button type="submit" class="btn btn-sm btn-primary w-100 py-2">
                                  <i class="bi bi-check-circle me-1"></i> به‌روزرسانی کاربر
                              </button>
                          </div>
                      </div>
                  @endif
              </form>
          </div>
      </div>

      @push('scripts')
      <script>
          document.addEventListener('DOMContentLoaded', function () {
              const buildingSelect = document.getElementById('building_id');
              const unitSelect = document.getElementById('unit_id');
              const roleSelect = document.getElementById('role_select');
              const residentCount = document.getElementById('resident_count');
              const toDate = document.getElementById('to_date');

              // لود اولیه واحد بر اساس ساختمان کاربر
              const initialBuildingId = '{{ old('building_id', $user->units->first()->building_id ?? '') }}';
              if (initialBuildingId) {
                  buildingSelect.value = initialBuildingId;
                  fetch(`{{ route('superadmin.users.getBuildingUnits', ['building' => ':buildingId']) }}`.replace(':buildingId', initialBuildingId))
                      .then(response => response.json())
                      .then(data => {
                          unitSelect.innerHTML = '<option value="">انتخاب واحد</option>';
                          if (data.error) {
                              console.error('Server Error:', data.error);
                          } else {
                              data.forEach(unit => {
                                  unitSelect.innerHTML += `<option value="${unit.id}" ${old('unit_id', $user->units->first()->id ?? '') == unit.id ? 'selected' : ''}>واحد ${unit.unit_number} - طبقه ${unit.floor}</option>`;
                              });
                          }
                      })
                      .catch(error => console.error('Error fetching units:', error));
              }

              buildingSelect.addEventListener('change', function () {
                  const buildingId = this.value;
                  if (buildingId) {
                      fetch(`{{ route('superadmin.users.getBuildingUnits', ['building' => ':buildingId']) }}`.replace(':buildingId', buildingId))
                          .then(response => response.json())
                          .then(data => {
                              unitSelect.innerHTML = '<option value="">انتخاب واحد</option>';
                              if (data.error) {
                                  console.error('Server Error:', data.error);
                              } else {
                                  data.forEach(unit => {
                                      unitSelect.innerHTML += `<option value="${unit.id}">واحد ${unit.unit_number} - طبقه ${unit.floor}</option>`;
                                  });
                              }
                          })
                          .catch(error => console.error('Error fetching units:', error));
                  } else {
                      unitSelect.innerHTML = '<option value="">ابتدا ساختمان را انتخاب کنید</option>';
                  }
              });

              roleSelect.addEventListener('change', function () {
                  const role = this.value;
                  if (role === 'resident' || role === 'both') {
                      residentCount.disabled = false;
                      residentCount.required = true;
                      toDate.disabled = false;
                      toDate.required = true;
                  } else {
                      residentCount.disabled = true;
                      residentCount.value = '';
                      residentCount.required = false;
                      toDate.disabled = true;
                      toDate.value = '';
                      toDate.required = false;
                  }
              });
          });
      </script>
      @endpush
  @endsection
