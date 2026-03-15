<?php

namespace App\Console\Commands;

use App\Models\Contract;
use App\Models\ActivityLog;
use Illuminate\Console\Command;

class AutoLiquidateContracts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'contracts:auto-liquidate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Tự động thanh lý các hợp đồng đã hết hạn (expiration_date < today)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Đang kiểm tra các hợp đồng hết hạn...');

        $expiredContracts = Contract::where('status', 'active')
            ->whereNotNull('expiration_date')
            ->where('expiration_date', '<', now()->toDateString())
            ->get();

        if ($expiredContracts->isEmpty()) {
            $this->info('Không tìm thấy hợp đồng nào cần thanh lý.');
            return;
        }

        foreach ($expiredContracts as $contract) {
            $contract->update(['status' => 'liquidated']);
            
            ActivityLog::create([
                'model_type' => get_class($contract),
                'model_id' => $contract->id,
                'user_id' => 1, // System user
                'action' => 'liquidate',
                'description' => 'Hệ thống tự động thanh lý hợp đồng do hết hạn: ' . $contract->contract_number,
            ]);

            $this->line("Đã thanh lý: {$contract->contract_number}");
        }

        $this->info('Hoàn tất thanh lý ' . $expiredContracts->count() . ' hợp đồng.');
    }
}
