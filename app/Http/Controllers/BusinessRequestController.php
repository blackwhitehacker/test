<?php

namespace App\Http\Controllers;

use App\Models\InventoryRequest;
use App\Models\Asset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class BusinessRequestController extends Controller
{
    /**
     * Danh sách các yêu cầu nghiệp vụ
     */
    public function index(Request $request)
    {
        $type = $request->get('type'); // allocation, repair, recall
        $status = $request->get('status', 'all');

        $query = InventoryRequest::with(['requester', 'items.asset'])
            ->whereIn('source_type', ['allocation', 'repair', 'recall', 'liquidation']);

        if ($type) {
            $query->where('source_type', '=', $type);
        }

        if ($status !== 'all') {
            $query->where('status', '=', $status);
        }

        $requests = $query->latest()->paginate(20)->withQueryString();

        return view('business_requests.index', compact('requests', 'type', 'status'));
    }

    /**
     * Form tạo yêu cầu mới
     */
    public function create(Request $request)
    {
        $type = $request->get('type', 'allocation'); // default to allocation
        
        // Lấy danh sách tài sản dựa trên loại yêu cầu
        $assets = [];
        if ($type === 'allocation' || $type === 'repair') {
            // Cấp phát hoặc sửa chữa thường lấy từ kho
            $assets = Asset::where('status', '=', 'inventory')->get(['id', 'code', 'name']);
        } elseif ($type === 'recall') {
            // Thu hồi thì lấy tài sản đang sử dụng
            $assets = Asset::where('status', '=', 'in_use')->with('user')->get();
        } elseif ($type === 'liquidation') {
            // Thanh lý thì lấy tài sản đang hỏng hoặc tồn kho
            $assets = Asset::whereIn('status', ['liquidating', 'inventory'])->get(['id', 'code', 'name']);
        }

        return view('business_requests.create', compact('type', 'assets'));
    }

    /**
     * Lưu yêu cầu mới
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'source_type' => 'required|in:allocation,repair,recall,liquidation',
            'target_type' => 'required|in:individual,department,center',
            'target_name' => 'required|string|max:255',
            'notes' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.asset_id' => 'required|exists:assets,id',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        $sourceType = $validated['source_type'];
        
        // Xác định type kho tương ứng (Cấp phát/Sửa chữa = Outbound, Thu hồi = Inbound)
        $type = ($sourceType === 'recall') ? 'inbound' : 'outbound';

        DB::transaction(function() use ($validated, $type, $sourceType) {
            $inventoryRequest = InventoryRequest::create([
                'type' => $type,
                'source_type' => $sourceType,
                'target_type' => $validated['target_type'],
                'target_name' => $validated['target_name'],
                'requester_id' => auth()->id(),
                'status' => 'pending',
                'notes' => $validated['notes'],
            ]);

            foreach ($validated['items'] as $item) {
                $asset = Asset::find($item['asset_id']);
                $inventoryRequest->items()->create([
                    'asset_id' => $item['asset_id'],
                    'name' => $asset->name,
                    'quantity' => $item['quantity'],
                    'specification' => $asset->group->name ?? '',
                    'price' => $asset->purchase_price,
                ]);
            }

            $inventoryRequest->logAction('create', [], $inventoryRequest->toArray());
        });

        return redirect()->route('business_requests.index', ['type' => $sourceType])
            ->with('success', 'Yêu cầu của bạn đã được gửi thành công.');
    }

    /**
     * Xem chi tiết
     */
    public function show(InventoryRequest $businessRequest)
    {
        $businessRequest->load(['requester', 'items.asset', 'activityLogs.user']);
        return view('business_requests.show', ['request' => $businessRequest]);
    }

    /**
     * Form chỉnh sửa
     */
    public function edit(InventoryRequest $businessRequest)
    {
        if ($businessRequest->status !== 'pending') {
            return redirect()->back()->with('error', 'Chỉ có thể chỉnh sửa yêu cầu khi đang chờ duyệt.');
        }

        $businessRequest->load('items.asset');
        $type = $businessRequest->source_type;
        
        $assets = [];
        if ($type === 'allocation' || $type === 'repair') {
            $assets = Asset::where('status', '=', 'inventory')->get(['id', 'code', 'name']);
        } elseif ($type === 'recall') {
            $assets = Asset::where('status', '=', 'in_use')->with('user')->get();
        }

        return view('business_requests.edit', compact('businessRequest', 'type', 'assets'));
    }

    /**
     * Cập nhật yêu cầu
     */
    public function update(Request $request, InventoryRequest $businessRequest)
    {
        if ($businessRequest->status !== 'pending') {
            return redirect()->back()->with('error', 'Chỉ có thể chỉnh sửa yêu cầu khi đang chờ duyệt.');
        }

        $validated = $request->validate([
            'target_type' => 'required|in:individual,department,center',
            'target_name' => 'required|string|max:255',
            'notes' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.asset_id' => 'required|exists:assets,id',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        DB::transaction(function() use ($validated, $businessRequest) {
            $oldData = $businessRequest->toArray();
            
            $businessRequest->update([
                'target_type' => $validated['target_type'],
                'target_name' => $validated['target_name'],
                'notes' => $validated['notes'],
            ]);

            // Re-create items
            $businessRequest->items()->delete();
            foreach ($validated['items'] as $item) {
                $asset = Asset::find($item['asset_id']);
                $businessRequest->items()->create([
                    'asset_id' => $item['asset_id'],
                    'name' => $asset->name,
                    'quantity' => $item['quantity'],
                    'specification' => $asset->group->name ?? '',
                    'price' => $asset->purchase_price,
                ]);
            }

            $businessRequest->logAction('update', $oldData, $businessRequest->fresh()->toArray());
        });

        return redirect()->route('business_requests.show', $businessRequest)
            ->with('success', 'Yêu cầu đã được cập nhật.');
    }

    /**
     * Hủy yêu cầu
     */
    public function cancel(InventoryRequest $businessRequest)
    {
        if ($businessRequest->status !== 'pending') {
            return redirect()->back()->with('error', 'Chỉ có thể hủy yêu cầu khi đang chờ duyệt.');
        }

        $businessRequest->update(['status' => 'cancelled']);
        $businessRequest->logAction('cancel', ['status' => 'pending'], ['status' => 'cancelled']);

        return redirect()->back()->with('success', 'Yêu cầu đã được hủy.');
    }

    /**
     * Gửi đánh giá tình trạng tài sản (Thu hồi)
     */
    public function submitAssessment(Request $request, InventoryRequest $businessRequest)
    {
        $validated = $request->validate([
            'assessment_status' => 'required|in:safe,damaged,broken',
            'assessment_notes' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            $businessRequest->update([
                'assessment_status' => $validated['assessment_status'],
                'assessment_notes' => $validated['assessment_notes'],
            ]);

            $businessRequest->logAction('assessment', [], $validated);

            DB::commit();
            return back()->with('success', 'Đã lưu đánh giá kỹ thuật.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    public function submitRepairUpdate(Request $request, InventoryRequest $business_request)
    {
        $request->validate([
            'repair_status' => 'required|in:repairing,completed,unfixable',
            'repair_cost' => 'nullable|numeric|min:0',
            'repair_notes' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            $business_request->update([
                'repair_status' => $request->repair_status,
                'repair_cost' => $request->repair_cost,
                'repair_notes' => $request->repair_notes,
            ]);

            // If unfixable, we might want to change request status or asset status
            if ($request->repair_status === 'unfixable') {
                // Auto change inventory request status to unfixable/approved for liquidation?
                // For now, just log and update asset if needed
                foreach ($business_request->items as $item) {
                    if ($item->asset) {
                        $item->asset->update(['status' => 'liquidating']); // Mark as awaiting liquidation
                    }
                }
            } elseif ($request->repair_status === 'completed') {
                foreach ($business_request->items as $item) {
                    if ($item->asset) {
                        $item->asset->update(['status' => 'inventory']); // Back to inventory
                    }
                }
            }

            DB::commit();
            return back()->with('success', 'Đã cập nhật tình trạng sửa chữa.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    public function submitLiquidationUpdate(Request $request, InventoryRequest $business_request)
    {
        $request->validate([
            'recovery_value' => 'required|numeric|min:0',
            'liquidation_notes' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            $business_request->update([
                'recovery_value' => $request->recovery_value,
                'liquidation_notes' => $request->liquidation_notes,
                'status' => 'completed', // Finalize when results are in
            ]);

            // Update asset status to liquidated
            foreach ($business_request->items as $item) {
                if ($item->asset) {
                    $item->asset->update(['status' => 'liquidated']);
                }
            }

            DB::commit();
            return back()->with('success', 'Đã cập nhật kết quả thanh lý và đóng yêu cầu.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    public function exportLiquidation(InventoryRequest $business_request)
    {
        $business_request->load(['items.asset', 'requester']);
        $pdf = Pdf::loadView('handover_records.pdf_liquidation', ['record' => $business_request]);
        return $pdf->download('Bien_ban_thanh_ly_' . $business_request->code . '.pdf');
    }

    /**
     * Phê duyệt yêu cầu
     */
    public function approve(InventoryRequest $businessRequest)
    {
        if ($businessRequest->status !== 'pending' && $businessRequest->status !== 'pending_director') {
            return redirect()->back()->with('error', 'Yêu cầu này không ở trạng thái chờ duyệt.');
        }

        DB::transaction(function() use ($businessRequest) {
            $oldStatus = $businessRequest->status;
            $businessRequest->update(['status' => 'approved']);
            
            // Nếu là cấp phát, tạo biên bản bàn giao
            if ($businessRequest->source_type === 'allocation') {
                $handoverCode = 'BBBG-' . date('Ymd') . '-' . str_pad($businessRequest->id, 4, '0', STR_PAD_LEFT);
                \App\Models\HandoverRecord::create([
                    'code' => $handoverCode,
                    'type' => 'handover',
                    'inventory_request_id' => $businessRequest->id,
                    'creator_id' => auth()->id(),
                    'receiver_name' => $businessRequest->target_name,
                    'handover_date' => now(),
                    'status' => 'draft',
                ]);

                // Cập nhật tài sản: Tồn kho -> Đang sử dụng
                foreach ($businessRequest->items as $item) {
                    if ($item->asset) {
                        $item->asset->update([
                            'status' => 'in_use',
                            'current_user_id' => $businessRequest->requester_id
                        ]);
                    }
                }
            }
            
            // Nếu là thu hồi, tạo biên bản hoàn trả
            if ($businessRequest->source_type === 'recall') {
                $returnCode = 'BBHT-' . date('Ymd') . '-' . str_pad($businessRequest->id, 4, '0', STR_PAD_LEFT);
                \App\Models\HandoverRecord::create([
                    'code' => $returnCode,
                    'type' => 'return',
                    'inventory_request_id' => $businessRequest->id,
                    'creator_id' => auth()->id(),
                    'receiver_name' => $businessRequest->requester->name, // Người hoàn trả
                    'handover_date' => now(),
                ]);
            }
            
            // Nếu là sửa chữa, cập nhật trạng thái tài sản thành đang sửa chữa
            if ($businessRequest->source_type === 'repair') {
                foreach ($businessRequest->items as $item) {
                    if ($item->asset) {
                        $item->asset->update(['status' => 'repairing']);
                    }
                }
            }

            // Nếu là thanh lý, cập nhật trạng thái tài sản thành chờ thanh lý
            if ($businessRequest->source_type === 'liquidation') {
                foreach ($businessRequest->items as $item) {
                    if ($item->asset) {
                        $item->asset->update(['status' => 'liquidating']);
                    }
                }
            }

            $businessRequest->logAction('approve', ['status' => $oldStatus], ['status' => 'approved']);
        });

        return redirect()->back()->with('success', 'Yêu cầu đã được phê duyệt thành công.');
    }

    /**
     * Gửi Giám đốc phê duyệt (trường hợp ngoài định mức)
     */
    public function escalate(InventoryRequest $businessRequest)
    {
        if ($businessRequest->status !== 'pending') {
            return redirect()->back()->with('error', 'Chỉ có thể gửi Giám đốc khi yêu cầu đang chờ duyệt.');
        }

        $businessRequest->update(['status' => 'pending_director']);
        $businessRequest->logAction('escalate', ['status' => 'pending'], ['status' => 'pending_director']);

        return redirect()->back()->with('success', 'Đã chuyển yêu cầu cho Giám đốc xử lý.');
    }

    /**
     * Kiểm tra định mức cấp phát
     */
    public function checkCompliance(InventoryRequest $businessRequest)
    {
        $results = [];
        $isExceeded = false;

        foreach ($businessRequest->items as $item) {
            if (!$item->asset || !$item->asset->group_id) continue;

            $standard = \App\Models\AllocationStandard::where('asset_group_id', $item->asset->group_id)
                ->where('target_type', $businessRequest->target_type)
                ->where('target_name', $businessRequest->target_name)
                ->first();

            if ($standard) {
                // Đếm số tài sản cùng nhóm đang sử dụng bởi đối tượng này
                // Lưu ý: Hiện tại DB chưa có cơ chế link target_name chính xác với bản ghi cụ thể ngoại trừ individual = user_id
                // Tạm thời đếm theo requester_id cho individual
                $currentCount = 0;
                if ($businessRequest->target_type === 'individual') {
                    $currentCount = Asset::where('current_user_id', $businessRequest->requester_id)
                        ->where('group_id', $item->asset->group_id)
                        ->count();
                }

                $totalAfter = $currentCount + $item->quantity;
                $exceeds = $totalAfter > $standard->limit_quantity;
                if ($exceeds) $isExceeded = true;

                $results[] = [
                    'group' => $item->asset->group->name ?? 'Unknown',
                    'standard' => $standard->limit_quantity,
                    'current' => $currentCount,
                    'requesting' => $item->quantity,
                    'exceeds' => $exceeds
                ];
            } else {
                $results[] = [
                    'group' => $item->asset->group->name ?? 'Unknown',
                    'standard' => 'N/A',
                    'current' => '-',
                    'requesting' => $item->quantity,
                    'exceeds' => false
                ];
            }
        }

        return response()->json([
            'compliance' => $results,
            'is_exceeded' => $isExceeded
        ]);
    }

    /**
     * Từ chối yêu cầu
     */
    public function reject(Request $request, InventoryRequest $businessRequest)
    {
        if ($businessRequest->status !== 'pending') {
            return redirect()->back()->with('error', 'Yêu cầu này không ở trạng thái chờ duyệt.');
        }

        $businessRequest->update(['status' => 'rejected']);
        $businessRequest->logAction('reject', ['status' => 'pending'], ['status' => 'rejected']);

        return redirect()->back()->with('success', 'Yêu cầu đã bị từ chối.');
    }

    /**
     * Danh sách tài sản của tôi
     */
    public function myAssets()
    {
        $assets = Asset::where('current_user_id', '=', auth()->id())
            ->with('group')
            ->latest()
            ->paginate(20);

        return view('business_requests.my_assets', compact('assets'));
    }
}
