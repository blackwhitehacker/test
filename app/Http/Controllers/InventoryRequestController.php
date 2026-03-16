<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\InventoryRequest;
use App\Models\Shipment;
use Illuminate\Http\Request;

class InventoryRequestController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $type = $request->get('type', 'inbound');
        $status = $request->get('status', 'all');
        
        $query = InventoryRequest::with(['requester', 'shipment', 'receipt'])->latest();
        
        if ($type !== 'all') {
            $query->where('type', $type);
        }

        if ($status !== 'all') {
            $query->where('status', $status);
        }

        $requests = $query->paginate(20)->withQueryString();
        return view('inventory_requests.index', compact('requests', 'status', 'type'));
    }

    public function update(Request $request, InventoryRequest $inventoryRequest)
    {
        if ($inventoryRequest->status !== 'pending') {
            return redirect()->back()->with('error', 'Chỉ có thể chỉnh sửa phiếu khi đang chờ duyệt.');
        }

        $validated = $request->validate([
            'source_type' => 'required|string',
            'shipment_id' => 'nullable|exists:shipments,id',
            'notes' => 'nullable|string',
            'receiver' => 'nullable|string',
            'items' => 'nullable|array',
            'items.*.asset_id' => 'nullable|exists:assets,id',
            'items.*.name' => 'required_without:items.*.asset_id|nullable|string',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.specification' => 'nullable|string',
            'items.*.price' => 'nullable|numeric|min:0',
        ]);

        $validated['type'] = $inventoryRequest->type;

        \Illuminate\Support\Facades\DB::transaction(function() use ($validated, $inventoryRequest) {
            $inventoryRequest->update($validated);

            $inventoryRequest->items()->delete(); // Clear existing items

            // Auto-fill items from shipment if selected
            if ($inventoryRequest->shipment_id && $inventoryRequest->shipment) {
                $shipment = $inventoryRequest->shipment;
                if ($shipment->items) {
                    foreach ($shipment->items as $item) {
                        $inventoryRequest->items()->create([
                            'name' => $item['name'],
                            'quantity' => $item['quantity'] ?? ($item['delivered_qty'] ?? ($item['ordered_qty'] ?? 1)),
                            'specification' => $item['specification'] ?? ($item['unit'] ?? null),
                            'price' => $item['price'] ?? null,
                        ]);
                    }
                }
            }

            // Also allow manual items from form
            if (isset($validated['items']) && !empty($validated['items'])) {
                foreach ($validated['items'] as $item) {
                    if (!empty($item['asset_id'])) {
                        $asset = \App\Models\Asset::find($item['asset_id']);
                        if ($asset) {
                            $item['name'] = $item['name'] ?: $asset->name;
                            $item['specification'] = $item['specification'] ?: ($asset->group->name ?? '');
                            $item['price'] = $item['price'] ?: $asset->purchase_price;
                        }
                    }
                    $inventoryRequest->items()->create($item);
                }
            }
        });

        $label = $inventoryRequest->type === 'inbound' ? 'nhập kho' : 'xuất kho';
        return redirect()->route('inventory_requests.index', ['type' => $inventoryRequest->type])->with('success', "Phiếu yêu cầu {$label} đã được cập nhật.");
    }

    public function create(Request $request)
    {
        $type = $request->get('type', 'inbound');
        $shipments = Shipment::whereIn('status', ['pending', 'shipped', 'delivered', 'received'])->latest()->get();
        $assets = [];
        if ($type === 'outbound') {
            $assets = \App\Models\Asset::whereIn('status', ['inventory', 'in_use', 'repairing'])
                ->with('group:id,name')
                ->latest()
                ->get(['id', 'code', 'name', 'purchase_price', 'group_id'])
                ->map(function($asset) {
                    return [
                        'id' => $asset->id,
                        'code' => $asset->code,
                        'name' => $asset->name,
                        'purchase_price' => $asset->purchase_price,
                        'group_name' => $asset->group->name ?? ''
                    ];
                });
        }
        return view('inventory_requests.create', compact('shipments', 'type', 'assets'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'type' => 'required|in:inbound,outbound',
            'source_type' => 'required|string',
            'shipment_id' => 'nullable|exists:shipments,id',
            'notes' => 'nullable|string',
            'receiver' => 'nullable|string',
            'items' => 'nullable|array',
            'items.*.asset_id' => 'nullable|exists:assets,id',
            'items.*.name' => 'required_without:items.*.asset_id|nullable|string',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.specification' => 'nullable|string',
            'items.*.price' => 'nullable|numeric|min:0',
        ]);

        $validated['requester_id'] = auth()->id();
        $validated['status'] = 'pending';

        \Illuminate\Support\Facades\DB::transaction(function() use ($validated) {
            $inventoryRequest = InventoryRequest::create($validated);

            // Auto-fill items from shipment if selected
            if ($inventoryRequest->shipment_id && $inventoryRequest->shipment) {
                $shipment = $inventoryRequest->shipment;
                if ($shipment->items) {
                    foreach ($shipment->items as $item) {
                        $inventoryRequest->items()->create([
                            'name' => $item['name'],
                            'quantity' => $item['quantity'] ?? ($item['delivered_qty'] ?? ($item['ordered_qty'] ?? 1)),
                            'specification' => $item['specification'] ?? ($item['unit'] ?? null),
                            'price' => $item['price'] ?? null,
                        ]);
                    }
                }
            }

            // Also allow manual items from form
            if (isset($validated['items']) && !empty($validated['items'])) {
                foreach ($validated['items'] as $item) {
                    if (!empty($item['asset_id'])) {
                        $asset = \App\Models\Asset::find($item['asset_id']);
                        if ($asset) {
                            $item['name'] = $item['name'] ?: $asset->name;
                            $item['specification'] = $item['specification'] ?: ($asset->group->name ?? '');
                            $item['price'] = $item['price'] ?: $asset->purchase_price;
                        }
                    }
                    $inventoryRequest->items()->create($item);
                }
            }
        });

        $label = $validated['type'] === 'inbound' ? 'nhập kho' : 'xuất kho';
        return redirect()->route('inventory_requests.index', ['type' => $validated['type']])->with('success', "Phiếu yêu cầu {$label} đã được tạo.");
    }

    public function show(InventoryRequest $inventoryRequest)
    {
        $inventoryRequest->load(['requester', 'shipment.contract.partner', 'items', 'activityLogs.user']);
        return view('inventory_requests.show', ['inventoryRequest' => $inventoryRequest]);
    }

    public function approve(InventoryRequest $inventoryRequest)
    {
        \Illuminate\Support\Facades\DB::transaction(function() use ($inventoryRequest) {
            $inventoryRequest->update(['status' => 'approved']);
            $inventoryRequest->logAction('approve', ['status' => 'pending'], ['status' => 'approved']);

            // Create automatic Inventory Receipt (PNK)
            $receiptItems = $inventoryRequest->items->map(function($item) {
                return [
                    'name' => $item->name,
                    'quantity' => $item->quantity,
                    'specification' => $item->specification,
                    'price' => $item->price,
                    // Preparation for detailed entry
                    'details' => array_map(function($i) use ($item) {
                        return [
                            'asset_code' => ($i === 1 && $item->asset) ? $item->asset->code : '',
                            'serial' => ($i === 1 && $item->asset) ? $item->asset->serial_number : '',
                            'condition' => 'good'
                        ];
                    }, range(1, $item->quantity))
                ];
            })->toArray();

            \App\Models\InventoryReceipt::create([
                'request_id' => $inventoryRequest->id,
                'processor_id' => auth()->id(),
                'process_date' => now(),
                'type' => $inventoryRequest->type,
                'status' => 'draft',
                'items' => $receiptItems,
                'evaluation_notes' => 'Tự động tạo từ yêu cầu phê duyệt.'
            ]);

            // Automatically update linked shipment status if inbound
            if ($inventoryRequest->type === 'inbound' && $inventoryRequest->shipment_id) {
                $shipment = \App\Models\Shipment::find($inventoryRequest->shipment_id);
                if ($shipment) {
                    $shipment->update(['status' => 'inventoried']);
                    $shipment->logAction('update_status', ['status' => 'received'], ['status' => 'inventoried']);
                }
            }
        });

        $label = $inventoryRequest->type === 'inbound' ? 'nhập kho' : 'xuất kho';
        return redirect()->back()->with('success', "Yêu cầu {$label} đã được phê duyệt.");
    }

    public function edit(InventoryRequest $inventoryRequest)
    {
        if ($inventoryRequest->status !== 'pending') {
            return redirect()->back()->with('error', 'Chỉ có thể chỉnh sửa phiếu khi đang chờ duyệt.');
        }
        
        $type = $inventoryRequest->type;
        $shipments = Shipment::whereIn('status', ['pending', 'shipped', 'delivered', 'received'])->latest()->get();
        $assets = [];
        if ($type === 'outbound') {
            $assets = \App\Models\Asset::whereIn('status', ['inventory', 'in_use', 'repairing'])
                ->with('group:id,name')
                ->latest()
                ->get(['id', 'code', 'name', 'purchase_price', 'group_id'])
                ->map(function($asset) {
                    return [
                        'id' => $asset->id,
                        'code' => $asset->code,
                        'name' => $asset->name,
                        'purchase_price' => $asset->purchase_price,
                        'group_name' => $asset->group->name ?? ''
                    ];
                });
        }
        return view('inventory_requests.edit', compact('inventoryRequest', 'shipments', 'assets'));
    }

    public function cancel(InventoryRequest $inventoryRequest)
    {
        if ($inventoryRequest->status !== 'pending') {
            return redirect()->back()->with('error', 'Chỉ có thể hủy phiếu khi đang chờ duyệt.');
        }

        $oldStatus = $inventoryRequest->status;
        $inventoryRequest->update(['status' => 'cancelled']);
        $inventoryRequest->logAction('cancel', ['status' => $oldStatus], ['status' => 'cancelled']);
        
        return redirect()->back()->with('success', 'Đã hủy phiếu yêu cầu thành công.');
    }

    public function destroy(InventoryRequest $inventoryRequest)
    {
        if (!in_array($inventoryRequest->status, ['pending', 'cancelled', 'rejected'])) {
            return redirect()->back()->with('error', 'Không thể xóa phiếu đã được phê duyệt hoặc đang xử lý.');
        }

        $type = $inventoryRequest->type;
        $inventoryRequest->delete();
        return redirect()->route('inventory_requests.index', ['type' => $type])->with('success', 'Đã xóa phiếu yêu cầu thành công.');
    }

    public function generateReceipt(InventoryRequest $inventoryRequest)
    {
        if ($inventoryRequest->status !== 'approved') {
            return redirect()->back()->with('error', 'Chỉ có thể tạo phiếu xử lý cho yêu cầu đã được phê duyệt.');
        }

        $existing = \App\Models\InventoryReceipt::where('request_id', $inventoryRequest->id)->first();
        if ($existing) {
            return redirect()->route('inventory_receipts.show', $existing)->with('info', 'Phiếu xử lý đã tồn tại.');
        }

        \Illuminate\Support\Facades\DB::transaction(function() use ($inventoryRequest) {
            $receiptItems = $inventoryRequest->items->map(function($item) {
                return [
                    'name' => $item->name,
                    'quantity' => $item->quantity,
                    'specification' => $item->specification,
                    'price' => $item->price,
                    'details' => array_map(function($i) use ($item) {
                        return [
                            'asset_code' => ($i === 1 && $item->asset_id) ? $item->asset->code : '',
                            'serial' => ($i === 1 && $item->asset_id) ? ($item->asset->serial_number ?? '') : '',
                            'condition' => 'new'
                        ];
                    }, range(1, $item->quantity))
                ];
            })->toArray();

            \App\Models\InventoryReceipt::create([
                'request_id' => $inventoryRequest->id,
                'processor_id' => auth()->id(),
                'process_date' => now(),
                'type' => $inventoryRequest->type,
                'status' => 'draft',
                'items' => $receiptItems,
                'evaluation_notes' => 'Tạo thủ công cho yêu cầu đã phê duyệt.'
            ]);
        });

        $label = $inventoryRequest->type === 'inbound' ? 'nhập kho' : 'xuất kho';
        return redirect()->route('inventory_receipts.index', ['type' => $inventoryRequest->type])->with('success', "Đã tạo phiếu {$label} mới.");
    }
}
