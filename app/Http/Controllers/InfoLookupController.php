<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\AssetGroup;
use App\Models\User;
use Illuminate\Http\Request;

class InfoLookupController extends Controller
{
    /**
     * Tra cứu tài sản theo nhân sự (Mã NV, Phòng ban, Trung tâm)
     */
    public function assets(Request $request)
    {
        $employeeCode = $request->get('employee_code');
        $department = $request->get('department');
        $center = $request->get('center');
        
        $query = Asset::with(['group.parent.parent', 'user']);

        // Nếu có thông tin tra cứu nhân sự/tổ chức
        if ($employeeCode || $department || $center) {
            $query->where(function($q) use ($employeeCode, $department, $center) {
                // Lọc theo người dùng đang gán
                $q->whereHas('user', function($userQuery) use ($employeeCode, $department, $center) {
                    if ($employeeCode) $userQuery->where('employee_code', 'like', "%{$employeeCode}%");
                    if ($department) $userQuery->where('department', 'like', "%{$department}%");
                    if ($center) $userQuery->where('center', 'like', "%{$center}%");
                });

                // HOẶC lọc trực tiếp trên bảng Assets (gán cho tập thể)
                if ($department) {
                    $q->orWhere('assigned_department', 'like', "%{$department}%");
                }
                if ($center) {
                    $q->orWhere('assigned_center', 'like', "%{$center}%");
                }
            });
        } elseif (!$request->has('search_all')) {
            $query->where('id', '=', 0); 
        }

        $assets = $query->paginate(20)->withQueryString();
        
        return view('info_lookup.assets', compact('assets', 'employeeCode', 'department', 'center'));
    }

    /**
     * Tra cứu thông tin tồn kho
     */
    public function inventory(Request $request)
    {
        $search = $request->get('search');
        $groupId = $request->get('group_id');

        $query = Asset::with(['group.parent.parent'])
            ->where('status', '=', 'inventory');

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%");
            });
        }

        if ($groupId) {
            $query->where('group_id', '=', $groupId);
        }

        $assets = $query->paginate(20)->withQueryString();
        $groups = AssetGroup::whereNull('parent_id')->with('children.children')->get();

        return view('info_lookup.inventory', compact('assets', 'groups', 'search', 'groupId'));
    }
}
