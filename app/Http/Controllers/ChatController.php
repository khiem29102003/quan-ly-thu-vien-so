<?php

namespace App\Http\Controllers;

use App\Services\ChatService;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    protected $chatService;

    public function __construct(ChatService $chatService)
    {
        $this->chatService = $chatService;
    }

    /**
     * Xử lý tin nhắn chat từ người dùng
     */
    public function sendMessage(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:500',
            'user_id' => 'nullable|exists:users,id'
        ]);

        $message = $request->input('message');
        $userId = $request->input('user_id', auth()->id());

        try {
            $response = $this->chatService->processMessage($message, $userId);
            
            return response()->json([
                'success' => true,
                'response' => $response['message'],
                'suggestions' => $response['suggestions'] ?? [],
                'data' => $response['data'] ?? null
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'response' => 'Xin lỗi, tôi gặp sự cố khi xử lý câu hỏi của bạn. Vui lòng thử lại.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Lấy các câu hỏi gợi ý
     */
    public function getSuggestions()
    {
        $suggestions = [
            'Tìm sách về lập trình',
            'Sách nào đang được mượn nhiều nhất?',
            'Tôi muốn mượn sách như thế nào?',
            'Có sách nào về khoa học không?',
            'Gợi ý sách hay cho tôi',
            'Làm thế nào để trả sách?',
            'Phí phạt quá hạn là bao nhiêu?',
            'Tôi có thể mượn bao nhiêu sách cùng lúc?'
        ];

        return response()->json([
            'suggestions' => $suggestions
        ]);
    }

    /**
     * Xóa lịch sử chat (nếu có lưu trữ)
     */
    public function clearHistory(Request $request)
    {
        $userId = $request->input('user_id', auth()->id());
        
        // TODO: Implement chat history clearing if needed
        
        return response()->json([
            'success' => true,
            'message' => 'Đã xóa lịch sử chat'
        ]);
    }
}
