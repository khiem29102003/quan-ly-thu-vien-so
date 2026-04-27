<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Category;
use App\Models\Borrow;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;

class BookController extends Controller
{
    public function index(Request $request)
    {
        $query = Book::with('category');
        
        // Apply filters
        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }
        
        if ($request->filled('language')) {
            $query->where('language', $request->language);
        }
        
        if ($request->filled('rating')) {
            $query->where('rating', '>=', $request->rating);
        }
        
        if ($request->filled('availability')) {
            if ($request->availability === 'available') {
                $query->where('available_copies', '>', 0);
            } elseif ($request->availability === 'unavailable') {
                $query->where('available_copies', 0);
            }
        }
        
        if ($request->filled('year_from')) {
            $query->where('publication_year', '>=', $request->year_from);
        }
        
        if ($request->filled('year_to')) {
            $query->where('publication_year', '<=', $request->year_to);
        }
        
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%$search%")
                  ->orWhere('author', 'like', "%$search%")
                  ->orWhere('isbn', 'like', "%$search%");
            });
        }
        
        $books = $query->paginate(12)->appends($request->except('page'));
        $categories = Category::withCount('books')->having('books_count', '>', 0)->get();
        $languages = Book::distinct()->pluck('language');
        
        return view('books.index', compact('books', 'categories', 'languages'));
    }

    public function show($id)
    {
        $book = Book::with('category')->findOrFail($id);
        return view('books.show', compact('book'));
    }

    public function search(Request $request)
    {
        $query = $request->input('q');
        $books = Book::where('title', 'like', "%$query%")
                    ->orWhere('author', 'like', "%$query%")
                    ->orWhere('isbn', 'like', "%$query%")
                    ->paginate(12);
        
        return view('books.index', compact('books', 'query'));
    }

    public function filterByCategory($categoryId)
    {
        $category = Category::findOrFail($categoryId);
        $books = Book::where('category_id', $categoryId)->paginate(12);
        return view('books.index', compact('books', 'category'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('books.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $bookColumns = $this->getBookTableColumns();

        $validated = $request->validate([
            'title' => 'required|string',
            'author' => 'required|string',
            'isbn' => 'required|unique:books',
            'category_id' => 'required|exists:categories,id',
            'total_copies' => 'required|integer|min:1',
            'publisher' => 'nullable|string',
            'publication_year' => 'nullable|integer',
            'description' => 'nullable|string',
            'language' => 'nullable|string',
            'rating' => 'nullable|integer|min:0|max:5',
            'cover_image' => 'nullable|image|max:2048',
            'source_type' => 'required_if:is_digital,1|in:purchase,donation,license,open-access,internal',
            'source_name' => 'nullable|string|max:255',
            'source_url' => 'nullable|url|max:1000',
            'borrow_fee' => 'nullable|integer|min:0',
            'daily_late_fee' => 'nullable|integer|min:0',
            'is_digital' => 'nullable|boolean',
            'digital_file' => 'required_if:is_digital,1|file|mimes:pdf|mimetypes:application/pdf,application/x-pdf|max:20480',
        ]);

        if ($request->hasFile('cover_image')) {
            $validated['cover_image'] = $request->file('cover_image')->store('book-covers', 'public');
        }

        if ($request->hasFile('digital_file')) {
            if (!in_array('file_path', $bookColumns, true)) {
                return back()->withInput()->with('error', 'Cơ sở dữ liệu chưa cập nhật cho sách số. Vui lòng chạy migrate.');
            }

            $validated['file_path'] = $request->file('digital_file')->store('private-books', 'local');
            $validated['is_digital'] = true;
        }

        $validated['language'] = $validated['language'] ?? 'Tiếng Việt';
        $validated['rating'] = $validated['rating'] ?? 0;
        $validated['source_type'] = $validated['source_type'] ?? 'purchase';
        $validated['borrow_fee'] = $validated['borrow_fee'] ?? 0;
        $validated['daily_late_fee'] = $validated['daily_late_fee'] ?? 5000;
        $validated['is_digital'] = (bool) ($validated['is_digital'] ?? false);
        $validated['available_copies'] = $validated['total_copies'];

        unset($validated['digital_file']);

        $validated = $this->filterByExistingBookColumns($validated, $bookColumns);

        Book::create($validated);
        return redirect()->route('books.index')->with('success', 'Thêm sách thành công!');
    }

    public function edit($id)
    {
        $book = Book::findOrFail($id);
        $categories = Category::all();
        return view('books.edit', compact('book', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $book = Book::findOrFail($id);
        $bookColumns = $this->getBookTableColumns();

        $isDigitalInput = $request->boolean('is_digital');
        $digitalFileRule = $isDigitalInput && empty($book->file_path)
            ? 'required|file|mimes:pdf|mimetypes:application/pdf,application/x-pdf|max:20480'
            : 'nullable|file|mimes:pdf|mimetypes:application/pdf,application/x-pdf|max:20480';

        $validated = $request->validate([
            'title' => 'required|string',
            'author' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'total_copies' => 'required|integer|min:1',
            'publisher' => 'nullable|string',
            'publication_year' => 'nullable|integer',
            'description' => 'nullable|string',
            'language' => 'nullable|string',
            'rating' => 'nullable|integer|min:0|max:5',
            'cover_image' => 'nullable|image|max:2048',
            'source_type' => 'required_if:is_digital,1|in:purchase,donation,license,open-access,internal',
            'source_name' => 'nullable|string|max:255',
            'source_url' => 'nullable|url|max:1000',
            'borrow_fee' => 'nullable|integer|min:0',
            'daily_late_fee' => 'nullable|integer|min:0',
            'is_digital' => 'nullable|boolean',
            'digital_file' => $digitalFileRule,
        ]);

        if ($request->hasFile('cover_image')) {
            if ($book->cover_image) {
                Storage::disk('public')->delete($book->cover_image);
            }
            $validated['cover_image'] = $request->file('cover_image')->store('book-covers', 'public');
        }

        if ($request->hasFile('digital_file')) {
            if (!in_array('file_path', $bookColumns, true)) {
                return back()->withInput()->with('error', 'Cơ sở dữ liệu chưa cập nhật cho sách số. Vui lòng chạy migrate.');
            }

            $this->deleteDigitalFileIfExists($book->file_path);

            $validated['file_path'] = $request->file('digital_file')->store('private-books', 'local');
            $validated['is_digital'] = true;
        }

        if (!$request->boolean('is_digital') && !$request->hasFile('digital_file')) {
            $this->deleteDigitalFileIfExists($book->file_path);
            $validated['file_path'] = null;
        }

        $validated['language'] = $validated['language'] ?? $book->language;
        $validated['rating'] = $validated['rating'] ?? $book->rating;
        $validated['source_type'] = $validated['source_type'] ?? $book->source_type ?? 'purchase';
        $validated['borrow_fee'] = $validated['borrow_fee'] ?? $book->borrow_fee ?? 0;
        $validated['daily_late_fee'] = $validated['daily_late_fee'] ?? $book->daily_late_fee ?? 5000;
        $validated['is_digital'] = $request->boolean('is_digital');

        unset($validated['digital_file']);

        $validated = $this->filterByExistingBookColumns($validated, $bookColumns);

        $book->update($validated);
        return redirect()->route('books.index')->with('success', 'Cập nhật sách thành công!');
    }

    public function destroy($id)
    {
        $book = Book::findOrFail($id);

        if ($book->cover_image) {
            Storage::disk('public')->delete($book->cover_image);
        }

        $this->deleteDigitalFileIfExists($book->file_path);

        $book->delete();

        return redirect()->route('books.index')->with('success', 'Xóa sách thành công!');
    }

    public function coverImage($id)
    {
        $book = Book::findOrFail($id);

        if (empty($book->cover_image) || !Storage::disk('public')->exists($book->cover_image)) {
            abort(404, 'Khong tim thay anh bia.');
        }

        $path = Storage::disk('public')->path($book->cover_image);

        return response()->file($path, [
            'Cache-Control' => 'public, max-age=86400',
            'X-Content-Type-Options' => 'nosniff',
        ]);
    }

    private function deleteDigitalFileIfExists(?string $filePath): void
    {
        if (empty($filePath)) {
            return;
        }

        // Prefer local/private disk. Keep public fallback for old records before migration.
        if (Storage::disk('local')->exists($filePath)) {
            Storage::disk('local')->delete($filePath);
            return;
        }

        if (Storage::disk('public')->exists($filePath)) {
            Storage::disk('public')->delete($filePath);
        }
    }

    private function getBookTableColumns(): array
    {
        static $columns = null;

        if ($columns === null) {
            $columns = Schema::getColumnListing((new Book())->getTable());
        }

        return $columns;
    }

    private function filterByExistingBookColumns(array $attributes, array $columns): array
    {
        return array_intersect_key($attributes, array_flip($columns));
    }
}
