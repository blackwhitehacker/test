<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\PurchaseRequisition;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class PurchaseRequisitionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = PurchaseRequisition::with('requester')->latest();
        
        if ($request->has('search')) {
            $search = $request->get('search');
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%");
            });
        }

        $requisitions = $query->paginate(20)->withQueryString();
        
        $pendingCount = PurchaseRequisition::where('status', 'pending')->count();
        $approvedCount = PurchaseRequisition::where('status', 'approved')->count();

        return view('purchase_requisitions.index', compact('requisitions', 'pendingCount', 'approvedCount'));
    }

    public function create()
    {
        return view('purchase_requisitions.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'department' => 'nullable|string|max:255',
            'estimated_cost' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'needed_date' => 'nullable|date',
            'partner_name' => 'nullable|string|max:255',
            'items' => 'required|array|min:1',
            'items.*.name' => 'required|string',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.estimate' => 'required|numeric|min:0',
            'items.*.unit' => 'nullable|string',
            'attachments.*' => 'nullable|file|max:10240', // 10MB limit
        ]);

        $validated['requester_id'] = auth()->id();
        $validated['status'] = 'pending';
        
        $attachmentPaths = [];
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $path = $file->store('requisitions', 'public');
                $attachmentPaths[] = [
                    'name' => $file->getClientOriginalName(),
                    'path' => $path
                ];
            }
        }
        $validated['attachments'] = $attachmentPaths;
        
        if ($request->partner_name) {
            $partner = \App\Models\Partner::firstOrCreate(['name' => $request->partner_name]);
            $validated['partner_id'] = $partner->id;
        }
        
        PurchaseRequisition::create($validated);

        return redirect()->route('purchase_requisitions.index')->with('success', 'Tờ trình mua sắm đã được gửi phê duyệt.');
    }

    /**
     * Display the specified resource.
     */
    public function show(PurchaseRequisition $purchaseRequisition)
    {
        $purchaseRequisition->load(['requester', 'activityLogs.user']);
        return view('purchase_requisitions.show', ['requisition' => $purchaseRequisition]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PurchaseRequisition $purchaseRequisition)
    {
        if ($purchaseRequisition->status !== 'pending') {
            return redirect()->back()->with('error', 'Chỉ có thể chỉnh sửa tờ trình đang chờ duyệt.');
        }
        return view('purchase_requisitions.edit', ['requisition' => $purchaseRequisition]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PurchaseRequisition $purchaseRequisition)
    {
        if ($purchaseRequisition->status !== 'pending') {
            return redirect()->back()->with('error', 'Chỉ có thể chỉnh sửa tờ trình đang chờ duyệt.');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'department' => 'nullable|string|max:255',
            'estimated_cost' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'needed_date' => 'nullable|date',
            'partner_name' => 'nullable|string|max:255',
            'items' => 'required|array|min:1',
            'items.*.name' => 'required|string',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.estimate' => 'required|numeric|min:0',
            'items.*.unit' => 'nullable|string',
            'attachments.*' => 'nullable|file|max:10240',
        ]);

        if ($request->hasFile('attachments')) {
            $attachmentPaths = $purchaseRequisition->attachments ?? [];
            foreach ($request->file('attachments') as $file) {
                $path = $file->store('requisitions', 'public');
                $attachmentPaths[] = [
                    'name' => $file->getClientOriginalName(),
                    'path' => $path
                ];
            }
            $validated['attachments'] = $attachmentPaths;
        }

        if ($request->partner_name) {
            $partner = \App\Models\Partner::firstOrCreate(['name' => $request->partner_name]);
            $validated['partner_id'] = $partner->id;
        }

        $purchaseRequisition->update($validated);

        return redirect()->route('purchase_requisitions.show', $purchaseRequisition)->with('success', 'Đã cập nhật tờ trình.');
    }

    public function approve(PurchaseRequisition $purchaseRequisition)
    {
        $purchaseRequisition->update(['status' => 'approved']);
        $purchaseRequisition->logAction('approve', ['status' => 'pending'], ['status' => 'approved']);
        return redirect()->back()->with('success', 'Tờ trình đã được phê duyệt.');
    }

    public function reject(Request $request, PurchaseRequisition $purchaseRequisition)
    {
        $oldStatus = $purchaseRequisition->status;
        $purchaseRequisition->update([
            'status' => 'rejected',
            'rejection_reason' => $request->reason
        ]);
        $purchaseRequisition->logAction('reject', ['status' => $oldStatus], [
            'status' => 'rejected',
            'reason' => $request->reason
        ]);
        return redirect()->back()->with('success', 'Đã từ chối tờ trình.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PurchaseRequisition $purchaseRequisition)
    {
        $purchaseRequisition->delete();
        return redirect()->route('purchase_requisitions.index')->with('success', 'Đã xóa tờ trình.');
    }

    public function export(PurchaseRequisition $purchaseRequisition)
    {
        $purchaseRequisition->load('requester');
        $pdf = Pdf::loadView('purchase_requisitions.pdf', ['requisition' => $purchaseRequisition]);
        return $pdf->download('To_trinh_' . $purchaseRequisition->code . '.pdf');
    }
}
