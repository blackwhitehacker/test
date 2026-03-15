<?php

namespace App\Traits;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Request;

trait LogsActivity
{
    public static function bootLogsActivity()
    {
        static::created(function ($model) {
            $model->logAction('create', null, $model->getAttributes());
        });

        static::updated(function ($model) {
            $changes = $model->getChanges();
            // Remove timestamps if they are the only changes
            unset($changes['updated_at']);
            
            if (empty($changes)) return;

            $old = array_intersect_key($model->getOriginal(), $changes);
            $model->logAction('update', $old, $changes);
        });

        static::deleted(function ($model) {
            $model->logAction('delete', $model->getAttributes(), null);
        });
    }

    public function logAction($action, $oldValues = null, $newValues = null, $description = null)
    {
        if (!$description) {
            $actionLabels = [
                'create' => 'Khởi tạo bản ghi mới',
                'update' => 'Cập nhật thông tin bản ghi',
                'delete' => 'Xóa bản ghi khỏi hệ thống',
                'approve' => 'Phê duyệt yêu cầu',
                'reject' => 'Từ chối yêu cầu',
                'cancel' => 'Hủy bỏ yêu cầu',
                'escalate' => 'Chuyển cấp thẩm quyền phê duyệt',
            ];
            $description = $actionLabels[$action] ?? "Thực hiện hành động: $action";
        }

        ActivityLog::create([
            'user_id' => auth()->id() ?? 1,
            'action' => $action,
            'description' => $description,
            'model_type' => get_class($this),
            'model_id' => $this->id,
            'old_values' => $oldValues,
            'new_values' => $newValues,
            'ip_address' => Request::ip(),
        ]);
    }
}
