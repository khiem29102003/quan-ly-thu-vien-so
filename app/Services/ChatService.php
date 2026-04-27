<?php

namespace App\Services;

use App\Models\Book;
use App\Models\Borrow;
use App\Models\User;
use App\Models\Category;
use Illuminate\Support\Facades\DB;

class ChatService
{
    /**
     * Xử lý tin nhắn và trả về phản hồi AI
     */
    public function processMessage(string $message, $userId = null)
    {
        $messageLower = mb_strtolower($message, 'UTF-8');
        
        // Phân tích ý định của tin nhắn
        $intent = $this->detectIntent($messageLower);
        
        // Xử lý dựa trên ý định
        switch ($intent) {
            case 'search_book':
                return $this->handleBookSearch($messageLower);
            
            case 'book_recommendation':
                return $this->handleRecommendation($userId);
            
            case 'borrow_info':
                return $this->handleBorrowInfo($userId);
            
            case 'how_to_borrow':
                return $this->handleHowToBorrow();
            
            case 'how_to_return':
                return $this->handleHowToReturn();
            
            case 'late_fee':
                return $this->handleLateFeeInfo();
            
            case 'popular_books':
                return $this->handlePopularBooks();
            
            case 'categories':
                return $this->handleCategories();
            
            case 'greeting':
                return $this->handleGreeting();
            
            case 'help':
                return $this->handleHelp();
            
            default:
                return $this->handleDefault($messageLower);
        }
    }

    /**
     * Phát hiện ý định từ tin nhắn
     */
    private function detectIntent(string $message): string
    {
        // Greeting - Chào hỏi (check đầu tiên)
        if (preg_match('/(xin chào|chào|hello|hi|hey)/i', $message)) {
            return 'greeting';
        }

        // Help - Trợ giúp (cụ thể)
        if (preg_match('/(trợ giúp|help|hướng dẫn|guide)/i', $message)) {
            return 'help';
        }

        // Categories - Danh mục (cụ thể)
        if (preg_match('/(danh mục|thể loại|categories|loại sách|có những.*loại)/i', $message)) {
            return 'categories';
        }

        // Popular books - Sách phổ biến (cụ thể)
        if (preg_match('/(phổ biến|được mượn nhiều|popular|trend|xu hướng|hot|top|top)/i', $message)) {
            return 'popular_books';
        }

        // Late fee - Phí phạt (cụ thể)
        if (preg_match('/(phí|phạt|quá hạn|late|fee)/i', $message)) {
            return 'late_fee';
        }

        // How to return - Hướng dẫn trả (cụ thể)
        if (preg_match('/(làm thế nào|cách|how to).*(trả|return)/i', $message)) {
            return 'how_to_return';
        }

        // How to borrow - Hướng dẫn mượn (cụ thể)
        if (preg_match('/(làm thế nào|cách|how to).*(mượn|borrow)/i', $message)) {
            return 'how_to_borrow';
        }

        // Borrow information - Thông tin mượn (cụ thể)
        if (preg_match('/(mượn của tôi|sách đang mượn|đang mượn|my borrow|sách.*mượn)/i', $message)) {
            return 'borrow_info';
        }

        // Recommendation - Gợi ý (cụ thể)
        if (preg_match('/(gợi ý|đề xuất|recommend|giới thiệu|nên đọc|sách hay|hay cho)/i', $message)) {
            return 'book_recommendation';
        }

        // Search book - Tìm sách (chung nhất - check cuối cùng)
        if (preg_match('/(tìm|tìm kiếm|có sách|sách về|sách|book)/i', $message)) {
            return 'search_book';
        }

        return 'unknown';
    }

