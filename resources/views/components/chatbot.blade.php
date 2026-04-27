<!-- AI Chatbot Component -->
<style>
    /* Chatbot Styles */
    .chat-widget {
        position: fixed;
        bottom: 20px;
        right: 20px;
        z-index: 1000;
        font-family: 'Inter', sans-serif;
    }

    .chat-button {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        background: linear-gradient(135deg, #06b6d4 0%, #0891b2 100%);
        color: white;
        border: none;
        cursor: pointer;
        box-shadow: 0 4px 12px rgba(6, 182, 212, 0.4);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        transition: all 0.3s ease;
        animation: pulse 2s infinite;
    }

    .chat-button:hover {
        transform: scale(1.1);
        box-shadow: 0 6px 16px rgba(6, 182, 212, 0.6);
    }

    @keyframes pulse {
        0%, 100% { transform: scale(1); }
        50% { transform: scale(1.05); }
    }

    .chat-container {
        position: fixed;
        bottom: 90px;
        right: 20px;
        width: 380px;
        height: 550px;
        background: white;
        border-radius: 16px;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
        display: none;
        flex-direction: column;
        overflow: hidden;
        animation: slideUp 0.3s ease;
    }

    .chat-container.active {
        display: flex;
    }

    @keyframes slideUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .chat-header {
        background: linear-gradient(135deg, #06b6d4 0%, #0891b2 100%);
        color: white;
        padding: 20px;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .chat-header h3 {
        font-size: 18px;
        font-weight: 600;
        margin: 0;
    }

    .chat-header small {
        display: block;
        opacity: 0.9;
        font-size: 12px;
        margin-top: 2px;
    }

    .chat-close {
        background: rgba(255, 255, 255, 0.2);
        border: none;
        color: white;
        width: 32px;
        height: 32px;
        border-radius: 50%;
        cursor: pointer;
        font-size: 18px;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: background 0.2s;
    }

    .chat-close:hover {
        background: rgba(255, 255, 255, 0.3);
    }

    .chat-messages {
        flex: 1;
        overflow-y: auto;
        padding: 20px;
        background: #f9fafb;
    }

    .chat-message {
        margin-bottom: 16px;
        animation: fadeIn 0.3s ease;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .message-bot {
        display: flex;
        gap: 10px;
        align-items: flex-start;
    }

    .message-user {
        display: flex;
        gap: 10px;
        align-items: flex-start;
        flex-direction: row-reverse;
    }

    .message-avatar {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
        flex-shrink: 0;
    }

    .message-bot .message-avatar {
        background: linear-gradient(135deg, #06b6d4 0%, #0891b2 100%);
        color: white;
    }

    .message-user .message-avatar {
        background: #e5e7eb;
        color: #4b5563;
    }

    .message-content {
        max-width: 75%;
    }

    .message-bubble {
        padding: 12px 16px;
        border-radius: 12px;
        font-size: 14px;
        line-height: 1.5;
        white-space: pre-wrap;
        word-wrap: break-word;
    }

    .message-bot .message-bubble {
        background: white;
        color: #1f2937;
        border: 1px solid #e5e7eb;
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
    }

    .message-user .message-bubble {
        background: linear-gradient(135deg, #06b6d4 0%, #0891b2 100%);
        color: white;
    }

    .message-time {
        font-size: 11px;
        color: #9ca3af;
        margin-top: 4px;
        padding: 0 4px;
    }

    .message-suggestions {
        display: flex;
        flex-wrap: wrap;
        gap: 6px;
        margin-top: 8px;
    }

    .suggestion-btn {
        background: white;
        border: 1px solid #06b6d4;
        color: #06b6d4;
        padding: 6px 12px;
        border-radius: 16px;
        font-size: 12px;
        cursor: pointer;
        transition: all 0.2s;
    }

    .suggestion-btn:hover {
        background: #06b6d4;
        color: white;
        transform: translateY(-1px);
    }

    .typing-indicator {
        display: none;
        align-items: center;
        gap: 10px;
        padding: 12px 16px;
        background: white;
        border-radius: 12px;
        width: fit-content;
        border: 1px solid #e5e7eb;
    }

    .typing-indicator.active {
        display: flex;
    }

    .typing-dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background: #06b6d4;
        animation: typing 1.4s infinite;
    }

    .typing-dot:nth-child(2) {
        animation-delay: 0.2s;
    }

    .typing-dot:nth-child(3) {
        animation-delay: 0.4s;
    }

    @keyframes typing {
        0%, 60%, 100% {
            opacity: 0.3;
            transform: translateY(0);
        }
        30% {
            opacity: 1;
            transform: translateY(-8px);
        }
    }

    .chat-input-container {
        padding: 16px;
        background: white;
        border-top: 1px solid #e5e7eb;
    }

    .chat-input-wrapper {
        display: flex;
        gap: 8px;
        align-items: flex-end;
    }

    .chat-input {
        flex: 1;
        border: 1px solid #d1d5db;
        border-radius: 12px;
        padding: 10px 14px;
        font-size: 14px;
        resize: none;
        font-family: 'Inter', sans-serif;
        max-height: 100px;
        transition: border-color 0.2s;
    }

    .chat-input:focus {
        outline: none;
        border-color: #06b6d4;
        box-shadow: 0 0 0 3px rgba(6, 182, 212, 0.1);
    }

    .chat-send-btn {
        background: linear-gradient(135deg, #06b6d4 0%, #0891b2 100%);
        color: white;
        border: none;
        width: 40px;
        height: 40px;
        border-radius: 50%;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 16px;
        transition: all 0.2s;
        flex-shrink: 0;
    }

    .chat-send-btn:hover:not(:disabled) {
        transform: scale(1.1);
        box-shadow: 0 4px 12px rgba(6, 182, 212, 0.4);
    }

    .chat-send-btn:disabled {
        opacity: 0.5;
        cursor: not-allowed;
    }

    /* Scrollbar styling */
    .chat-messages::-webkit-scrollbar {
        width: 6px;
    }

    .chat-messages::-webkit-scrollbar-track {
        background: #f3f4f6;
    }

    .chat-messages::-webkit-scrollbar-thumb {
        background: #d1d5db;
        border-radius: 3px;
    }

    .chat-messages::-webkit-scrollbar-thumb:hover {
        background: #9ca3af;
    }

    /* Mobile responsive */
    @media (max-width: 480px) {
        .chat-container {
            width: calc(100vw - 20px);
            right: 10px;
            height: calc(100vh - 100px);
            bottom: 80px;
        }

        .chat-widget {
            right: 10px;
        }
    }
</style>

<!-- Chat Widget HTML -->
<div class="chat-widget">
    <button class="chat-button" id="chatToggle" title="Mở chat AI">
        <i class="fas fa-robot"></i>
    </button>

    <div class="chat-container" id="chatContainer">
        <div class="chat-header">
            <div>
                <h3><i class="fas fa-robot"></i> Trợ Lý AI</h3>
                <small>Hỏi tôi về sách, mượn trả...</small>
            </div>
            <button class="chat-close" id="chatClose">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <div class="chat-messages" id="chatMessages">
            <!-- Initial greeting message -->
            <div class="chat-message message-bot">
                <div class="message-avatar">
                    <i class="fas fa-robot"></i>
                </div>
                <div class="message-content">
                    <div class="message-bubble">
                        👋 Xin chào! Tôi là trợ lý AI của thư viện. Tôi có thể giúp bạn tìm sách, gợi ý đọc, và trả lời mọi thắc mắc về thư viện!
                    </div>
                    <div class="message-suggestions">
                        <button class="suggestion-btn" data-message="Gợi ý sách cho tôi">💡 Gợi ý sách</button>
                        <button class="suggestion-btn" data-message="Tìm sách">🔍 Tìm sách</button>
                        <button class="suggestion-btn" data-message="Hướng dẫn mượn sách">📖 Hướng dẫn</button>
                        <button class="suggestion-btn" data-message="Sách phổ biến">🔥 Sách hot</button>
                    </div>
                </div>
            </div>

            <!-- Typing indicator -->
            <div class="chat-message message-bot">
                <div class="message-avatar">
                    <i class="fas fa-robot"></i>
                </div>
                <div class="typing-indicator" id="typingIndicator">
                    <div class="typing-dot"></div>
                    <div class="typing-dot"></div>
                    <div class="typing-dot"></div>
                </div>
            </div>
        </div>

        <div class="chat-input-container">
            <div class="chat-input-wrapper">
                <textarea 
                    class="chat-input" 
                    id="chatInput" 
                    placeholder="Nhập tin nhắn..."
                    rows="1"
                ></textarea>
                <button class="chat-send-btn" id="chatSendBtn" title="Gửi">
                    <i class="fas fa-paper-plane"></i>
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Chat JavaScript -->
<script>
(function() {
    // Elements
    const chatToggle = document.getElementById('chatToggle');
    const chatClose = document.getElementById('chatClose');
    const chatContainer = document.getElementById('chatContainer');
    const chatMessages = document.getElementById('chatMessages');
    const chatInput = document.getElementById('chatInput');
    const chatSendBtn = document.getElementById('chatSendBtn');
    const typingIndicator = document.getElementById('typingIndicator');

    // State
    let isOpen = false;

    // Toggle chat
    function toggleChat() {
        isOpen = !isOpen;
        chatContainer.classList.toggle('active');
        if (isOpen) {
            chatInput.focus();
            scrollToBottom();
        }
    }

    chatToggle.addEventListener('click', toggleChat);
    chatClose.addEventListener('click', toggleChat);

    // Auto-resize textarea
    chatInput.addEventListener('input', function() {
        this.style.height = 'auto';
        this.style.height = Math.min(this.scrollHeight, 100) + 'px';
    });

    // Send message on Enter (Shift+Enter for new line)
    chatInput.addEventListener('keydown', function(e) {
        if (e.key === 'Enter' && !e.shiftKey) {
            e.preventDefault();
            sendMessage();
        }
    });

    chatSendBtn.addEventListener('click', sendMessage);

    // Handle suggestion buttons
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('suggestion-btn')) {
            const message = e.target.getAttribute('data-message');
            sendMessage(message);
        }
    });

    // Send message function
    async function sendMessage(predefinedMessage = null) {
        const message = predefinedMessage || chatInput.value.trim();
        
        if (!message) return;

        // Clear input
        if (!predefinedMessage) {
            chatInput.value = '';
            chatInput.style.height = 'auto';
        }

        // Disable send button
        chatSendBtn.disabled = true;

        // Add user message to chat
        addMessage(message, 'user');

        // Show typing indicator
        typingIndicator.classList.add('active');
        scrollToBottom();

        try {
            // Get current user ID if logged in
            const userId = document.querySelector('meta[name="user-id"]')?.content || null;

            // Send to API
            const response = await fetch('/api/chat/message', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                },
                body: JSON.stringify({
                    message: message,
                    user_id: userId
                })
            });

            // Hide typing indicator
            typingIndicator.classList.remove('active');

            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }

            const data = await response.json();

            if (data.success) {
                // Add bot response
                addMessage(data.response, 'bot', data.suggestions);
            } else {
                addMessage('Xin lỗi, tôi gặp sự cố. Vui lòng thử lại sau.', 'bot');
                console.error('API Error:', data);
            }
        } catch (error) {
            console.error('Chat error:', error);
            typingIndicator.classList.remove('active');
            addMessage('❌ Không thể kết nối đến server. Vui lòng kiểm tra kết nối mạng và thử lại.', 'bot');
        } finally {
            // Re-enable send button
            chatSendBtn.disabled = false;
            chatInput.focus();
        }
    }

    // Add message to chat
    function addMessage(text, sender, suggestions = null) {
        const messageDiv = document.createElement('div');
        messageDiv.className = `chat-message message-${sender}`;

        const avatarDiv = document.createElement('div');
        avatarDiv.className = 'message-avatar';
        avatarDiv.innerHTML = sender === 'bot' 
            ? '<i class="fas fa-robot"></i>' 
            : '<i class="fas fa-user"></i>';

        const contentDiv = document.createElement('div');
        contentDiv.className = 'message-content';

        const bubbleDiv = document.createElement('div');
        bubbleDiv.className = 'message-bubble';
        bubbleDiv.textContent = text;

        const timeDiv = document.createElement('div');
        timeDiv.className = 'message-time';
        timeDiv.textContent = new Date().toLocaleTimeString('vi-VN', { 
            hour: '2-digit', 
            minute: '2-digit' 
        });

        contentDiv.appendChild(bubbleDiv);
        contentDiv.appendChild(timeDiv);

        // Add suggestions if available
        if (suggestions && suggestions.length > 0) {
            const suggestionsDiv = document.createElement('div');
            suggestionsDiv.className = 'message-suggestions';
            
            suggestions.forEach(suggestion => {
                const btn = document.createElement('button');
                btn.className = 'suggestion-btn';
                btn.setAttribute('data-message', suggestion);
                btn.textContent = suggestion;
                suggestionsDiv.appendChild(btn);
            });

            contentDiv.appendChild(suggestionsDiv);
        }

        messageDiv.appendChild(avatarDiv);
        messageDiv.appendChild(contentDiv);

        // Add message to chat (append before typing indicator wrapper)
        const typingWrapper = typingIndicator.closest('.chat-message');
        if (typingWrapper && typingWrapper.parentElement === chatMessages) {
            chatMessages.insertBefore(messageDiv, typingWrapper);
        } else {
            // Fallback: just append to end
            chatMessages.appendChild(messageDiv);
        }

        scrollToBottom();
    }

    // Scroll to bottom
    function scrollToBottom() {
        setTimeout(() => {
            chatMessages.scrollTop = chatMessages.scrollHeight;
        }, 100);
    }
})();
</script>
