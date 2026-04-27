<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class WebhookController extends Controller
{
    /**
     * Endpoint API nhận Webhook từ SePay / Các cổng trung gian
     * URL: POST /api/webhooks/bank
     */
    public function handleBankTransfer(Request $request)
    {
        // Ghi Log toàn bộ request gửi về để debug
        Log::info('Received Payment Webhook:', $request->all());

        // Định dạng Webhook của SePay thường gửi lên:
        // {
        //   "gateway": "Vietcombank",
        //   "transactionDate": "2023-10-10 10:10:10",
        //   "accountNumber": "0987654321",
        //   "code": "NAP 5", 
        //   "content": "NAP 5 chuyen khoan",
        //   "transferType": "in",
        //   "transferAmount": 50000,
        //   "accumulated": 150000,
        //   "id": 12345
        // }
        // Để linh hoạt, ta kiểm tra các field `content` hoặc `description` hoặc `code`
        
        $content = strtoupper($request->input('content', $request->input('description', $request->input('code', ''))));
        $amount = (int) $request->input('transferAmount', $request->input('amount', 0));
        
        // Nếu transferType là "out" (trừ tiền) thì bỏ qua
        if ($request->input('transferType') === 'out') {
            return response()->json(['success' => true, 'message' => 'Ignored outgoing transfer.']);
        }

        if ($amount <= 0 || empty($content)) {
            return response()->json(['success' => false, 'message' => 'Invalid amount or content.'], 400);
        }

        // Tìm cú pháp chữ NAP [Số_ID]
        // Ví dụ: NAP 12, nap 12, NAP12, vv...
        if (preg_match('/NAP\s*(\d+)/', $content, $matches)) {
            $userId = (int) $matches[1];
            
            $user = User::find($userId);
            if (!$user) {
                return response()->json(['success' => false, 'message' => 'User ID not found in system.'], 404);
            }

            // Giao dịch vào database
            DB::transaction(function () use ($user, $amount, $content, $request) {
                // Cộng tiền vào user
                $user->increment('wallet_balance', $amount);

                // Ghi nhận Activity Log (tương đương hóa đơn Nạp ví Thành Công)
                ActivityLog::create([
                    'log_name' => 'wallet_topups',
                    'description' => 'He thong tu dong duyet Webhook',
                    'event' => 'approved',
                    'causer_id' => $user->id,
                    'causer_type' => User::class,
                    'subject_id' => $user->id,
                    'subject_type' => User::class,
                    'properties' => [
                        'amount' => $amount,
                        'note' => "[AUTO-WEBHOOK] " . $content,
                        'status' => 'approved',
                        'approved_by' => null,
                        'approved_by_name' => 'Hệ Thống (Auto)',
                        'approved_at' => now()->toDateTimeString(),
                        'final_wallet_balance' => (float) $user->fresh()->wallet_balance,
                        'webhook_raw' => $request->all()
                    ],
                    'ip_address' => $request->ip(),
                    'user_agent' => 'SePay Webhook Engine'
                ]);
            });

            return response()->json(['success' => true, 'message' => 'Topup automated successfully for User ID ' . $user->id]);
        }

        return response()->json(['success' => false, 'message' => 'No matching NAP syntax found in transfer content.']);
    }
}