    /**
     * Xử lý tìm kiếm sách
     */
    private function handleBookSearch(string $message): array
    {
        // Trích xuất từ khóa tìm kiếm
        $keywords = $this->extractSearchKeywords($message);
        
        if (empty($keywords)) {
            return [
                'message' => '📚 Bạn muốn tìm sách gì? Hãy cho tôi biết tên sách, tác giả, hoặc chủ đề bạn quan tâm!',
                'suggestions' => [
                    'Tìm sách về lập trình',
                    'Sách khoa học',
                    'Tiểu thuyết hay'
                ]
            ];
        }

        $books = Book::where('title', 'like', "%{$keywords}%")
                    ->orWhere('author', 'like', "%{$keywords}%")
                    ->orWhere('description', 'like', "%{$keywords}%")
                    ->where('available_copies', '>', 0)
                    ->limit(5)
                    ->get();

        // Nếu tìm kiếm trực tiếp không có, thử tìm qua danh mục
        if ($books->isEmpty()) {
            $categories = Category::where('name', 'like', "%{$keywords}%")->get();
            if ($categories->isNotEmpty()) {
                $books = Book::whereIn('category_id', $categories->pluck('id'))
                           ->where('available_copies', '>', 0)
                           ->limit(5)
                           ->get();
                
                if ($books->isNotEmpty()) {
                    $categoryNames = $categories->pluck('name')->implode(', ');
                    $bookList = $books->map(function($book) {
                        $available = $book->available_copies > 0 ? '✅ Có sẵn' : '❌ Hết sách';
                        return "📖 **{$book->title}** - {$book->author}\n   {$available} ({$book->available_copies}/{$book->total_copies} cuốn)";
                    })->implode("\n\n");

                    return [
                        'message' => "🔍 Tôi tìm thấy {$books->count()} cuốn sách trong danh mục **{$categoryNames}**:\n\n{$bookList}\n\n💡 Bạn có thể vào trang \"Sách\" để xem chi tiết và mượn sách!",
                        'data' => $books,
                        'suggestions' => [
                            'Chi tiết sách đầu tiên',
                            'Gợi ý sách khác',
                            'Làm thế nào để mượn sách?'
                        ]
                    ];
                }
            }
        }

        if ($books->isEmpty()) {
            return [
                'message' => "😔 Xin lỗi, tôi không tìm thấy sách nào về **\"{$keywords}\"**. Bạn có thể:\n\n" .
                            "• Thử tìm kiếm với từ khóa khác\n" .
                            "• Xem danh mục sách\n" .
                            "• Xem sách phổ biến\n" .
                            "• Yêu cầu gợi ý",
                'suggestions' => [
                    'Sách phổ biến nhất',
                    'Gợi ý sách cho tôi',
                    'Xem danh mục sách'
                ]
            ];
        }

        $bookList = $books->map(function($book) {
            $available = $book->available_copies > 0 ? '✅ Có sẵn' : '❌ Hết sách';
            return "📖 **{$book->title}** - {$book->author}\n   {$available} ({$book->available_copies}/{$book->total_copies} cuốn)";
        })->implode("\n\n");

        return [
            'message' => "🔍 Tôi tìm thấy {$books->count()} cuốn sách về **\"{$keywords}\"**:\n\n{$bookList}\n\n💡 Bạn có thể vào trang \"Sách\" để xem chi tiết và mượn sách!",
            'data' => $books,
            'suggestions' => [
                'Chi tiết sách đầu tiên',
                'Gợi ý sách khác',
                'Làm thế nào để mượn sách?'
            ]
        ];
    }

