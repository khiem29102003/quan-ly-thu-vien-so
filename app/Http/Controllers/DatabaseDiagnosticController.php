<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class DatabaseDiagnosticController extends Controller
{
    /**
     * Kiểm tra cấu hình database
     */
    public function checkConfig()
    {
        return response()->json([
            'driver' => config('database.default'),
            'connection' => config('database.default'),
            'host' => config('database.connections.mysql.host'),
            'port' => config('database.connections.mysql.port'),
            'database' => config('database.connections.mysql.database'),
            'username' => config('database.connections.mysql.username'),
        ]);
    }

    /**
     * Test kết nối database
     */
    public function testConnection()
    {
        try {
            $startTime = microtime(true);
            
            // Thử kết nối
            DB::connection()->getPdo();
            
            $endTime = microtime(true);
            $responseTime = round(($endTime - $startTime) * 1000, 2);
            
            // Lấy thông tin server
            $serverVersion = DB::select('SELECT VERSION() as version')[0]->version ?? 'Unknown';
            $databaseName = DB::select('SELECT DATABASE() as db')[0]->db ?? 'Unknown';
            
            return response()->json([
                'success' => true,
                'status' => 'Connected',
                'database' => $databaseName,
                'driver' => config('database.default'),
                'response_time' => $responseTime,
                'server_version' => $serverVersion,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'status' => 'Disconnected',
                'error' => $e->getMessage(),
                'details' => 'Kiểm tra cấu hình .env: DB_HOST, DB_PORT, DB_DATABASE, DB_USERNAME, DB_PASSWORD',
            ], 500);
        }
    }

    /**
     * Kiểm tra thống kê dữ liệu
     */
    public function dataStats()
    {
        try {
            $tables = [
                'users',
                'books',
                'categories',
                'borrows',
                'recommendations',
                'activity_logs',
                'book_reservations',
            ];

            $stats = [];
            foreach ($tables as $table) {
                try {
                    $stats[$table] = DB::table($table)->count();
                } catch (\Exception $e) {
                    $stats[$table] = 0;
                }
            }

            // Lấy kích thước database
            $dbSize = DB::select("
                SELECT 
                    ROUND(SUM(data_length + index_length) / 1024 / 1024, 2) AS size_mb
                FROM information_schema.tables 
                WHERE table_schema = ?
            ", [config('database.connections.mysql.database')])[0]->size_mb ?? 0;

            return response()->json([
                'tables' => $stats,
                'database_size' => $dbSize . ' MB',
                'checked_at' => now()->format('Y-m-d H:i:s'),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Kiểm tra migrations
     */
    public function checkMigrations()
    {
        try {
            $migrations = DB::table('migrations')->get();
            
            return response()->json([
                'total' => $migrations->count(),
                'ran' => $migrations->count(),
                'pending' => 0,
                'migrations' => $migrations->toArray(),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Bảng migrations không tồn tại. Chạy: php artisan migrate',
                'details' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Chẩn đoán hoàn chỉnh
     */
    public function diagnostics()
    {
        try {
            // Kiểm tra kết nối
            DB::connection()->getPdo();
            $connection_ok = true;
        } catch (\Exception $e) {
            $connection_ok = false;
        }

        try {
            // Kiểm tra migrations
            $migrationCount = DB::table('migrations')->count();
            $migrations_ok = $migrationCount > 0;
        } catch (\Exception $e) {
            $migrationCount = 0;
            $migrations_ok = false;
        }

        try {
            // Kiểm tra dữ liệu
            $booksCount = DB::table('books')->count();
            $usersCount = DB::table('users')->count();
            $borrowsCount = DB::table('borrows')->count();
            $has_data = ($booksCount > 0 || $usersCount > 0 || $borrowsCount > 0);
            $total_records = $booksCount + $usersCount + $borrowsCount;
        } catch (\Exception $e) {
            $has_data = false;
            $total_records = 0;
        }

        try {
            // Đếm bảng
            $tables = DB::select("
                SELECT TABLE_NAME 
                FROM information_schema.TABLES 
                WHERE TABLE_SCHEMA = ?
            ", [config('database.connections.mysql.database')]);
            $tables_count = count($tables);
        } catch (\Exception $e) {
            $tables_count = 0;
        }

        // Xác định trạng thái chung
        if ($connection_ok && $migrations_ok && $has_data) {
            $overall_status = 'HEALTHY';
        } elseif ($connection_ok && $migrations_ok) {
            $overall_status = 'WARNING';
        } else {
            $overall_status = 'ERROR';
        }

        return response()->json([
            'overall_status' => $overall_status,
            'connection_ok' => $connection_ok,
            'migrations_ok' => $migrations_ok,
            'has_data' => $has_data,
            'tables_count' => $tables_count,
            'total_records' => $total_records,
            'books' => $booksCount ?? 0,
            'users' => $usersCount ?? 0,
            'borrows' => $borrowsCount ?? 0,
        ]);
    }
}
