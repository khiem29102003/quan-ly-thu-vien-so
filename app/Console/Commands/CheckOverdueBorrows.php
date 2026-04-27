<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Borrow;
use Carbon\Carbon;

class CheckOverdueBorrows extends Command
{
    protected $signature = 'borrows:check-overdue';
    protected $description = 'Tự động kiểm tra và đánh dấu các phiếu mượn quá hạn';

    public function handle()
    {
        $now = Carbon::now();
        
        // Tìm tất cả phiếu mượn đang active và đã quá hạn
        $overdueBorrows = Borrow::where('status', 'borrowed')
            ->where('due_date', '<', $now)
            ->get();
            
        $count = 0;
        foreach ($overdueBorrows as $borrow) {
            $borrow->update(['status' => 'overdue']);
            
            // Tính tiền phạt (10,000 VND mỗi ngày quá hạn)
            $daysOverdue = $now->diffInDays($borrow->due_date);
            $lateFee = $daysOverdue * 10000;
            $borrow->update(['late_fee' => $lateFee]);
            
            $count++;
        }
        
        $this->info("✓ Đã kiểm tra và cập nhật {$count} phiếu mượn quá hạn.");
        
        return Command::SUCCESS;
    }
}