    /**
     * Xử lý gợi ý sách
     */
    private function handleRecommendation($userId): array
    {
        if (!$userId) {
            return [
                'message' => '📚 Để tôi gợi ý sách phù hợp, bạn vui lòng đăng nhập nhé! Hoặc bạn có thể xem danh sách sách phổ biến.',
                'suggestions' => [
                    'Sách phổ biến nhất',
                    'Xem tất cả sách',
                    'Danh mục sách'
                ]
            ];
        }

        // Lấy lịch sử mượn
        $userBorrows = Borrow::where('user_id', $userId)
                            ->where('status', 'returned')
                            ->with('book.category')
                            ->limit(10)
                            ->get();

        if ($userBorrows->isEmpty()) {
            // Gợi ý sách phổ biến nếu chưa có lịch sử
            $popularBooks = Book::where('available_copies', '>', 0)
                               ->orderBy('rating', 'desc')
                               ->limit(5)
                               ->get();

            $bookList = $popularBooks->map(function($book) {
                return "📖 **{$book->title}** - {$book->author}\n   ⭐ Đánh giá: {$book->rating}/5";
            })->implode("\n\n");

            return [
                'message' => "🌟 Bạn chưa mượn sách nào! Đây là một số sách được đánh giá cao:\n\n{$bookList}",
                'data' => $popularBooks,
                'suggestions' => [
                    'Tìm sách khoa học',
                    'Sách tiểu thuyết',
                    'Làm thế nào để mượn?'
                ]
            ];
        }

        // Lấy danh mục yêu thích
        $favoriteCategories = $userBorrows->pluck('book.category_id')->unique();

        // Gợi ý sách cùng danh mục
        $recommendations = Book::whereIn('category_id', $favoriteCategories)
                              ->whereNotIn('id', $userBorrows->pluck('book_id'))
                              ->where('available_copies', '>', 0)
                              ->orderBy('rating', 'desc')
                              ->limit(5)
                              ->get();

        if ($recommendations->isEmpty()) {
            return [
                'message' => '📚 Hiện tại tôi chưa có gợi ý phù hợp. Bạn có thể xem các sách mới nhất hoặc sách được đánh giá cao!',
                'suggestions' => [
                    'Sách phổ biến',
                    'Xem tất cả sách',
                    'Danh mục sách'
                ]
            ];
        }

        $bookList = $recommendations->map(function($book) {
            return "📖 **{$book->title}** - {$book->author}\n   ⭐ {$book->rating}/5 | ✅ {$book->available_copies} cuốn có sẵn";
        })->implode("\n\n");

        return [
            'message' => "🎯 Dựa trên sở thích của bạn, tôi gợi ý {$recommendations->count()} cuốn sách:\n\n{$bookList}\n\n💡 Những sách này thuộc thể loại bạn thường đọc!",
            'data' => $recommendations,
            'suggestions' => [
                'Chi tiết sách đầu tiên',
                'Tìm sách khác',
                'Sách đang mượn của tôi'
            ]
        ];
    }

    /**
     * Xử lý thông tin mượn sách của user
     */
    private function handleBorrowInfo($userId): array
    {
        if (!$userId) {
            return [
                'message' => '🔐 Bạn cần đăng nhập để xem thông tin mượn sách của mình.',
                'suggestions' => ['Hướng dẫn mượn sách', 'Xem sách phổ biến']
            ];
        }

        $activeBorrows = Borrow::where('user_id', $userId)
                              ->where('status', 'borrowed')
                              ->with('book')
                              ->get();

        if ($activeBorrows->isEmpty()) {
            return [
                'message' => '📚 Bạn hiện không đang mượn sách nào. Hãy khám phá thư viện và tìm sách yêu thích nhé!',
                'suggestions' => [
                    'Gợi ý sách cho tôi',
                    'Tìm sách',
                    'Làm thế nào để mượn?'
                ]
            ];
        }

        $borrowList = $activeBorrows->map(function($borrow) {
            $daysLeft = now()->diffInDays($borrow->due_date, false);
            $status = $daysLeft > 0 
                ? "⏰ Còn {$daysLeft} ngày" 
                : "⚠️ Quá hạn " . abs($daysLeft) . " ngày";
            
            return "📖 **{$borrow->book->title}**\n   Mượn: {$borrow->borrow_date}\n   Hạn trả: {$borrow->due_date}\n   {$status}";
        })->implode("\n\n");

        return [
            'message' => "📋 Bạn đang mượn {$activeBorrows->count()} cuốn sách:\n\n{$borrowList}\n\n💡 Nhớ trả sách đúng hạn để tránh phí phạt nhé!",
            'data' => $activeBorrows,
            'suggestions' => [
                'Làm thế nào để trả sách?',
                'Phí phạt quá hạn',
                'Gợi ý sách khác'
            ]
        ];
    }

