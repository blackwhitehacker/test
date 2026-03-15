<?php

namespace App\Http\Controllers;

use App\Models\AssetGroup;
use Illuminate\Http\Request;

class AssetGroupController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->get('search');
        
        // We want to show a flattened list like Excel for the 4-level view
        // Level 1: Categories (Loại)
        // Level 2: Groups (Nhóm)
        // Level 3: Lines (Dòng)
        // Level 4: Assets (Tài sản cụ thể)
        
        $query = AssetGroup::whereNull('parent_id')
            ->with(['children.children.assets', 'children.children.children']); // Nested structure
            
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%")
                  ->orWhereHas('children', function($q2) use ($search) {
                      $q2->where('name', 'like', "%{$search}%")
                        ->orWhereHas('children', function($q3) use ($search) {
                            $q3->where('name', 'like', "%{$search}%")
                              ->orWhereHas('assets', function($q4) use ($search) {
                                  $q4->where('name', 'like', "%{$search}%");
                              });
                        });
                  });
            });
        }

        $groups = $query->get();
            
        return view('asset_groups.index', compact('groups', 'search'));
    }

    public function create()
    {
        // For the 4-level quick add
        $categories = AssetGroup::whereNull('parent_id')->get();
        $existingGroups = AssetGroup::whereNotNull('parent_id')
            ->whereHas('parent', function($q) {
                $q->whereNull('parent_id');
            })
            ->pluck('name')
            ->unique();
            
        $existingLines = AssetGroup::whereNotNull('parent_id')
            ->whereHas('parent', function($q) {
                $q->whereNotNull('parent_id');
            })
            ->pluck('name')
            ->unique();

        return view('asset_groups.create', compact('categories', 'existingGroups', 'existingLines'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:asset_groups,id',
            'group_name' => 'required|string|max:255',
            'line_name' => 'required|string|max:255',
            'asset_name' => 'nullable|string|max:255', // Level 4
        ]);

        // Find or Create Level 2 (Group)
        $category = AssetGroup::findOrFail($request->category_id);
        $group = AssetGroup::firstOrCreate([
            'name' => $request->group_name,
            'parent_id' => $category->id
        ], [
            'tracking_type' => $category->tracking_type
        ]);

        // Find or Create Level 3 (Line)
        $line = AssetGroup::firstOrCreate([
            'name' => $request->line_name,
            'parent_id' => $group->id
        ], [
            'tracking_type' => $category->tracking_type
        ]);

        // If Asset Name is provided, create a dummy asset or redirect to asset creation
        if ($request->asset_name) {
            \App\Models\Asset::create([
                'name' => $request->asset_name,
                'group_id' => $line->id,
                'status' => 'inventory', // Default
                'purchase_price' => 0,
                'usage_months' => 36,
            ]);
            return redirect()->route('asset_groups.index')->with('success', 'Đã tạo danh mục và tài sản cụ thể thành công.');
        }

        return redirect()->route('asset_groups.index')->with('success', 'Danh mục tài sản đã được cập nhật.');
    }

    public function edit(AssetGroup $assetGroup)
    {
        $parents = AssetGroup::where('id', '!=', $assetGroup->id)->get();
        return view('asset_groups.edit', compact('assetGroup', 'parents'));
    }

    public function update(Request $request, AssetGroup $assetGroup)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'parent_id' => 'nullable|exists:asset_groups,id',
            'tracking_type' => 'nullable|string|in:quantity,serialized',
        ]);

        // Update children's tracking type if parent changes? 
        // For simplicity, root defines it.
        $assetGroup->update($validated);

        return redirect()->route('asset_groups.index')->with('success', 'Danh mục tài sản đã được cập nhật.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AssetGroup $assetGroup)
    {
        if ($assetGroup->assets()->exists() || $assetGroup->children()->exists()) {
            return back()->with('error', 'Không thể xóa danh mục này vì vẫn còn dữ liệu phụ thuộc (tài sản hoặc danh mục con).');
        }

        $assetGroup->delete();

        return redirect()->route('asset_groups.index')->with('success', 'Danh mục tài sản đã được xóa.');
    }
}
