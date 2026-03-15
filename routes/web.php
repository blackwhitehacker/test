<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PartnerController;
use App\Http\Controllers\AssetGroupController;
use App\Http\Controllers\AssetController;
use App\Http\Controllers\PurchaseRequisitionController;
use App\Http\Controllers\ContractController;
use App\Http\Controllers\ShipmentController;
use App\Http\Controllers\InventoryRequestController;
use App\Http\Controllers\InventoryReceiptController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\InfoLookupController;
use App\Http\Controllers\BusinessRequestController;
use App\Http\Controllers\AllocationStandardController;
use App\Http\Controllers\HandoverRecordController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('dashboard');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Category Management
    Route::get('partners/lookup', [\App\Http\Controllers\PartnerLookupController::class, 'search'])->name('partners.lookup');
    Route::resource('partners', PartnerController::class);
    Route::resource('asset_groups', AssetGroupController::class);
    Route::resource('assets', AssetController::class);

    // Procurement
    Route::post('purchase_requisitions/{purchase_requisition}/approve', [PurchaseRequisitionController::class, 'approve'])->name('purchase_requisitions.approve');
    Route::post('purchase_requisitions/{purchase_requisition}/reject', [PurchaseRequisitionController::class, 'reject'])->name('purchase_requisitions.reject');
    Route::get('purchase_requisitions/{purchase_requisition}/export', [PurchaseRequisitionController::class, 'export'])->name('purchase_requisitions.export');
    Route::resource('purchase_requisitions', PurchaseRequisitionController::class);
    Route::get('contracts/{contract}/export', [ContractController::class, 'exportPdf'])->name('contracts.export');
    Route::post('contracts/{contract}/liquidate', [ContractController::class, 'liquidate'])->name('contracts.liquidate');
    Route::post('contracts/{contract}/cancel', [ContractController::class, 'cancel'])->name('contracts.cancel');
    Route::get('contracts/{contract}/export-items', [ContractController::class, 'exportItems'])->name('contracts.export_items');
    Route::resource('contracts', ContractController::class);
    Route::get('shipments/{shipment}/export', [ShipmentController::class, 'exportPdf'])->name('shipments.export');
    Route::get('shipments/export-list', [ShipmentController::class, 'exportList'])->name('shipments.export_list');
    Route::resource('shipments', ShipmentController::class);

    // Inventory
    Route::post('inventory_requests/{inventory_request}/approve', [InventoryRequestController::class, 'approve'])->name('inventory_requests.approve');
    Route::post('inventory_requests/{inventory_request}/reject', [InventoryRequestController::class, 'reject'])->name('inventory_requests.reject');
    Route::post('inventory_requests/{inventory_request}/cancel', [InventoryRequestController::class, 'cancel'])->name('inventory_requests.cancel');
    Route::post('inventory_requests/{inventory_request}/generate-receipt', [InventoryRequestController::class, 'generateReceipt'])->name('inventory_requests.generate_receipt');
    Route::resource('inventory_requests', InventoryRequestController::class);
    Route::get('inventory_receipts/{inventory_receipt}/export', [InventoryReceiptController::class, 'exportPdf'])->name('inventory_receipts.export');
    Route::post('inventory_receipts/{inventory_receipt}/confirm', [InventoryReceiptController::class, 'confirm'])->name('inventory_receipts.confirm');
    Route::post('inventory_receipts/{inventory_receipt}/save-items', [InventoryReceiptController::class, 'saveItems'])->name('inventory_receipts.save_items');
    Route::resource('inventory_receipts', InventoryReceiptController::class);

    // Settings
    Route::get('settings', [SettingController::class, 'index'])->name('settings.index');
    Route::post('settings', [SettingController::class, 'update'])->name('settings.update');

    // Tra cứu thông tin
    Route::get('/info-lookup/assets', [InfoLookupController::class, 'assets'])->name('info_lookup.assets');
    Route::get('/info-lookup/inventory', [InfoLookupController::class, 'inventory'])->name('info_lookup.inventory');

    // Yêu cầu nghiệp vụ (Cấp phát, Sửa chữa, Thu hồi)
    Route::get('/my-assets', [BusinessRequestController::class, 'myAssets'])->name('business_requests.my_assets');
    Route::post('/business_requests/{business_request}/cancel', [BusinessRequestController::class, 'cancel'])->name('business_requests.cancel');
    Route::post('/business_requests/{business_request}/approve', [BusinessRequestController::class, 'approve'])->name('business_requests.approve');
    Route::post('/business_requests/{business_request}/reject', [BusinessRequestController::class, 'reject'])->name('business_requests.reject');
    Route::post('/business_requests/{business_request}/escalate', [BusinessRequestController::class, 'escalate'])->name('business_requests.escalate');
    Route::post('/business_requests/{business_request}/assessment', [BusinessRequestController::class, 'submitAssessment'])->name('business_requests.assessment');
    Route::post('/business_requests/{business_request}/repair_update', [BusinessRequestController::class, 'submitRepairUpdate'])->name('business_requests.repair_update');
    Route::post('/business_requests/{business_request}/liquidation_update', [BusinessRequestController::class, 'submitLiquidationUpdate'])->name('business_requests.liquidation_update');
    Route::get('/business_requests/{business_request}/export_liquidation', [BusinessRequestController::class, 'exportLiquidation'])->name('business_requests.export_liquidation');
    Route::get('/business_requests/check-compliance/{business_request}', [BusinessRequestController::class, 'checkCompliance'])->name('business_requests.check_compliance');
    Route::resource('business_requests', BusinessRequestController::class);

    // Định mức cấp phát
    Route::resource('allocation_standards', AllocationStandardController::class);

    // Biên bản bàn giao
    Route::post('/handover_records/{handover_record}/sign', [HandoverRecordController::class, 'sign'])->name('handover_records.sign');
    Route::get('/handover_records/{handover_record}/export', [HandoverRecordController::class, 'exportPdf'])->name('handover_records.export');
    Route::resource('handover_records', HandoverRecordController::class);
});

require __DIR__.'/auth.php';
