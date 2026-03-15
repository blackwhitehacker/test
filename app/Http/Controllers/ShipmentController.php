<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Shipment;
use App\Models\Contract;
use App\Models\User;
use App\Models\ActivityLog;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ShipmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Shipment::with(['contract.partner']);

        if ($request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('code', 'LIKE', "%{$search}%")
                  ->orWhere('receiver_name', 'LIKE', "%{$search}%");
            })->orWhereHas('contract', function($q) use ($search) {
                $q->where('contract_number', 'LIKE', "%{$search}%")
                  ->orWhereHas('partner', function($pq) use ($search) {
                      $pq->where('name', 'LIKE', "%{$search}%");
                  });
            });
        }

        $shipments = $query->latest()->paginate(10);

        return view('shipments.index', compact('shipments'));
    }

    public function create(Request $request)
    {
        $contracts = Contract::where(function($query) {
            $query->where('status', 'active');
        })->get();
        $selectedContract = null;
        $items = [];

        if ($request->contract_id) {
            $selectedContract = Contract::with('requisition')->findOrFail($request->contract_id);
            // Prioritize contract items over requisition items
            $items = $selectedContract->items ?? ($selectedContract->requisition->items ?? []);
        }

        $users = User::all();

        return view('shipments.create', compact('contracts', 'selectedContract', 'items', 'users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'contract_id' => 'required|exists:contracts,id',
            'delivery_date' => 'required|date',
            'status' => 'required|string',
            'receiver_name' => 'nullable|string|max:255',
            'items' => 'required|array',
        ]);

        $shipment = Shipment::create([
            'code' => $this->generateCode(),
            'contract_id' => $request->contract_id,
            'delivery_date' => $request->delivery_date,
            'status' => $request->status,
            'receiver_name' => $request->receiver_name,
            'items' => $request->items,
            'note' => $request->note,
        ]);

        ActivityLog::create([
            'model_type' => Shipment::class,
            'model_id' => $shipment->id,
            'user_id' => Auth::id(),
            'action' => 'create',
            'description' => "Khởi tạo lô hàng {$shipment->code} cho hợp đồng " . $shipment->contract->contract_number,
        ]);

        return redirect()->route('shipments.show', $shipment)->with('success', 'Lô hàng đã được tạo thành công.');
    }

    public function show(Shipment $shipment)
    {
        $shipment->load(['contract.partner', 'receiver', 'activityLogs.user']);
        return view('shipments.show', compact('shipment'));
    }

    public function edit(Shipment $shipment)
    {
        $contracts = Contract::where('status', 'active')->get();
        $users = User::all();
        return view('shipments.edit', compact('shipment', 'contracts', 'users'));
    }

    public function update(Request $request, Shipment $shipment)
    {
        $request->validate([
            'delivery_date' => 'required|date',
            'status' => 'required|string',
            'receiver_name' => 'nullable|string|max:255',
            'items' => 'required|array',
        ]);

        $shipment->update([
            'delivery_date' => $request->delivery_date,
            'status' => $request->status,
            'receiver_name' => $request->receiver_name,
            'items' => $request->items,
            'note' => $request->note,
        ]);

        ActivityLog::create([
            'model_type' => Shipment::class,
            'model_id' => $shipment->id,
            'user_id' => Auth::id(),
            'action' => 'update',
            'description' => "Cập nhật thông tin lô hàng {$shipment->code}",
        ]);

        return redirect()->route('shipments.show', $shipment)->with('success', 'Lô hàng đã được cập nhật thành công.');
    }

    public function destroy(Shipment $shipment)
    {
        $code = $shipment->code;
        $shipment->delete();

        ActivityLog::create([
            'model_type' => Shipment::class,
            'model_id' => 0,
            'user_id' => Auth::id(),
            'action' => 'delete',
            'description' => "Xóa lô hàng {$code}",
        ]);

        return redirect()->route('shipments.index')->with('success', 'Lô hàng đã được xóa.');
    }

    public function exportPdf(Shipment $shipment)
    {
        $shipment->load(['contract.partner', 'receiver']);
        $settings = \App\Models\Setting::all()->pluck('value', 'key');
        
        $pdf = Pdf::loadView('shipments.pdf_detail', compact('shipment', 'settings'));
        return $pdf->download('Lo_hang_' . $shipment->code . '.pdf');
    }

    public function exportList(Request $request)
    {
        $shipments = Shipment::with(['contract.partner', 'receiver'])->latest()->get();
        $settings = \App\Models\Setting::all()->pluck('value', 'key');
        
        $pdf = Pdf::loadView('shipments.pdf_list', compact('shipments', 'settings'));
        return $pdf->download('Danh_sach_lo_hang_' . now()->format('dmY') . '.pdf');
    }

    private function generateCode()
    {
        $count = Shipment::count() + 1;
        return 'LH' . str_pad($count, 6, '0', STR_PAD_LEFT);
    }
}
