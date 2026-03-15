<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Contract;
use App\Models\Partner;
use App\Models\Setting;
use App\Models\PurchaseRequisition;
use App\Models\ActivityLog;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class ContractController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        \Illuminate\Support\Facades\Artisan::call('contracts:auto-liquidate');

        $query = Contract::with(['partner', 'requisition']);

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('contract_number', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%")
                  ->orWhereHas('partner', function($pq) use ($search) {
                      $pq->where('name', 'like', "%{$search}%");
                  });
            });
        }

        $contracts = $query->latest()->paginate(20)->withQueryString();
        return view('contracts.index', compact('contracts'));
    }

    public function create()
    {
        $partners = Partner::all();
        $requisitions = PurchaseRequisition::where('status', 'approved')->get();
        return view('contracts.create', compact('partners', 'requisitions'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'contract_number' => 'required|string|max:100',
            'partner_name' => 'required|string|max:255',
            'requisition_id' => 'nullable|exists:purchase_requisitions,id',
            'value' => 'required|numeric|min:0',
            'expiration_date' => 'nullable|date',
            'warranty_months' => 'nullable|integer|min:0',
            'items' => 'nullable|array',
            'files.*' => 'nullable|file|max:10240',
        ]);

        $partner = Partner::firstOrCreate(['name' => $request->partner_name]);
        $validated['partner_id'] = $partner->id;

        $files = [];
        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $file) {
                $path = $file->store('contracts', 'public');
                $files[] = [
                    'name' => $file->getClientOriginalName(),
                    'path' => $path,
                    'size' => $file->getSize()
                ];
            }
        }

        $validated['files'] = $files;
        $validated['status'] = 'active';

        $contract = Contract::create($validated);

        return redirect()->route('contracts.index')->with('success', 'Hợp đồng mua sắm đã được tạo thành công.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Contract $contract)
    {
        $contract->load(['partner', 'requisition', 'activityLogs.user']);
        return view('contracts.show', compact('contract'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Contract $contract)
    {
        $partners = Partner::all();
        $requisitions = PurchaseRequisition::where('status', 'approved')->get();
        return view('contracts.edit', compact('contract', 'partners', 'requisitions'));
    }

    public function update(Request $request, Contract $contract)
    {
        $validated = $request->validate([
            'contract_number' => 'required|string|max:100',
            'partner_name' => 'required|string|max:255', // Changed from partner_id
            'requisition_id' => 'nullable|exists:purchase_requisitions,id',
            'value' => 'required|numeric|min:0',
            'signed_date' => 'nullable|date',
            'expiration_date' => 'nullable|date',
            'warranty_months' => 'nullable|integer|min:0',
            'items' => 'nullable|array',
            'files.*' => 'nullable|file|max:10240',
        ]);

        // Find or create partner based on name
        $partner = Partner::firstOrCreate(['name' => $request->partner_name]);
        $validated['partner_id'] = $partner->id; // Assign the partner_id

        $files = $contract->files ?? [];
        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $file) {
                $path = $file->store('contracts', 'public');
                $files[] = [
                    'name' => $file->getClientOriginalName(),
                    'path' => $path,
                    'size' => $file->getSize()
                ];
            }
        }

        $validated['files'] = $files;

        $contract->update($validated);

        return redirect()->route('contracts.show', $contract)->with('success', 'Hợp đồng đã được cập nhật thành công.');
    }



    public function cancel(Contract $contract)
    {
        $contract->update(['status' => 'cancelled']);
        
        // Manual logging for clarity in action name
        ActivityLog::create([
            'model_type' => get_class($contract),
            'model_id' => $contract->id,
            'user_id' => auth()->id(),
            'action' => 'cancel',
            'description' => 'Đã hủy hợp đồng: ' . $contract->contract_number,
        ]);

        return redirect()->back()->with('success', 'Hợp đồng đã được hủy thành công.');
    }

    public function liquidate(Contract $contract)
    {
        $contract->update(['status' => 'liquidated']);
        
        ActivityLog::create([
            'model_type' => get_class($contract),
            'model_id' => $contract->id,
            'user_id' => auth()->id(),
            'action' => 'liquidate',
            'description' => 'Đã thanh lý hợp đồng: ' . $contract->contract_number,
        ]);

        return redirect()->back()->with('success', 'Hợp đồng đã được thanh lý thành công.');
    }

    public function exportPdf(Contract $contract)
    {
        $contract->load(['partner', 'requisition']);
        $items = $contract->items ?? ($contract->requisition->items ?? []);
        $settings = Setting::all()->pluck('value', 'key');
        
        $pdf = Pdf::loadView('contracts.pdf_contract', compact('contract', 'items', 'settings'));
        
        return $pdf->download('Hop_dong_' . $contract->contract_number . '_' . $contract->code . '.pdf');
    }

    public function exportItems(Contract $contract)
    {
        $items = $contract->items ?? ($contract->requisition->items ?? []);
        if (empty($items)) {
            return redirect()->back()->with('error', 'Hợp đồng này không có danh mục hàng hóa để xuất.');
        }

        $pdf = Pdf::loadView('contracts.pdf_items', compact('contract', 'items'));
        
        return $pdf->download('Danh_muc_hang_hoa_' . $contract->code . '.pdf');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Contract $contract)
    {
        $contract->delete();
        return redirect()->route('contracts.index')->with('success', 'Hợp đồng đã được xóa thành công.');
    }
}
