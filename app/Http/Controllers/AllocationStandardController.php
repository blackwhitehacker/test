<?php

namespace App\Http\Controllers;

use App\Models\AllocationStandard;
use App\Models\AssetGroup;
use Illuminate\Http\Request;

class AllocationStandardController extends Controller
{
    public function index()
    {
        $standards = AllocationStandard::with('assetGroup')->latest()->paginate(20);
        return view('allocation_standards.index', compact('standards'));
    }

    public function create()
    {
        $groups = AssetGroup::all();
        return view('allocation_standards.create', compact('groups'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'asset_group_id' => 'required|exists:asset_groups,id',
            'target_type' => 'required|in:individual,department,center',
            'target_name' => 'required|string|max:255',
            'limit_quantity' => 'required|integer|min:1',
            'notes' => 'nullable|string',
        ]);

        AllocationStandard::create($validated);

        return redirect()->route('allocation_standards.index')
            ->with('success', 'Định mức cấp phát đã được tạo thành công.');
    }

    public function edit(AllocationStandard $allocationStandard)
    {
        $groups = AssetGroup::all();
        return view('allocation_standards.edit', compact('allocationStandard', 'groups'));
    }

    public function update(Request $request, AllocationStandard $allocationStandard)
    {
        $validated = $request->validate([
            'asset_group_id' => 'required|exists:asset_groups,id',
            'target_type' => 'required|in:individual,department,center',
            'target_name' => 'required|string|max:255',
            'limit_quantity' => 'required|integer|min:1',
            'notes' => 'nullable|string',
        ]);

        $allocationStandard->update($validated);

        return redirect()->route('allocation_standards.index')
            ->with('success', 'Định mức cấp phát đã được cập nhật.');
    }

    public function destroy(AllocationStandard $allocationStandard)
    {
        $allocationStandard->delete();
        return redirect()->route('allocation_standards.index')
            ->with('success', 'Định mức cấp phát đã được xóa.');
    }
}
