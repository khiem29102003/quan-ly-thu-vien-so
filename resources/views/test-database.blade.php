<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>🔍 Kiểm Tra Kết Nối Database</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            max-width: 1000px;
            margin: 0 auto;
            padding: 20px;
            background: #f0f2f5;
        }
        .container {
            background: white;
            border-radius: 8px;
            padding: 30px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        h1 {
            color: #06b6d4;
            border-bottom: 3px solid #06b6d4;
            padding-bottom: 10px;
        }
        .test-section {
            margin: 30px 0;
            padding: 20px;
            background: #f8f9fa;
            border-left: 4px solid #06b6d4;
            border-radius: 4px;
        }
        .test-section h2 {
            margin-top: 0;
            color: #333;
        }
        button {
            background: #06b6d4;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            margin: 5px 5px 5px 0;
            transition: background 0.3s;
        }
        button:hover {
            background: #0891b2;
        }
        button:disabled {
            background: #ccc;
            cursor: not-allowed;
        }
        .result {
            margin-top: 15px;
            padding: 15px;
            border-radius: 5px;
            font-family: 'Courier New', monospace;
            font-size: 13px;
            line-height: 1.6;
            white-space: pre-wrap;
            word-wrap: break-word;
        }
        .result.success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .result.error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        .result.info {
            background: #d1ecf1;
            color: #0c5460;
            border: 1px solid #bee5eb;
        }
        .result.warning {
            background: #fff3cd;
            color: #856404;
            border: 1px solid #ffeeba;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        table th, table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        table th {
            background: #06b6d4;
            color: white;
        }
        table tr:hover {
            background: #f5f5f5;
        }
        .status-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
        }
        .status-badge.connected {
            background: #d4edda;
            color: #155724;
        }
        .status-badge.disconnected {
            background: #f8d7da;
            color: #721c24;
        }
        .status-badge.sample {
            background: #fff3cd;
            color: #856404;
        }
        .loading {
            display: inline-block;
            margin-left: 10px;
        }
        .spinner {
            border: 3px solid #f3f3f3;
            border-top: 3px solid #06b6d4;
            border-radius: 50%;
            width: 20px;
            height: 20px;
            animation: spin 1s linear infinite;
            display: inline-block;
        }
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔍 Kiểm Tra Kết Nối Database</h1>

        <div class="test-section">
            <h2>1️⃣ Kiểm Tra Cấu Hình Database</h2>
            <button onclick="testConfig()">Kiểm Tra Config</button>
            <div id="config-result" class="result" style="display:none;"></div>
        </div>

        <div class="test-section">
            <h2>2️⃣ Kiểm Tra Kết Nối MySQL</h2>
            <button onclick="testConnection()">
                Test Kết Nối 
                <span class="loading" id="conn-loader" style="display:none;">
                    <span class="spinner"></span>
                </span>
            </button>
            <div id="connection-result" class="result" style="display:none;"></div>
        </div>

        <div class="test-section">
            <h2>3️⃣ Kiểm Tra Dữ Liệu</h2>
            <button onclick="testData()">Kiểm Tra Dữ Liệu Database</button>
            <div id="data-result" class="result" style="display:none;"></div>
        </div>

        <div class="test-section">
            <h2>4️⃣ Kiểm Tra Migrations</h2>
            <button onclick="testMigrations()">Kiểm Tra Migrations</button>
            <div id="migrations-result" class="result" style="display:none;"></div>
        </div>

        <div class="test-section">
            <h2>5️⃣ Chẩn Đoán Hoàn Chỉnh</h2>
            <button onclick="runFullDiagnostics()">
                Chạy Chẩn Đoán Đầy Đủ
                <span class="loading" id="diag-loader" style="display:none;">
                    <span class="spinner"></span>
                </span>
            </button>
            <div id="diagnostics-result" class="result" style="display:none;"></div>
        </div>
    </div>

    <script>
        function showResult(elementId, content, type = 'info') {
            const el = document.getElementById(elementId);
            el.textContent = content;
            el.className = 'result ' + type;
            el.style.display = 'block';
        }

        async function testConfig() {
            showResult('config-result', '🔄 Đang kiểm tra...', 'info');
            try {
                const response = await fetch('/api/db/config');
                const data = await response.json();
                
                let content = '✅ CẤU HÌNH DATABASE\n\n';
                content += `Database Driver: ${data.driver}\n`;
                content += `Host: ${data.host}\n`;
                content += `Port: ${data.port}\n`;
                content += `Database: ${data.database}\n`;
                content += `Username: ${data.username}\n`;
                content += `Connection Name: ${data.connection}\n\n`;
                content += `⚠️ Lưu ý: Password không hiển thị cho lý do bảo mật`;
                
                showResult('config-result', content, 'success');
            } catch (error) {
                showResult('config-result', `❌ Lỗi: ${error.message}`, 'error');
            }
        }

        async function testConnection() {
            showResult('connection-result', '🔄 Đang kết nối...', 'info');
            document.getElementById('conn-loader').style.display = 'inline';
            
            try {
                const response = await fetch('/api/db/test-connection');
                const data = await response.json();
                
                document.getElementById('conn-loader').style.display = 'none';
                
                if (data.success) {
                    let content = '✅ KẾT NỐI THÀNH CÔNG\n\n';
                    content += `Status: ${data.status}\n`;
                    content += `Database: ${data.database}\n`;
                    content += `Driver: ${data.driver}\n`;
                    content += `Response Time: ${data.response_time}ms\n`;
                    content += `Server Version: ${data.server_version}\n`;
                    
                    showResult('connection-result', content, 'success');
                } else {
                    let content = '❌ KẾT NỐI THẤT BẠI\n\n';
                    content += `Lỗi: ${data.error}\n`;
                    content += `Chi tiết: ${data.details || 'N/A'}`;
                    
                    showResult('connection-result', content, 'error');
                }
            } catch (error) {
                document.getElementById('conn-loader').style.display = 'none';
                showResult('connection-result', `❌ Lỗi: ${error.message}`, 'error');
            }
        }

        async function testData() {
            showResult('data-result', '🔄 Đang kiểm tra...', 'info');
            try {
                const response = await fetch('/api/db/data-stats');
                const data = await response.json();
                
                let content = '📊 THỐNG KÊ DỮ LIỆU\n\n';
                
                for (const [table, count] of Object.entries(data.tables)) {
                    content += `${table}: ${count} records\n`;
                }
                
                content += `\n💾 Dung lượng Database: ${data.database_size}\n`;
                content += `🕐 Ngày kiểm tra: ${data.checked_at}`;
                
                showResult('data-result', content, data.tables.books > 0 ? 'success' : 'warning');
            } catch (error) {
                showResult('data-result', `❌ Lỗi: ${error.message}`, 'error');
            }
        }

        async function testMigrations() {
            showResult('migrations-result', '🔄 Đang kiểm tra...', 'info');
            try {
                const response = await fetch('/api/db/migrations');
                const data = await response.json();
                
                let content = '🗂️ LỊCH SỬ MIGRATIONS\n\n';
                
                if (data.migrations && data.migrations.length > 0) {
                    content += `Tổng migrations: ${data.total}\n`;
                    content += `Đã chạy: ${data.ran}\n`;
                    content += `Chưa chạy: ${data.pending}\n\n`;
                    
                    content += '✅ Migrations đã chạy:\n';
                    data.migrations.slice(0, 5).forEach(m => {
                        content += `  • ${m.migration}\n`;
                    });
                    if (data.migrations.length > 5) {
                        content += `  ... và ${data.migrations.length - 5} migrations khác\n`;
                    }
                } else {
                    content += '⚠️ Chưa chạy migration nào\n';
                    content += 'Chạy: php artisan migrate';
                }
                
                showResult('migrations-result', content, data.pending === 0 ? 'success' : 'warning');
            } catch (error) {
                showResult('migrations-result', `❌ Lỗi: ${error.message}`, 'error');
            }
        }

        async function runFullDiagnostics() {
            showResult('diagnostics-result', '🔄 Đang chạy chẩn đoán...', 'info');
            document.getElementById('diag-loader').style.display = 'inline';
            
            try {
                const response = await fetch('/api/db/diagnostics');
                const data = await response.json();
                
                document.getElementById('diag-loader').style.display = 'none';
                
                let content = '🔬 CHẨN ĐOÁN HOÀN CHỈNH\n\n';
                
                content += `📍 Status Chung: ${data.overall_status}\n`;
                content += `✅ Kết nối DB: ${data.connection_ok ? 'HOẠT ĐỘNG' : 'LỖI'}\n`;
                content += `✅ Migrations: ${data.migrations_ok ? 'ĐẦY ĐỦ' : 'THIẾU'}\n`;
                content += `✅ Dữ liệu: ${data.has_data ? 'CÓ' : 'KHÔNG'}\n`;
                content += `✅ Bảng: ${data.tables_count} bảng\n`;
                content += `✅ Records: ${data.total_records} records\n\n`;
                
                if (data.overall_status === 'HEALTHY') {
                    content += '🎉 Hệ thống hoạt động hoàn hảo!';
                    showResult('diagnostics-result', content, 'success');
                } else if (data.overall_status === 'WARNING') {
                    content += '⚠️ Có một số cảnh báo cần chú ý';
                    showResult('diagnostics-result', content, 'warning');
                } else {
                    content += '❌ Có lỗi cần xử lý';
                    showResult('diagnostics-result', content, 'error');
                }
            } catch (error) {
                document.getElementById('diag-loader').style.display = 'none';
                showResult('diagnostics-result', `❌ Lỗi: ${error.message}`, 'error');
            }
        }

        // Auto test on load
        window.addEventListener('load', () => {
            console.log('✅ Trang kiểm tra DB đã sẵn sàng');
        });
    </script>
</body>
</html>
