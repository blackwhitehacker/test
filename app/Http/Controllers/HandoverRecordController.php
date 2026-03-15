<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

use App\Models\HandoverRecord;

class HandoverRecordController extends Controller
{
    public function index()
    {
        $records = HandoverRecord::with(['inventoryRequest', 'creator', 'receiver'])
            ->latest()
            ->paginate(20);
            
        return view('handover_records.index', compact('records'));
    }

    public function show(HandoverRecord $handoverRecord)
    {
        $handoverRecord->load(['inventoryRequest.items.asset.group', 'creator', 'receiver']);
        return view('handover_records.show', ['record' => $handoverRecord]);
    }

    public function update(Request $request, HandoverRecord $handoverRecord)
    {
        $request->validate([
            'notes' => 'nullable|string'
        ]);

        $handoverRecord->update($request->only('notes'));

        return redirect()->back()->with('success', 'Đã lưu thay đổi nội dung biên bản.');
    }

    public function exportPdf(HandoverRecord $handoverRecord)
    {
        $handoverRecord->load(['inventoryRequest.items.asset.group', 'creator', 'receiver']);
        
        if ($handoverRecord->type === 'return') {
            $view = 'handover_records.pdf_return';
        } elseif ($handoverRecord->type === 'liquidation') {
            $view = 'handover_records.pdf_liquidation';
        } else {
            $view = 'handover_records.pdf';
        }
        
        $pdf = Pdf::loadView($view, ['record' => $handoverRecord]);
        return $pdf->download($handoverRecord->code . '.pdf');
    }

    public function sign(HandoverRecord $handoverRecord)
    {
        if ($handoverRecord->status !== 'draft') {
            return redirect()->back()->with('error', 'Biên bản này đã được ký hoặc không thể ký.');
        }

        DB::transaction(function() use ($handoverRecord) {
            $handoverRecord->update([
                'status' => 'signed',
                'signed_at' => now(),
            ]);

            // Cập nhật trạng thái tài sản dựa trên loại biên bản
            $inventoryRequest = $handoverRecord->inventoryRequest;
            if ($inventoryRequest) {
                foreach ($inventoryRequest->items as $item) {
                    if ($item->asset) {
                        if ($handoverRecord->type === 'return') {
                            // Thu hồi: -> Trong kho, gỡ User
                            $item->asset->update([
                                'status' => 'inventory',
                                'current_user_id' => null
                            ]);
                        } else {
                            // Cấp phát: -> Đang sử dụng, gán User
                            $item->asset->update([
                                'status' => 'in_use',
                                'current_user_id' => $inventoryRequest->requester_id
                            ]);
                        }
                    }
                }
                $inventoryRequest->update(['status' => 'completed']);
                $inventoryRequest->logAction('sign_handover', [], ['handover_record_id' => $handoverRecord->id]);
            }
        });

        return redirect()->back()->with('success', 'Biên bản bàn giao đã được ký xác nhận thành công.');
    }
}
