<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>cat-AI - ur feline assistant</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/lucide@0.294.0/dist/umd/lucide.css" rel="stylesheet">
    <link href="/assets/css/cat-ai-bootstrap.css" rel="stylesheet">
    <style>
        :root {
            --cat-orange: #f97316;
            --cat-orange-dark: #ea580c;
        }
        
        .dark-mode {
            background-color: #0f172a !important;
            color: #f1f5f9 !important;
        }
        
        .dark-mode .bg-light {
            background-color: #1e293b !important;
            color: #f1f5f9 !important;
        }
        
        .dark-mode .border {
            border-color: #374151 !important;
        }
        
        .cat-avatar {
            background-color: var(--cat-orange);
            border-radius: 50%;
            width: 32px;
            height: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
        }
        
        .chat-message {
            margin-bottom: 1rem;
        }
        
        .user-message {
            margin-left: 2rem;
        }
        
        .ai-message {
            margin-right: 2rem;
        }
        
        .typing-indicator {
            display: none;
        }
        
        .typing-dots {
            display: flex;
            gap: 4px;
        }
        
        .typing-dot {
            width: 8px;
            height: 8px;
            background-color: #6b7280;
            border-radius: 50%;
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
                transform: translateY(0);
            }
            30% {
                transform: translateY(-10px);
            }
        }
        
        .sidebar {
            height: 100vh;
            width: 280px;
            background-color: #f8f9fa;
            border-right: 1px solid #dee2e6;
            transition: transform 0.3s ease;
        }
        
        .sidebar.collapsed {
            transform: translateX(-100%);
        }
        
        @media (max-width: 768px) {
            .sidebar {
                position: fixed;
                top: 0;
                left: 0;
                z-index: 1050;
            }
        }
        
        .main-content {
            margin-left: 280px;
            height: 100vh;
            transition: margin-left 0.3s ease;
        }
        
        .main-content.expanded {
            margin-left: 0;
        }
        
        .chat-container {
            height: calc(100vh - 60px);
            overflow-y: auto;
        }
        
        .welcome-screen {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: calc(100vh - 200px);
            text-align: center;
            padding: 2rem;
        }
        
        .welcome-buttons {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1rem;
            width: 100%;
            max-width: 600px;
            margin-top: 2rem;
        }
        
        .input-section {
            position: sticky;
            bottom: 0;
            background: white;
            border-top: 1px solid #dee2e6;
            padding: 1rem;
        }
        
        .dark-mode .input-section {
            background: #1e293b;
            border-top-color: #374151;
        }
    </style>
