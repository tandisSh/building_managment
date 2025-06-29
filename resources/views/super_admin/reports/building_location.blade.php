@extends('layouts.app')

@section('content')
    <div class="admin-header d-flex justify-content-between align-items-center mb-3 shadow-sm rounded flex-wrap">
        <h6 class="mb-0 fw-bold text-white text-center"><i class="bi bi-geo-alt"></i> گزارش تفکیک ساختمان‌ها بر اساس استان و شهر</h6>
    </div>

    <!-- کادر فیلترها و جستجو -->
    <div class="card search-filter-card mb-3">
        <div class="card-body">
            <form method="GET" class="row g-2 align-items-center text-center">
                <div class="col-auto">
                    <input type="text" name="search" value="{{ request('search') }}"
                        class="form-control form-control-sm w-auto search-input" placeholder="جستجو در نام، استان یا شهر"
                        style="max-width: 250px;">
                </div>
                <div class="col-auto">
                    <button type="submit" class="btn btn-sm btn-outline-primary filter-btn">جستجو</button>
                    <a href="{{ route('superadmin.reports.building_location') }}" class="btn btn-sm btn-outline-secondary filter-btn">حذف فیلتر</a>
                </div>
            </form>
        </div>
    </div>

    <!-- خلاصه کلی -->
    <div class="row mb-4">
        <div class="col-md-2">
            <div class="card text-center bg-purple-300 text-dark">
                <div class="card-body">
                    <h5 class="card-title">{{ $summary['total_provinces'] }}</h5>
                    <p class="card-text small">کل استان‌ها</p>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card text-center bg-purple-500 text-dark">
                <div class="card-body">
                    <h5 class="card-title">{{ $summary['total_cities'] }}</h5>
                    <p class="card-text small">کل شهرها</p>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card text-center bg-purple-700 text-dark">
                <div class="card-body">
                    <h5 class="card-title">{{ $summary['total_buildings'] }}</h5>
                    <p class="card-text small">کل ساختمان‌ها</p>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card text-center bg-purple-900 text-dark">
                <div class="card-body">
                    <h5 class="card-title">{{ $summary['total_units'] }}</h5>
                    <p class="card-text small">کل واحدها</p>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card text-center bg-purple-100 text-dark">
                <div class="card-body">
                    <h5 class="card-title">{{ $summary['avg_buildings_per_province'] }}</h5>
                    <p class="card-text small">میانگین ساختمان در هر استان</p>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card text-center bg-danger text-dark">
                <div class="card-body">
                    <h5 class="card-title">{{ $summary['avg_buildings_per_city'] }}</h5>
                    <p class="card-text small">میانگین ساختمان در هر شهر</p>
                </div>
            </div>
        </div>
    </div>

    <div class="mb-3 d-flex justify-content-between align-items-center text-center">
        <div>
            <strong>استان با بیشترین ساختمان:</strong> {{ $summary['most_buildings_province']->province ?? 'نامشخص' }} ({{ $summary['most_buildings_province']->total_buildings ?? 0 }} ساختمان)
        </div>
        <div>
            <a href="{{ route('superadmin.reports.building_location.print', request()->query()) }}" target="_blank"
                class="btn btn-info btn-sm">چاپ گزارش</a>
        </div>
    </div>

    <!-- جدول ساختمان‌ها -->
    <div class="card admin-table-card">
        <div class="card-body table-responsive">
            @if (session('success'))
                <div class="alert alert-success text-center">{{ session('success') }}</div>
            @endif

            <table class="table table-bordered table-striped align-middle text-center table-units">
                <thead>
                    <tr>
                        <th>ردیف</th>
                        <th>نام ساختمان</th>
                        <th>استان</th>
                        <th>شهر</th>
                        <th>آدرس</th>
                        <th>تعداد واحدها</th>
                        <th>تعداد طبقات</th>
                        <th>مدیر</th>
                        <th>وضعیت</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($buildings as $index => $building)
                        <tr>
                            <td>{{ $index + 1 + ($buildings->currentPage() - 1) * $buildings->perPage() }}</td>
                            <td>{{ $building->name }}</td>
                            <td>{{ $building->province ?? 'تعریف نشده' }}</td>
                            <td>{{ $building->city ?? 'تعریف نشده' }}</td>
                            <td class="text-truncate" style="max-width: 200px;">{{ $building->address }}</td>
                            <td>{{ $building->number_of_units }}</td>
                            <td>{{ $building->number_of_floors }}</td>
                            <td>{{ $building->manager->name ?? 'تعیین نشده' }}</td>
                            <td>
                                @if($building->manager_id)
                                    <span class="badge bg-success">دارای مدیر</span>
                                @else
                                    <span class="badge bg-warning">بدون مدیر</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center">هیچ ساختمانی یافت نشد.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="mt-3 d-flex justify-content-center">
                {{ $buildings->withQueryString()->links() }}
            </div>
        </div>
    </div>

    <!-- نمودار مقایسه‌ای -->
    <div class="row mt-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0"><i class="bi bi-bar-chart"></i> مقایسه تعداد ساختمان‌ها در استان‌ها</h6>
                </div>
                <div class="card-body">
                    <canvas id="buildingsChart" width="400" height="200"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0"><i class="bi bi-pie-chart"></i> توزیع ساختمان‌ها در استان‌ها</h6>
                </div>
                <div class="card-body">
                    <canvas id="utilitiesChart" width="400" height="200"></canvas>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // نمودار تعداد ساختمان‌ها
    const buildingsCtx = document.getElementById('buildingsChart').getContext('2d');
    const buildingsData = @json($province_performance->take(10));
    
    new Chart(buildingsCtx, {
        type: 'bar',
        data: {
            labels: buildingsData.map(item => item.province),
            datasets: [{
                label: 'تعداد ساختمان‌ها',
                data: buildingsData.map(item => item.total_buildings),
                backgroundColor: 'rgba(54, 162, 235, 0.8)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true
                }
            },
            plugins: {
                legend: {
                    display: false
                }
            }
        }
    });

    // نمودار توزیع
    const utilitiesCtx = document.getElementById('utilitiesChart').getContext('2d');
    const utilitiesData = @json($province_performance->take(5));
    
    new Chart(utilitiesCtx, {
        type: 'doughnut',
        data: {
            labels: utilitiesData.map(item => item.province),
            datasets: [{
                data: utilitiesData.map(item => item.total_buildings),
                backgroundColor: [
                    'rgba(255, 99, 132, 0.8)',
                    'rgba(54, 162, 235, 0.8)',
                    'rgba(255, 205, 86, 0.8)',
                    'rgba(75, 192, 192, 0.8)',
                    'rgba(153, 102, 255, 0.8)'
                ],
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });
});
</script>
@endpush 