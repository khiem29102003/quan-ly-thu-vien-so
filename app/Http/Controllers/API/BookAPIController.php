<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Book;
use Illuminate\Http\Request;

class BookAPIController extends Controller
{
    /**
     * GET /api/books - Lấy danh sách tất cả sách
     */
    public function index()
    {
        $books = Book::with('category')->paginate(20);
        
        return response()->json([
            'success' => true,
            'data' => $books
        ]);
    }

    /**
     * GET /api/books/{id} - Lấy chi tiết một sách
     */
    public function show($id)
    {
        $book = Book::with('category')->find($id);
        
        if (!$book) {
            return response()->json([
                'success' => false,
                'message' => 'Book not found'
            ], 404);
        }
        
        return response()->json([
            'success' => true,
            'data' => $book
        ]);
    }

    /**
     * POST /api/books - Tạo sách mới
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'isbn' => 'nullable|string|unique:books',
            'category_id' => 'required|exists:categories,id',
            'total_copies' => 'required|integer|min:0',
            'publisher' => 'nullable|string',
            'publication_year' => 'nullable|integer',
        ]);

        $validated['available_copies'] = $validated['total_copies'];
        
        $book = Book::create($validated);
        
        return response()->json([
            'success' => true,
            'message' => 'Book created successfully',
            'data' => $book
        ], 201);
    }

    /**
     * PUT /api/books/{id} - Cập nhật sách
     */
    public function update(Request $request, $id)
    {
        $book = Book::find($id);
        
        if (!$book) {
            return response()->json([
                'success' => false,
                'message' => 'Book not found'
            ], 404);
        }

        $validated = $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'author' => 'sometimes|required|string|max:255',
            'isbn' => 'nullable|string|unique:books,isbn,' . $id,
            'category_id' => 'sometimes|required|exists:categories,id',
            'total_copies' => 'sometimes|required|integer|min:0',
            'publisher' => 'nullable|string',
            'publication_year' => 'nullable|integer',
        ]);

        $book->update($validated);
        
        return response()->json([
            'success' => true,
            'message' => 'Book updated successfully',
            'data' => $book
        ]);
    }

    /**
     * DELETE /api/books/{id} - Xóa sách
     */
    public function destroy($id)
    {
        $book = Book::find($id);
        
        if (!$book) {
            return response()->json([
                'success' => false,
                'message' => 'Book not found'
            ], 404);
        }

        $book->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'Book deleted successfully'
        ]);
    }

    /**
     * GET /api/books/search?q=keyword - Tìm kiếm sách
     */
    public function search(Request $request)
    {
        $keyword = $request->get('q', '');
        
        $books = Book::where('title', 'like', "%{$keyword}%")
            ->orWhere('author', 'like', "%{$keyword}%")
            ->orWhere('isbn', 'like', "%{$keyword}%")
            ->with('category')
            ->paginate(20);
        
        return response()->json([
            'success' => true,
            'data' => $books
        ]);
    }
}
