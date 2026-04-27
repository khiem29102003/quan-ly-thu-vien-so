<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Recommendation;
use App\Models\Borrow;
use Illuminate\Http\Request;

class AiController extends Controller
{
    // Gợi ý sách dựa trên lịch sử mượn
    public function getRecommendations($userId)
    {
        $user = \App\Models\User::findOrFail($userId);
        $userBorrows = Borrow::where('user_id', $userId)
                            ->where('status', 'returned')
                            ->with('book.category')
                            ->get();

        if ($userBorrows->isEmpty()) {
            return response()->json(['recommendations' => []]);
        }

        // Lấy các danh mục sách người dùng đã mượn
        $categories = $userBorrows->pluck('book.category_id')->unique();

        // Tìm sách cùng danh mục mà người dùng chưa mượn
        $recommendations = Book::whereIn('category_id', $categories)
                              ->whereNotIn('id', $userBorrows->pluck('book_id'))
                              ->orderBy('rating', 'desc')
                              ->limit(5)
                              ->get();

        foreach ($recommendations as $book) {
            $confidence = 75 + rand(0, 20);
            Recommendation::updateOrCreate(
                ['user_id' => $userId, 'book_id' => $book->id],
                ['confidence_score' => $confidence, 'reason' => 'Dựa trên lịch sử mượn']
            );
        }

        return response()->json(['recommendations' => $recommendations]);
    }

    // Tìm kiếm thông minh với gợi ý
    public function smartSearch(Request $request)
    {
        $query = $request->input('q');
        
        // Tìm kiếm cơ bản
        $books = Book::where('title', 'like', "%$query%")
                    ->orWhere('author', 'like', "%$query%")
                    ->orWhere('description', 'like', "%$query%")
                    ->get();

        // Nếu tìm thấy ít hơn 3 kết quả, thêm các gợi ý từ các danh mục liên quan
        if ($books->count() < 3) {
            $relatedCategories = \App\Models\Category::where('name', 'like', "%$query%")->get();
            $suggestedBooks = Book::whereIn('category_id', $relatedCategories->pluck('id'))->take(5)->get();
            $books = $books->merge($suggestedBooks)->unique();
        }

        return response()->json(['results' => $books]);
    }

    // Phân tích xu hướng đọc
    public function analyzeReadingTrends()
    {
        $trends = Borrow::selectRaw('book_id, COUNT(*) as borrow_count')
                       ->where('status', 'returned')
                       ->groupBy('book_id')
                       ->with('book')
                       ->orderBy('borrow_count', 'desc')
                       ->limit(10)
                       ->get();

        return response()->json(['trends' => $trends]);
    }

    // Phân loại sách dựa trên mô tả
    public function categorizeBook(Request $request)
    {
        $description = $request->input('description');
        
        // Phân tích đơn giản dựa trên từ khóa
        $keywords = [
            'tiểu thuyết' => ['tiểu thuyết', 'cô gái', 'chàng trai', 'tình yêu', 'cuộc sống'],
            'khoa học' => ['khoa học', 'thí nghiệm', 'con người', 'vũ trụ', 'công nghệ'],
            'lịch sử' => ['lịch sử', 'chiến tranh', 'quá khứ', 'vương quốc', 'thế kỷ'],
            'tự truyện' => ['tự truyện', 'cuộc đời', 'kỷ niệm', 'trải nghiệm', 'hành trình'],
        ];

        $scores = [];
        $descLower = strtolower($description);

        foreach ($keywords as $category => $words) {
            $score = 0;
            foreach ($words as $word) {
                if (strpos($descLower, $word) !== false) {
                    $score += 20;
                }
            }
            $scores[$category] = $score;
        }

        $suggestedCategory = array_key_first(array_filter($scores, fn($s) => $s > 0));
        $suggestedCategory = $suggestedCategory ?? 'Khác';

        return response()->json(['category' => $suggestedCategory, 'scores' => $scores]);
    }
}
