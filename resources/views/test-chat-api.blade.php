<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Test Chatbot API</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
            background: #f5f5f5;
        }
        .test-box {
            background: white;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 20px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        button {
            background: #06b6d4;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            margin: 5px;
        }
        button:hover {
            background: #0891b2;
        }
        .result {
            background: #f0f9ff;
            padding: 15px;
            border-radius: 5px;
            margin-top: 10px;
            white-space: pre-wrap;
            font-family: monospace;
            font-size: 12px;
        }
        .error {
            background: #fee;
            color: #c00;
        }
        .success {
            background: #efe;
            color: #060;
        }
        input, textarea {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ddd;
            border-radius: 5px;
            box-sizing: border-box;
        }
        h2 {
            color: #06b6d4;
        }
    </style>
</head>
<body>
    <h1>🧪 Test Chatbot API</h1>

    <div class="test-box">
        <h2>Test 1: Kiểm tra CSRF Token</h2>
        <button onclick="testCsrfToken()">Test CSRF Token</button>
        <div id="csrf-result" class="result"></div>
    </div>

    <div class="test-box">
        <h2>Test 2: Kiểm tra API Routes</h2>
        <button onclick="testRoutes()">Test Routes</button>
        <div id="routes-result" class="result"></div>
    </div>

    <div class="test-box">
        <h2>Test 3: Gửi Tin Nhắn Chat</h2>
        <input type="text" id="test-message" placeholder="Nhập tin nhắn test..." value="Xin chào">
        <button onclick="testChatMessage()">Gửi Tin Nhắn</button>
        <div id="chat-result" class="result"></div>
    </div>

    <div class="test-box">
        <h2>Test 4: Lấy Suggestions</h2>
        <button onclick="testSuggestions()">Get Suggestions</button>
        <div id="suggestions-result" class="result"></div>
    </div>

    <div class="test-box">
        <h2>Test 5: Test Nhiều Câu Hỏi</h2>
        <button onclick="testMultiple()">Test Tất Cả</button>
        <div id="multiple-result" class="result"></div>
    </div>

    <script>
        function log(elementId, message, isError = false) {
            const el = document.getElementById(elementId);
            el.textContent = message;
            el.className = 'result ' + (isError ? 'error' : 'success');
        }

        function testCsrfToken() {
            const token = document.querySelector('meta[name="csrf-token"]')?.content;
            if (token) {
                log('csrf-result', `✅ CSRF Token tồn tại:\n${token}`);
            } else {
                log('csrf-result', '❌ CSRF Token KHÔNG tồn tại!', true);
            }
        }

        async function testRoutes() {
            log('routes-result', '🔄 Đang kiểm tra routes...');
            
            try {
                const response = await fetch('/api/chat/suggestions');
                const data = await response.json();
                
                log('routes-result', `✅ Routes hoạt động!\nStatus: ${response.status}\nData: ${JSON.stringify(data, null, 2)}`);
            } catch (error) {
                log('routes-result', `❌ Lỗi:\n${error.message}`, true);
            }
        }

        async function testChatMessage() {
            const message = document.getElementById('test-message').value;
            log('chat-result', '🔄 Đang gửi tin nhắn...');
            
            try {
                const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
                const userId = document.querySelector('meta[name="user-id"]')?.content || null;

                console.log('Sending request with:', {
                    message,
                    userId,
                    csrfToken: csrfToken ? 'exists' : 'missing'
                });

                const response = await fetch('/api/chat/message', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken || '',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        message: message,
                        user_id: userId
                    })
                });

                console.log('Response status:', response.status);
                console.log('Response headers:', [...response.headers.entries()]);

                const contentType = response.headers.get('content-type');
                if (!contentType || !contentType.includes('application/json')) {
                    const text = await response.text();
                    throw new Error(`Server returned non-JSON response:\n${text}`);
                }

                const data = await response.json();
                console.log('Response data:', data);

                if (response.ok && data.success) {
                    log('chat-result', `✅ Thành công!\n\nTrả lời:\n${data.response}\n\nSuggestions: ${JSON.stringify(data.suggestions, null, 2)}`);
                } else {
                    log('chat-result', `⚠️ API trả về lỗi:\n${JSON.stringify(data, null, 2)}`, true);
                }
            } catch (error) {
                console.error('Chat test error:', error);
                log('chat-result', `❌ Lỗi:\n${error.message}\n\nKiểm tra Console (F12) để xem chi tiết.`, true);
            }
        }

        async function testSuggestions() {
            log('suggestions-result', '🔄 Đang lấy suggestions...');
            
            try {
                const response = await fetch('/api/chat/suggestions');
                const data = await response.json();
                
                log('suggestions-result', `✅ Thành công!\n${JSON.stringify(data, null, 2)}`);
            } catch (error) {
                log('suggestions-result', `❌ Lỗi:\n${error.message}`, true);
            }
        }

        async function testMultiple() {
            log('multiple-result', '🔄 Đang test nhiều câu hỏi...\n\n');
            
            const questions = [
                'Xin chào',
                'Tìm sách về lập trình',
                'Gợi ý sách cho tôi',
                'Sách phổ biến',
                'Làm thế nào để mượn sách?'
            ];

            let results = '';
            
            for (const q of questions) {
                try {
                    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
                    const response = await fetch('/api/chat/message', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken || ''
                        },
                        body: JSON.stringify({ message: q })
                    });

                    const data = await response.json();
                    
                    results += `\n📝 Câu hỏi: ${q}\n`;
                    results += `✅ Trả lời: ${data.response?.substring(0, 100)}...\n`;
                    results += `---\n`;
                } catch (error) {
                    results += `\n📝 Câu hỏi: ${q}\n`;
                    results += `❌ Lỗi: ${error.message}\n`;
                    results += `---\n`;
                }
            }
            
            log('multiple-result', results);
        }

        // Auto test on load
        window.addEventListener('load', () => {
            testCsrfToken();
        });
    </script>
</body>
</html>
