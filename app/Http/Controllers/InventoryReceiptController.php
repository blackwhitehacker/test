<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\InventoryReceipt;
use App\Models\InventoryRequest;
use Illuminate\Http\Request;

class InventoryReceiptController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $type = $request->get('type', 'inbound');
        $receipts = InventoryReceipt::where('type', $type)
            ->with(['processor', 'request'])
            ->latest()
            ->paginate(20)
            ->withQueryString();
        return view('inventory_receipts.index', compact('receipts', 'type'));
    }

    /**
     * Display the specified resource.
     */
    public function show(InventoryReceipt $inventory_receipt)
    {
        $inventory_receipt->load(['processor', 'request.requester', 'request.items', 'activityLogs.user']);
        $assetGroups = \App\Models\AssetGroup::all();
        return view('inventory_receipts.show', [
            'receipt' => $inventory_receipt,
            'assetGroups' => $assetGroups
        ]);
    }

    public function saveItems(Request $request, InventoryReceipt $inventory_receipt)
    {
        if ($inventory_receipt->status !== 'draft') {
            return redirect()->back()->with('error', 'Phiếu đã xác nhận không thể chỉnh sửa.');
        }

        $items = $inventory_receipt->items;
        $inputItems = $request->input('items', []);

        // Sync input data to items array
        foreach ($items as $index => &$item) {
            if (isset($inputItems[$index]['details'])) {
                foreach ($item['details'] as $detailIndex => &$detail) {
                    if (isset($inputItems[$index]['details'][$detailIndex])) {
                        $detail['asset_code'] = $inputItems[$index]['details'][$detailIndex]['asset_code'] ?? '';
                        $detail['serial'] = $inputItems[$index]['details'][$detailIndex]['serial'] ?? '';
                        $detail['condition'] = $inputItems[$index]['details'][$detailIndex]['condition'] ?? 'new';
                        $detail['group_id'] = $inputItems[$index]['details'][$detailIndex]['group_id'] ?? null;
                        $detail['actual_quantity'] = (int)($inputItems[$index]['details'][$detailIndex]['actual_quantity'] ?? 1);
                    }
                }
            }
        }

        $inventory_receipt->update([
            'items' => $items,
            'evaluation_notes' => $request->input('evaluation_notes')
        ]);

        $inventory_receipt->logAction('save_draft', [], ['notes' => 'Cập nhật bản nháp chi tiết hàng hóa']);

        return redirect()->back()->with('success', 'Đã lưu thông tin hàng hóa.');
    }

    public function confirm(Request $request, InventoryReceipt $inventory_receipt)
    {
        if ($inventory_receipt->status !== 'draft') {
            return redirect()->back()->with('error', 'Phiếu này đã được xác nhận trước đó.');
        }

        try {
            \Illuminate\Support\Facades\DB::transaction(function() use ($inventory_receipt, $request) {
                $items = $inventory_receipt->items;
                $inputItems = $request->input('items', []);

                // Sync and Validate
                foreach ($items as $index => &$item) {
                    if (isset($inputItems[$index]['details'])) {
                        foreach ($item['details'] as $detailIndex => &$detail) {
                            if (isset($inputItems[$index]['details'][$detailIndex])) {
                                $detail['asset_code'] = $inputItems[$index]['details'][$detailIndex]['asset_code'] ?? '';
                                $detail['serial'] = $inputItems[$index]['details'][$detailIndex]['serial'] ?? '';
                                $detail['condition'] = $inputItems[$index]['details'][$detailIndex]['condition'] ?? 'new';
                                $detail['group_id'] = $inputItems[$index]['details'][$detailIndex]['group_id'] ?? null;
                                $detail['actual_quantity'] = (int)($inputItems[$index]['details'][$detailIndex]['actual_quantity'] ?? 1);

                                // Logic validation: Chỉ bắt buộc mã tài sản cho dòng đầu tiên (STT 1)
                                if ($detailIndex === 0 && empty($detail['asset_code'])) {
                                    throw new \Exception("Mặt hàng '{$item['name']}' thiếu Mã tài sản ở dòng đầu tiên.");
                                }
                            }
                        }
                    }
                }

                // Save final state of items
                $inventory_receipt->update([
                    'items' => $items,
                    'status' => 'confirmed',
                    'process_date' => now(),
                    'evaluation_notes' => $request->input('evaluation_notes')
                ]);

                // Create or Update Assets
                foreach ($items as $item) {
                    $detailsByGroupAndCode = [];
                    foreach ($item['details'] as $detail) {
                        $code = $detail['asset_code'] ?? '';
                        if (empty($code)) continue;

                        $gid = $detail['group_id'] ?? 2; 
                        $key = $gid . '|' . $code;
                        
                        if (!isset($detailsByGroupAndCode[$key])) {
                            $detailsByGroupAndCode[$key] = [
                                'group_id' => $gid,
                                'asset_code' => $code,
                                'serial' => $detail['serial'] ?? '',
                                'condition' => $detail['condition'] ?? 'new',
                                'count' => 0
                            ];
                        }
                        $detailsByGroupAndCode[$key]['count'] += ($detail['actual_quantity'] ?? 1);
                    }

                    foreach ($detailsByGroupAndCode as $groupData) {
                        $groupObject = \App\Models\AssetGroup::find($groupData['group_id']);
                        $isCCDC = ($groupObject && $groupObject->tracking_type === 'quantity');

                        if ($inventory_receipt->type === 'inbound') {
                            $existingAsset = \App\Models\Asset::where('code', $groupData['asset_code'])->first();
                            
                            $assetData = [
                                'code' => $groupData['asset_code'],
                                'name' => $item['name'],
                                'group_id' => $groupData['group_id'],
                                'serial_number' => $groupData['serial'],
                                'status' => 'inventory',
                                'purchase_price' => $item['price'] ?? 0,
                                'purchase_date' => $inventory_receipt->process_date,
                                'specs' => $item['specification'] ?? '',
                                'quantity' => $isCCDC ? $groupData['count'] : 1,
                            ];

                            // Logic Sync for RECALL (Thu hồi) - Clear user info when back to warehouse
                            if ($inventory_receipt->request && $inventory_receipt->request->source_type === 'recall') {
                                $assetData['current_user_id'] = null;
                                $assetData['assigned_department'] = null;
                                $assetData['assigned_center'] = null;
                                $assetData['status'] = 'inventory';
                            }

                            if (!$existingAsset) {
                                \App\Models\Asset::create($assetData);
                            } else {
                                if ($isCCDC) $assetData['quantity'] = $existingAsset->quantity + $groupData['count'];
                                $existingAsset->update($assetData);
                            }
                        } else {
                            // Outbound Sync: ALLOCATION or REPAIR
                            $asset = \App\Models\Asset::where('code', $groupData['asset_code'])->first();
                            if ($asset) {
                                $newStatus = 'in_use';
                                $updateData = [];

                                if ($inventory_receipt->request) {
                                    $sourceType = $inventory_receipt->request->source_type;
                                    $targetType = $inventory_receipt->request->target_type;
                                    $targetName = $inventory_receipt->request->target_name;

                                    if ($sourceType === 'allocation') {
                                        $newStatus = 'in_use';
                                        if ($targetType === 'individual') {
                                            $updateData['current_user_id'] = $inventory_receipt->request->requester_id;
                                            $updateData['assigned_department'] = null;
                                            $updateData['assigned_center'] = null;
                                        } elseif ($targetType === 'department') {
                                            $updateData['current_user_id'] = null;
                                            $updateData['assigned_department'] = $targetName;
                                            $updateData['assigned_center'] = null;
                                        } elseif ($targetType === 'center') {
                                            $updateData['current_user_id'] = null;
                                            $updateData['assigned_department'] = null;
                                            $updateData['assigned_center'] = $targetName;
                                        }
                                    } elseif ($sourceType === 'repair') {
                                        $newStatus = 'repairing';
                                    } elseif ($sourceType === 'disposal') {
                                        $newStatus = 'liquidated';
                                    }
                                }
                                
                                $updateData['status'] = $newStatus;
                                if ($isCCDC) {
                                    $updateData['quantity'] = max(0, $asset->quantity - $groupData['count']);
                                    if ($updateData['quantity'] > 0) $updateData['status'] = $asset->status;
                                }

                                $asset->update($updateData);
                            }
                        }
                    }
                }

                $inventory_receipt->logAction('confirm', ['status' => 'draft'], ['status' => 'confirmed']);
            });
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Lỗi: ' . $e->getMessage());
        }

        $msg = $inventory_receipt->type === 'inbound' 
            ? 'Nhập kho thành công. Các tài sản đã được tạo mới với trạng thái Chưa sử dụng.'
            : 'Xuất kho thành công. Trạng thái các tài sản đã được cập nhật.';

        return redirect()->route('inventory_receipts.index', ['type' => $inventory_receipt->type])->with('success', $msg);
    }

    public function exportPdf(InventoryReceipt $inventory_receipt)
    {
        $inventory_receipt->load(['processor', 'request.requester', 'request.items']);
        
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('inventory_receipts.pdf', [
            'receipt' => $inventory_receipt
        ]);

        return $pdf->stream("phieu-nhap-kho-{$inventory_receipt->code}.pdf");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(InventoryReceipt $inventory_receipt)
    {
        if ($inventory_receipt->status === 'confirmed') {
            return redirect()->back()->with('error', 'Không thể xóa phiếu đã xác nhận nhập kho.');
        }
        $type = $inventory_receipt->type;
        $inventory_receipt->delete();
        $msg = $type === 'inbound' ? 'Đã xóa phiếu nhập kho.' : 'Đã xóa phiếu xuất kho.';
        return redirect()->route('inventory_receipts.index', ['type' => $type])->with('success', $msg);
    }
}
