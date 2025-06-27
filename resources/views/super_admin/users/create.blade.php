@extends('layouts.app')

  @section('content')
      <div class="container mt-3">
          <div class="admin-header d-flex justify-content-between align-items-center mb-4" style="background-color: #4e3cb3;">
              <h6 class="mb-0 fw-bold text-white py-2 px-3">
                  <i class="bi bi-person-plus me-2"></i>افزودن کاربر
              </h6>
          </div>

          <div class="admin-table-card p-4">
              <ul class="nav nav-tabs justify-content-center mb-4" id="userTabs" role="tablist">
                  <li class="nav-item">
                      <a class="nav-link active" id="normal-tab" data-bs-toggle="tab" href="#normal" role="tab">کاربر عادی</a>
                  </li>
                  <li class="nav-item">
                      <a class="nav-link" id="manager-tab" data-bs-toggle="tab" href="#manager" role="tab">مدیر ساختمان</a>
                  </li>
              </ul>

              <div class="tab-content">
                  <!-- فرم کاربر عادی -->
                  <div class="tab-pane fade show active" id="normal" role="tabpanel">
                      <form action="{{ route('superadmin.users.store') }}" method="POST">
                          @csrf
                          <input type="hidden" name="user_type" value="normal">

                          <div class="row g-3">
                              <div class="col-md-6">
                                  <label class="form-label small">نام و نام خانوادگی</label>
                                  <input type="text" name="name" class="form-control form-control-sm" required>
                              </div>
                              <div class="col-md-6">
                                  <label class="form-label small">ایمیل</label>
                                  <input type="email" name="email" class="form-control form-control-sm">
                              </div>
                              <div class="col-md-6">
                                  <label class="form-label small">شماره موبایل</label>
                                  <input type="text" name="phone" class="form-control form-control-sm" required>
                              </div>
                              <div class="col-md-6">
                                  <label class="form-label small">ساختمان</label>
                                  <select name="building_id" id="building_id" class="form-select form-select-sm" required>
                                      <option value="">انتخاب ساختمان</option>
                                      @foreach($buildings as $building)
                                          <option value="{{ $building->id }}">{{ $building->name }}</option>
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
                                      <option value="resident">ساکن</option>
                                      <option value="owner">مالک</option>
                                      <option value="both">ساکن و مالک</option>
                                  </select>
                              </div>
                              <div class="col-md-6">
                                  <label class="form-label small">تعداد افراد خانوار</label>
                                  <input type="number" name="resident_count" class="form-control form-control-sm" id="resident_count" disabled>
                              </div>
                              <div class="col-md-6">
                                  <label class="form-label small">تاریخ شروع سکونت</label>
                                  <input type="date" name="from_date" class="form-control form-control-sm" id="from_date" required>
                              </div>
                              <div class="col-md-6">
                                  <label class="form-label small">تاریخ پایان سکونت</label>
                                  <input type="date" name="to_date" class="form-control form-control-sm" id="to_date" disabled>
                              </div>
                              <div class="col-12 mt-3">
                                  <button type="submit" class="btn btn-sm btn-primary w-100 py-2">
                                      <i class="bi bi-check-circle me-1"></i> افزودن کاربر
                                  </button>
                              </div>
                          </div>
                      </form>
                  </div>

                  <!-- فرم مدیر ساختمان -->
                  <div class="tab-pane fade" id="manager" role="tabpanel">
                      <form action="{{ route('superadmin.users.store') }}" method="POST">
                          @csrf
                          <input type="hidden" name="user_type" value="manager">

                          <div class="row g-3">
                              <div class="col-md-6">
                                  <label class="form-label small">نام و نام خانوادگی</label>
                                  <input type="text" name="name" class="form-control form-control-sm" required>
                              </div>
                              <div class="col-md-6">
                                  <label class="form-label small">ایمیل</label>
                                  <input type="email" name="email" class="form-control form-control-sm">
                              </div>
                              <div class="col-md-6">
                                  <label class="form-label small">شماره موبایل</label>
                                  <input type="text" name="phone" class="form-control form-control-sm" required>
                              </div>
                              <div class="col-md-6">
                                  <label class="form-label small">ساختمان</label>
                                  <select name="building_id" class="form-select form-select-sm" required>
                                      <option value="">انتخاب ساختمان</option>
                                      @foreach($freeBuildings as $building)
                                          <option value="{{ $building->id }}">{{ $building->name }}</option>
                                      @endforeach
                                  </select>
                              </div>
                              <div class="col-12 mt-3">
                                  <button type="submit" class="btn btn-sm btn-primary w-100 py-2">
                                      <i class="bi bi-check-circle me-1"></i> ثبت مدیر ساختمان
                                  </button>
                              </div>
                          </div>
                      </form>
                  </div>
              </div>
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