    /**
     * Hướng dẫn cách mượn sách
     */
    private function handleHowToBorrow(): array
    {
        return [
            'message' => "📖 **Hướng dẫn mượn sách:**\n\n" .
                        "1. 🔍 Tìm sách bạn muốn ở trang \"Sách\"\n" .
                        "2. ✅ Kiểm tra sách còn có sẵn không\n" .
                        "3. 📝 Nhấn \"Mượn sách\" hoặc liên hệ thủ thư\n" .
                        "4. ⏰ Thời gian mượn: tối đa 14 ngày\n" .
                        "5. 📚 Mỗi người được mượn tối đa 3 cuốn cùng lúc\n\n" .
                        "💡 Lưu ý: Trả sách đúng hạn để tránh phí phạt 5.000đ/ngày!",
            'suggestions' => [
                'Tìm sách',
                'Phí phạt quá hạn',
                'Sách đang mượn của tôi'
            ]
        ];
    }

    /**
     * Hướng dẫn cách trả sách
     */
    private function handleHowToReturn(): array
    {
        return [
            'message' => "📤 **Hướng dẫn trả sách:**\n\n" .
                        "1. 📍 Mang sách đến quầy thủ thư\n" .
                        "2. 👤 Cung cấp thông tin tài khoản\n" .
                        "3. ✅ Thủ thư kiểm tra và xác nhận\n" .
                        "4. 💰 Thanh toán phí (nếu trả muộn)\n" .
                        "5. ✔️ Hoàn tất!\n\n" .
                        "⏰ **Giờ làm việc:** Thứ 2 - Thứ 6: 8:00 - 17:00\n\n" .
                        "💡 Tip: Kiểm tra ngày hết hạn trước khi đến để tránh phí phạt!",
            'suggestions' => [
                'Phí phạt quá hạn',
                'Sách đang mượn',
                'Tìm sách mới'
            ]
        ];
    }

    /**
     * Thông tin phí phạt
     */
    private function handleLateFeeInfo(): array
    {
        return [
            'message' => "💰 **Thông tin phí phạt:**\n\n" .
                        "📌 Phí trả sách muộn: **5.000đ/ngày/sách**\n\n" .
                        "**Ví dụ:**\n" .
                        "• Trễ 1 ngày: 5.000đ\n" .
                        "• Trễ 1 tuần: 35.000đ\n" .
                        "• Trễ 1 tháng: 150.000đ\n\n" .
                        "⚠️ **Lưu ý:**\n" .
                        "• Trả trễ quá 30 ngày: Tạm khóa tài khoản\n" .
                        "• Làm mất sách: Đền bù 200% giá trị sách\n\n" .
                        "💡 Hãy trả sách đúng hạn để tránh phí phạt nhé!",
            'suggestions' => [
                'Sách đang mượn',
                'Làm thế nào để trả sách?',
                'Tìm sách mới'
            ]
        ];
    }

    /**
     * Xử lý sách phổ biến
     */
    private function handlePopularBooks(): array
    {
        $popularBooks = Borrow::select('book_id', DB::raw('COUNT(*) as borrow_count'))
                             ->where('created_at', '>=', now()->subMonths(3))
                             ->groupBy('book_id')
                             ->orderBy('borrow_count', 'desc')
                             ->with('book')
                             ->limit(5)
                             ->get();

        if ($popularBooks->isEmpty()) {
            return [
                'message' => '📚 Hiện chưa có thống kê sách phổ biến. Hãy khám phá thư viện nhé!',
                'suggestions' => ['Xem tất cả sách', 'Gợi ý cho tôi']
            ];
        }

        $bookList = $popularBooks->map(function($item, $index) {
            $rank = $index + 1;
            $emoji = $rank === 1 ? '🥇' : ($rank === 2 ? '🥈' : ($rank === 3 ? '🥉' : '📖'));
            return "{$emoji} **{$item->book->title}** - {$item->book->author}\n   📊 Được mượn {$item->borrow_count} lần";
        })->implode("\n\n");

        return [
            'message' => "🔥 **Top 5 sách được mượn nhiều nhất (3 tháng gần đây):**\n\n{$bookList}\n\n💡 Những cuốn sách này rất được yêu thích!",
            'data' => $popularBooks,
            'suggestions' => [
                'Chi tiết sách top 1',
                'Gợi ý sách cho tôi',
                'Làm thế nào để mượn?'
            ]
        ];
    }

