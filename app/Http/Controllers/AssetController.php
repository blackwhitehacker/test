<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\AssetGroup;
use App\Models\Partner;
use App\Models\User;
use Illuminate\Http\Request;

class AssetController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $status = $request->get('status', 'all');
        $search = $request->get('search');
        $groupIdSource = $request->get('group_id');

        $query = Asset::with(['group.parent.parent', 'partner', 'user']);

        if ($status !== 'all') {
            $query->where(function($q) use ($status) {
                $q->where('status', $status);
            });
        }

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%")
                  ->orWhere('serial_number', 'like', "%{$search}%");
            });
        }

        if ($groupIdSource) {
            $query->where(function($q) use ($groupIdSource) {
                $q->where('group_id', $groupIdSource);
            });
        }

        $assets = $query->paginate(20)->withQueryString();
        
        // For filtering
        $groups = AssetGroup::whereNull('parent_id')->with('children.children')->get();

        return view('assets.index', compact('assets', 'status', 'groups'));
    }

    public function create()
    {
        // Get Level 1 categories (Root groups)
        $categories = AssetGroup::whereNull('parent_id')->get();
        
        // Get existing names for suggestions (Level 2 & 3)
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
            
        $partners = Partner::all();
        return view('assets.create', compact('categories', 'partners', 'existingGroups', 'existingLines'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:asset_groups,id',
            'group_name' => 'required|string|max:255',
            'line_name' => 'required|string|max:255',
            'partner_name' => 'nullable|string|max:255',
            'serial_number' => 'nullable|string|max:100',
            'quantity' => 'nullable|integer|min:1',
            'model' => 'nullable|string|max:255',
            'specs' => 'nullable|string',
            'purchase_price' => 'required|numeric|min:0',
            'recovery_value' => 'required|numeric|min:0',
            'usage_months' => 'required|integer|min:0',
            'purchase_date' => 'nullable|date',
            'warranty_expiry' => 'nullable|date',
            'status' => 'required|string|in:inventory,in_use,repairing,liquidating',
            'location' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
            'attachments' => 'nullable|array',
        ]);

        // Find or Create Partner
        $partnerId = null;
        if ($request->partner_name) {
            $partner = Partner::firstOrCreate(['name' => $request->partner_name]);
            $partnerId = $partner->id;
        }

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

        $data = $validated;
        $data['group_id'] = $line->id;
        $data['partner_id'] = $partnerId;

        Asset::create($data);

        return redirect()->route('assets.index')->with('success', 'Tài sản đã được tạo thành công.');
    }

    public function show(Asset $asset)
    {
        $asset->load(['group.parent.parent', 'partner', 'user', 'activityLogs.user', 'movementHistory']);
        return view('assets.show', compact('asset'));
    }

    public function edit(Asset $asset)
    {
        $asset->load(['group.parent.parent']);
        
        $categories = AssetGroup::whereNull('parent_id')->get();
        
        // Suggestions
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
            
        $partners = Partner::all();
        
        return view('assets.edit', compact('asset', 'categories', 'partners', 'existingGroups', 'existingLines'));
    }

    public function update(Request $request, Asset $asset)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:asset_groups,id',
            'group_name' => 'required|string|max:255',
            'line_name' => 'required|string|max:255',
            'partner_name' => 'nullable|string|max:255',
            'serial_number' => 'nullable|string|max:100',
            'quantity' => 'nullable|integer|min:1',
            'model' => 'nullable|string|max:255',
            'specs' => 'nullable|string',
            'purchase_price' => 'required|numeric|min:0',
            'recovery_value' => 'required|numeric|min:0',
            'usage_months' => 'required|integer|min:0',
            'purchase_date' => 'nullable|date',
            'warranty_expiry' => 'nullable|date',
            'status' => 'required|string|in:inventory,in_use,repairing,liquidating',
            'location' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
            'attachments' => 'nullable|array',
        ]);

        // Find or Create Partner
        $partnerId = null;
        if ($request->partner_name) {
            $partner = Partner::firstOrCreate(['name' => $request->partner_name]);
            $partnerId = $partner->id;
        }

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

        $data = $validated;
        $data['group_id'] = $line->id;
        $data['partner_id'] = $partnerId;

        $asset->update($data);

        return redirect()->route('assets.index')->with('success', 'Thông tin tài sản đã được cập nhật.');
    }

    public function destroy(Asset $asset)
    {
        $asset->delete();
        return redirect()->route('assets.index')->with('success', 'Tài sản đã được xóa.');
    }
}
