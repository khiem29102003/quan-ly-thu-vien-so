<?php

namespace App\Console\Commands;

use App\Models\Book;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class MigratePublicEbooksToPrivate extends Command
{
    protected $signature = 'ebooks:migrate-public-to-private {--dry-run : Preview changes without writing files}';

    protected $description = 'Move legacy ebook files from public disk to private local disk and update books.file_path';

    public function handle(): int
    {
        $isDryRun = (bool) $this->option('dry-run');

        $books = Book::query()
            ->where('is_digital', true)
            ->whereNotNull('file_path')
            ->get(['id', 'title', 'file_path']);

        if ($books->isEmpty()) {
            $this->info('No digital books with file paths found.');
            return self::SUCCESS;
        }

        $moved = 0;
        $skipped = 0;
        $missing = 0;
        $failed = 0;

        foreach ($books as $book) {
            $currentPath = (string) $book->file_path;
            $alreadyPrivate = Str::startsWith($currentPath, 'private-books/');

            if ($alreadyPrivate && Storage::disk('local')->exists($currentPath)) {
                $skipped++;
                continue;
            }

            $sourceDisk = null;
            if (Storage::disk('public')->exists($currentPath)) {
                $sourceDisk = 'public';
            } elseif (Storage::disk('local')->exists($currentPath)) {
                $sourceDisk = 'local';
            }

            if ($sourceDisk === null) {
                $missing++;
                $this->warn("[missing] book_id={$book->id} path={$currentPath}");
                continue;
            }

            $extension = pathinfo($currentPath, PATHINFO_EXTENSION) ?: 'pdf';
            $targetPath = 'private-books/' . Str::uuid() . '.' . strtolower($extension);

            if ($isDryRun) {
                $this->line("[dry-run] book_id={$book->id} {$sourceDisk}:{$currentPath} -> local:{$targetPath}");
                $moved++;
                continue;
            }

            try {
                $stream = Storage::disk($sourceDisk)->readStream($currentPath);
                if ($stream === false) {
                    $failed++;
                    $this->error("[failed-read] book_id={$book->id} path={$currentPath}");
                    continue;
                }

                $writeOk = Storage::disk('local')->writeStream($targetPath, $stream);
                if (is_resource($stream)) {
                    fclose($stream);
                }

                if (!$writeOk) {
                    $failed++;
                    $this->error("[failed-write] book_id={$book->id} target={$targetPath}");
                    continue;
                }

                $book->update(['file_path' => $targetPath]);

                if ($sourceDisk === 'public') {
                    Storage::disk('public')->delete($currentPath);
                }

                $moved++;
                $this->info("[moved] book_id={$book->id} -> {$targetPath}");
            } catch (\Throwable $e) {
                $failed++;
                $this->error("[exception] book_id={$book->id} {$e->getMessage()}");
            }
        }

        $this->newLine();
        $this->info('Migration summary');
        $this->line("moved: {$moved}");
        $this->line("skipped: {$skipped}");
        $this->line("missing: {$missing}");
        $this->line("failed: {$failed}");

        return $failed > 0 ? self::FAILURE : self::SUCCESS;
    }
}
