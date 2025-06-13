<?php

namespace App\Services\Admin\BuildingManager;

use App\Models\Building;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;

class BuildingManagerService
{
    /**
     * دریافت لیست ساختمان‌ها همراه با مدیر آن‌ها، با فیلتر جستجو
     */
    public function getBuildingsWithManagers(array $filters = [])
    {
        $query = Building::with('manager');

        if (!empty($filters['search'])) {
            $query->where(function ($q) use ($filters) {
                $q->where('name', 'LIKE', '%' . $filters['search'] . '%')
                    ->orWhere('id', 'LIKE', '%' . $filters['search'] . '%');
            });
        }

        if (!empty($filters['manager_id'])) {
            $query->where('manager_id', $filters['manager_id']);
        } else {
            // اگر فیلتر مدیر مشخص نشده، فقط ساختمان‌هایی که مدیر دارند رو بیار
            $query->whereNotNull('manager_id');
        }

        $buildings = $query->paginate(15);

        // اینجا همه مدیران با نقش manager رو میاریم (چه ساختمان داشته باشند چه نه)
        $managers = User::whereHas('roles', function (Builder $q) {
            $q->where('name', 'manager');
        })->orderBy('name')->get();

        return compact('buildings', 'managers');
    }


    /**
     * دریافت ساختمان خاص و لیست مدیران موجود برای انتخاب در فرم ویرایش
     */
    public function getBuildingWithAvailableManagers(int $buildingId)
    {
        $building = Building::findOrFail($buildingId);

        $managers = $this->getAvailableManagers($building->manager_id);

        return compact('building', 'managers');
    }

    /**
     * گرفتن مدیرانی که به ساختمان دیگری اختصاص ندارند، به جز مدیر فعلی
     * اگر $currentManagerId ارسال شود، آن مدیر نیز شامل لیست است (برای فرم ویرایش)
     */
    protected function getAvailableManagers(?int $currentManagerId = null)
    {
        // آیدی مدیرانی که در جدول ساختمان ها به عنوان manager_id انتخاب شده اند
        $assignedManagerIds = Building::whereNotNull('manager_id')
            ->when($currentManagerId, function ($query) use ($currentManagerId) {
                $query->where('manager_id', '!=', $currentManagerId);
            })
            ->pluck('manager_id')
            ->toArray();

        // مدیرانی که نقش manager دارند و به ساختمان دیگری اختصاص داده نشده اند
        $query = User::whereHas('roles', function (Builder $q) {
            $q->where('name', 'manager');
        })->whereNotIn('id', $assignedManagerIds);

        // اگر مدیر فعلی وجود دارد، او را به لیست اضافه کن
        if ($currentManagerId) {
            $query->orWhere('id', $currentManagerId);
        }

        return $query->orderBy('name')->get();
    }

    /**
     * به‌روزرسانی مدیر یک ساختمان با چک کردن تکراری نبودن
     */
    public function updateBuildingManager(int $buildingId, int $managerId): void
    {
        // چک کنیم که مدیر مورد نظر به ساختمان دیگری اختصاص ندارد (غیر از همین ساختمان)
        $exists = Building::where('manager_id', $managerId)
            ->where('id', '!=', $buildingId)
            ->exists();

        if ($exists) {
            throw new \Exception('این مدیر قبلاً به ساختمان دیگری اختصاص داده شده است.');
        }

        $building = Building::findOrFail($buildingId);
        $building->manager_id = $managerId;
        $building->save();
    }
}