    /**
     * Xử lý danh mục
     */
    private function handleCategories(): array
    {
        $categories = Category::withCount('books')->get();

        if ($categories->isEmpty()) {
            return [
                'message' => '📚 Hiện chưa có danh mục nào.',
                'suggestions' => ['Xem tất cả sách']
            ];
        }

        $categoryList = $categories->map(function($category) {
            return "📁 **{$category->name}**\n   📚 {$category->books_count} cuốn sách";
        })->implode("\n\n");

        return [
            'message' => "📂 **Danh mục sách có trong thư viện:**\n\n{$categoryList}\n\n💡 Bạn muốn xem sách ở danh mục nào?",
            'data' => $categories,
            'suggestions' => [
                'Tìm sách khoa học',
                'Tìm tiểu thuyết',
                'Sách phổ biến'
            ]
        ];
    }

    /**
     * Xử lý chào hỏi
     */
    private function handleGreeting(): array
    {
        $greetings = [
            "👋 Xin chào! Tôi là trợ lý AI của thư viện. Tôi có thể giúp gì cho bạn?",
            "😊 Chào bạn! Hãy hỏi tôi về sách, mượn trả, hoặc bất cứ điều gì về thư viện!",
            "🤗 Hello! Tôi sẵn sàng hỗ trợ bạn tìm sách, gợi ý đọc, và trả lời thắc mắc!"
        ];

        return [
            'message' => $greetings[array_rand($greetings)],
            'suggestions' => [
                'Gợi ý sách cho tôi',
                'Tìm sách',
                'Hướng dẫn mượn sách',
                'Sách phổ biến'
            ]
        ];
    }

    /**
     * Xử lý yêu cầu trợ giúp
     */
    private function handleHelp(): array
    {
        return [
            'message' => "🤖 **Tôi có thể giúp bạn:**\n\n" .
                        "🔍 **Tìm sách:** \"Tìm sách về lập trình\"\n" .
                        "💡 **Gợi ý sách:** \"Gợi ý sách hay cho tôi\"\n" .
                        "📋 **Xem sách đang mượn:** \"Tôi đang mượn sách gì?\"\n" .
                        "📖 **Hướng dẫn mượn:** \"Làm thế nào để mượn sách?\"\n" .
                        "📤 **Hướng dẫn trả:** \"Cách trả sách\"\n" .
                        "💰 **Phí phạt:** \"Phí phạt quá hạn là bao nhiêu?\"\n" .
                        "🔥 **Sách phổ biến:** \"Sách nào được mượn nhiều nhất?\"\n" .
                        "📂 **Danh mục:** \"Có những danh mục sách nào?\"\n\n" .
                        "💬 Hãy hỏi tôi bất cứ điều gì!",
            'suggestions' => [
                'Gợi ý sách',
                'Tìm sách',
                'Sách phổ biến',
                'Hướng dẫn mượn'
            ]
        ];
    }

    /**
     * Xử lý mặc định
     */
    private function handleDefault(string $message): array
    {
        return [
            'message' => "🤔 Xin lỗi, tôi chưa hiểu câu hỏi của bạn. Bạn có thể hỏi tôi về:\n\n" .
                        "• 🔍 Tìm sách\n" .
                        "• 💡 Gợi ý sách\n" .
                        "• 📋 Thông tin mượn/trả\n" .
                        "• 🔥 Sách phổ biến\n" .
                        "• 📂 Danh mục sách\n\n" .
                        "Hoặc gõ \"trợ giúp\" để xem hướng dẫn chi tiết!",
            'suggestions' => [
                'Trợ giúp',
                'Gợi ý sách',
                'Tìm sách',
                'Sách phổ biến'
            ]
        ];
    }

    /**
     * Trích xuất từ khóa tìm kiếm
     */
    private function extractSearchKeywords(string $message): string
    {
        // Loại bỏ các từ dừng
        $stopWords = ['tìm', 'tìm kiếm', 'sách', 'có', 'về', 'cho', 'tôi', 'mình', 'không', 'book', 'find'];
        
        $words = preg_split('/\s+/', $message);
        $keywords = array_filter($words, function($word) use ($stopWords) {
            return strlen($word) > 2 && !in_array(mb_strtolower($word, 'UTF-8'), $stopWords);
        });

        return implode(' ', $keywords);
    }
}
