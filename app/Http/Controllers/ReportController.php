<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\AssetGroup;
use App\Models\Contract;
use App\Models\InventoryRequest;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function assets(Request $request)
    {
        $query = Asset::with(['group', 'partner', 'user']);
        
        if ($request->filled('department')) {
            $query->where('assigned_department', $request->department);
        }
        
        $assets = $query->paginate(20);
        $departments = Asset::whereNotNull('assigned_department')->distinct()->pluck('assigned_department');
        
        return view('reports.assets', compact('assets', 'departments'));
    }

    public function scale()
    {
        // Simple scale aggregation
        $groups = AssetGroup::withCount('assets')->get();
        return view('reports.scale', compact('groups'));
    }

    public function inventory(Request $request)
    {
        $query = Asset::where('status', 'inventory')->with(['group']);
        
        if ($request->filled('group_id')) {
            $query->where('group_id', $request->group_id);
        }
        
        $assets = $query->paginate(20);
        $groups = AssetGroup::all();
        
        return view('reports.inventory', compact('assets', 'groups'));
    }

    public function procurement(Request $request)
    {
        $year = $request->get('year', date('Y'));
        $contracts = Contract::with(['partner'])
            ->whereYear('signed_date', $year)
            ->get();
            
        return view('reports.procurement', compact('contracts', 'year'));
    }

    public function liquidation(Request $request)
    {
        $requests = InventoryRequest::where('source_type', 'liquidation')
            ->where('status', 'completed')
            ->with(['items.asset'])
            ->get();
            
        return view('reports.liquidation', compact('requests'));
    }

    public function exportAssets()
    {
        $assets = Asset::with(['group', 'partner', 'user'])->get();
        $pdf = Pdf::loadView('reports.pdf_assets', compact('assets'));
        return $pdf->download('bao_cao_tai_san_' . now()->format('Ymd') . '.pdf');
    }

    public function exportScale()
    {
        $groups = AssetGroup::withCount('assets')->get();
        $pdf = Pdf::loadView('reports.pdf_scale', compact('groups'));
        return $pdf->download('bao_cao_quy_mo_' . now()->format('Ymd') . '.pdf');
    }

    public function exportInventory()
    {
        $assets = Asset::where('status', 'inventory')->with(['group'])->get();
        $pdf = Pdf::loadView('reports.pdf_inventory', compact('assets'));
        return $pdf->download('bao_cao_ton_kho_' . now()->format('Ymd') . '.pdf');
    }

    public function exportProcurement()
    {
        $contracts = Contract::with(['partner'])->get();
        $pdf = Pdf::loadView('reports.pdf_procurement', compact('contracts'));
        return $pdf->download('bao_cao_mua_sam_' . now()->format('Ymd') . '.pdf');
    }

    public function exportLiquidation()
    {
        $requests = InventoryRequest::where('source_type', 'liquidation')
            ->where('status', 'completed')
            ->with(['items.asset'])
            ->get();
        $pdf = Pdf::loadView('reports.pdf_liquidation', compact('requests'));
        return $pdf->download('bao_cao_thanh_ly_' . now()->format('Ymd') . '.pdf');
    }
}