</head>
<body>
    <div id="app">
        <!-- Sidebar -->
        <div id="sidebar" class="sidebar">
            <div class="p-3 border-bottom">
                <div class="d-flex align-items-center gap-2 mb-3">
                    <div class="cat-avatar">
                        <i data-lucide="cat" class="w-4 h-4"></i>
                    </div>
                    <span class="fw-bold">cat-AI</span>
                </div>
                <button class="btn btn-outline-primary w-100" onclick="newChat()">
                    <i data-lucide="plus" class="me-2"></i>new chat
                </button>
            </div>
            
            <div class="p-3 flex-grow-1">
                <div class="text-muted small mb-2">recent chats</div>
                <div id="chatHistory">
                    <div class="p-2 rounded bg-light mb-2 cursor-pointer" onclick="selectChat('1')">
                        <div class="small fw-medium">general chat</div>
                        <div class="text-muted small">meow (I'm cat-AI...)</div>
                    </div>
                </div>
            </div>
            
            <div class="p-3 border-top">
                <div class="d-flex align-items-center gap-2 mb-2">
                    <div class="cat-avatar" style="width: 24px; height: 24px;">
                        <i data-lucide="cat" style="width: 12px; height: 12px;"></i>
                    </div>
                    <span class="small"><?php echo htmlspecialchars($_SESSION['user']['name']); ?></span>
                </div>
                <div class="d-flex gap-2">
                    <button class="btn btn-sm btn-outline-secondary" onclick="showManagement()">manage...</button>
                    <button class="btn btn-sm btn-outline-secondary" onclick="toggleDarkMode()">
                        <i data-lucide="moon" id="themeIcon"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div id="mainContent" class="main-content">
            <!-- Header -->
            <div class="bg-white border-bottom p-3 d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center gap-3">
                    <button class="btn btn-sm btn-outline-secondary d-md-none" onclick="toggleSidebar()">
                        <i data-lucide="menu"></i>
                    </button>
                    <button class="btn btn-sm btn-outline-secondary d-none d-md-block" onclick="toggleSidebar()">
                        <i data-lucide="sidebar"></i>
                    </button>
                    <h6 class="mb-0">cat-AI</h6>
                </div>
                <div class="text-muted small">using Groq API</div>
            </div>

            <!-- Chat Area -->
            <div id="chatContainer" class="chat-container">
                <!-- Welcome Screen -->
                <div id="welcomeScreen" class="welcome-screen">
                    <div class="cat-avatar mb-4" style="width: 80px; height: 80px;">
                        <i data-lucide="cat" style="width: 40px; height: 40px;"></i>
                    </div>
                    <h1 class="mb-3">welcome back, <?php echo htmlspecialchars($_SESSION['user']['name']); ?>!</h1>
                    <p class="text-muted mb-4">meow (ur feline AI assistant is ready to perform! ask me anything now!)</p>
                    
                    <?php if ($_SESSION['user']['isGuest']): ?>
                    <div class="alert alert-warning mb-4">
                        <strong>meow</strong> (ur using cat-AI as a guest! some features might be limited. consider creating an account for the full experience!)
                    </div>
                    <?php endif; ?>
                    
                    <div class="welcome-buttons">
                        <button class="btn btn-outline-primary text-start p-3" onclick="sendPresetMessage('what can you help me with?')">
                            <div class="fw-medium">explore paw-sibilities</div>
                            <div class="text-muted small">what can you help meow with?</div>
                        </button>
                        <button class="btn btn-outline-primary text-start p-3" onclick="sendPresetMessage('random cat image')">
                            <div class="fw-medium">random cat image</div>
                            <div class="text-muted small">show me a cute cat pic</div>
                        </button>
                        <button class="btn btn-outline-primary text-start p-3" onclick="sendPresetMessage('tell me a cat story')">
                            <div class="fw-medium">stories thing</div>
                            <div class="text-muted small">tell me a cat story</div>
                        </button>
                        <button class="btn btn-outline-primary text-start p-3" onclick="sendPresetMessage('explain cat behavior')">
                            <div class="fw-medium">feline knowledge</div>
                            <div class="text-muted small">explain cat shenanigans</div>
                        </button>
                    </div>
                </div>

                <!-- Messages -->
                <div id="messages" style="display: none;"></div>
                
                <!-- Typing Indicator -->
                <div id="typingIndicator" class="typing-indicator chat-message">
                    <div class="d-flex gap-3 p-3">
                        <div class="cat-avatar">
                            <i data-lucide="cat" style="width: 16px; height: 16px;"></i>
                        </div>
                        <div>
                            <div class="fw-medium mb-2">cat-AI</div>
                            <div class="typing-dots">
                                <div class="typing-dot"></div>
                                <div class="typing-dot"></div>
                                <div class="typing-dot"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Input Section -->
            <div class="input-section">
                <div class="container-fluid">
                    <div class="input-group">
                        <input type="text" id="messageInput" class="form-control" placeholder="meow me something..." onkeypress="handleKeyPress(event)">
                        <button class="btn btn-primary" onclick="sendMessage()" id="sendButton">
                            <i data-lucide="send"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>
    <script>
        let messages = [];
        let isTyping = false;
        let sidebarOpen = true;
        let currentChatId = '1';
        let isDarkMode = false;

        // Initialize
        document.addEventListener('DOMContentLoaded', function() {
            lucide.createIcons();
            loadChat();
        });

        function toggleSidebar() {
            sidebarOpen = !sidebarOpen;
            const sidebar = document.getElementById('sidebar');
            const mainContent = document.getElementById('mainContent');
            
            if (sidebarOpen) {
                sidebar.classList.remove('collapsed');
                mainContent.classList.remove('expanded');
            } else {
                sidebar.classList.add('collapsed');
                mainContent.classList.add('expanded');
            }
        }

        function toggleDarkMode() {
            isDarkMode = !isDarkMode;
            document.body.classList.toggle('dark-mode', isDarkMode);
            
            const icon = document.getElementById('themeIcon');
            icon.setAttribute('data-lucide', isDarkMode ? 'sun' : 'moon');
            lucide.createIcons();
        }

        function newChat() {
            messages = [];
            document.getElementById('welcomeScreen').style.display = 'flex';
            document.getElementById('messages').style.display = 'none';
            document.getElementById('messageInput').value = '';
            
            // Close sidebar on mobile
            if (window.innerWidth < 768) {
                toggleSidebar();
            }
        }

        function selectChat(chatId) {
            currentChatId = chatId;
            // In a real app, load chat messages here
            
            // Close sidebar on mobile
            if (window.innerWidth < 768) {
                toggleSidebar();
            }
        }

        function loadChat() {
            // Show welcome screen initially
            document.getElementById('welcomeScreen').style.display = 'flex';
            document.getElementById('messages').style.display = 'none';
        }

        function showMessages() {
            document.getElementById('welcomeScreen').style.display = 'none';
            document.getElementById('messages').style.display = 'block';
        }

        function sendPresetMessage(message) {
            sendMessage(message);
        }

        function handleKeyPress(event) {
            if (event.key === 'Enter' && !event.shiftKey) {
                event.preventDefault();
                sendMessage();
            }
        }

        async function sendMessage(presetMessage = null) {
            const input = document.getElementById('messageInput');
            const message = presetMessage || input.value.trim();
            
            if (!message || isTyping) return;

            // Clear input
            input.value = '';
            
            // Close sidebar when sending message
            if (sidebarOpen) {
                toggleSidebar();
            }

            // Show messages container
            showMessages();

            // Add user message
            addMessage(message, true);

            // Show typing indicator
            showTyping();

            // Check for cat image request
            if (message.toLowerCase().includes('cat image') || message.toLowerCase().includes('cat pic')) {
                try {
                    const response = await fetch('https://api.thecatapi.com/v1/images/search');
                    const data = await response.json();
                    const imageUrl = data[0]?.url;
                    
                    setTimeout(() => {
                        hideTyping();
                        if (imageUrl) {
                            addMessage('meow (here\'s a random cat image for ur viewing pleasure!)', false, imageUrl);
                        } else {
                            addMessage('meow (sorry, I couldn\'t fetch a cat image right now. maybe try again later? *sad meow*)', false);
                        }
                    }, 1500);
                } catch (error) {
                    setTimeout(() => {
                        hideTyping();
                        addMessage('meow (sorry, I couldn\'t fetch a cat image right now. maybe try again later? *sad meow*)', false);
                    }, 1500);
                }
            } else {
                // Mock AI response
                setTimeout(() => {
                    hideTyping();
                    addMessage(generateCatResponse(message), false);
                }, 1500);
            }
        }

        function addMessage(content, isUser, imageUrl = null) {
            const messagesContainer = document.getElementById('messages');
            const messageDiv = document.createElement('div');
            messageDiv.className = `chat-message ${isUser ? 'user-message' : 'ai-message'}`;
            
            messageDiv.innerHTML = `
                <div class="d-flex gap-3 p-3 ${isUser ? 'justify-content-end' : ''}">
                    ${!isUser ? `
                        <div class="cat-avatar">
                            <i data-lucide="cat" style="width: 16px; height: 16px;"></i>
                        </div>
                    ` : ''}
                    <div class="flex-grow-1 ${isUser ? 'text-end' : ''}">
                        <div class="fw-medium mb-2">${isUser ? 'you' : 'cat-AI'}</div>
                        <div class="p-3 rounded ${isUser ? 'bg-primary text-white' : 'bg-light'}">
                            ${content}
                            ${imageUrl ? `<br><img src="${imageUrl}" class="img-fluid mt-2 rounded" style="max-width: 300px;">` : ''}
                        </div>
                        <div class="text-muted small mt-1">now</div>
                    </div>
                    ${isUser ? `
                        <div class="cat-avatar bg-secondary">
                            <i data-lucide="user" style="width: 16px; height: 16px;"></i>
                        </div>
                    ` : ''}
                </div>
            `;
            
            messagesContainer.appendChild(messageDiv);
            lucide.createIcons();
            
            // Scroll to bottom
            messageDiv.scrollIntoView({ behavior: 'smooth' });
            
            messages.push({
                id: Date.now().toString(),
                content: content,
                isUser: isUser,
                timestamp: 'now',
                imageUrl: imageUrl
            });
        }

        function showTyping() {
            isTyping = true;
            document.getElementById('typingIndicator').style.display = 'block';
            document.getElementById('sendButton').disabled = true;
            
            // Scroll to typing indicator
            document.getElementById('typingIndicator').scrollIntoView({ behavior: 'smooth' });
        }

        function hideTyping() {
            isTyping = false;
            document.getElementById('typingIndicator').style.display = 'none';
            document.getElementById('sendButton').disabled = false;
        }

        function generateCatResponse(userMessage) {
            const responses = [
                `meow (I understand you said: "${userMessage}". as a cat AI, I must say that's quite purr-fect!)`,
                `meow (regarding "${userMessage}" - that's interesting! *swishes tail thoughtfully*)`,
                `meow (about "${userMessage}" - let me pounce on that thought! *playful cat noises*)`,
                `meow (ur message "${userMessage}" reminds me of when I was a kitten... *nostalgic purring*)`,
                `meow (hmm, "${userMessage}" - that's worth a good cat nap to think about! *yawns*)`,
            ];
            return responses[Math.floor(Math.random() * responses.length)];
        }

        function showManagement() {
            window.location.href = '?page=management';
        }

        // Handle mobile sidebar
        window.addEventListener('resize', function() {
            if (window.innerWidth >= 768) {
                sidebarOpen = true;
                document.getElementById('sidebar').classList.remove('collapsed');
                document.getElementById('mainContent').classList.remove('expanded');
            }
        });
    </script>
</body>
</html>