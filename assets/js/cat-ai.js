// cat-AI JavaScript Functions

// Global variables
let currentTheme = document.documentElement.getAttribute('data-bs-theme') || 'dark';
let messages = [];

// Utility functions
function showNotification(message, type = 'info') {
    // Create toast notification
    const toastContainer = document.getElementById('toast-container') || createToastContainer();
    
    const toastId = 'toast-' + Date.now();
    const toast = document.createElement('div');
    toast.className = 'toast align-items-center border-0';
    toast.id = toastId;
    toast.setAttribute('role', 'alert');
    toast.setAttribute('aria-live', 'assertive');
    toast.setAttribute('aria-atomic', 'true');
    
    // Set toast color based on type
    let bgClass = 'text-bg-primary';
    switch (type) {
        case 'error':
            bgClass = 'text-bg-danger';
            break;
        case 'success':
            bgClass = 'text-bg-success';
            break;
        case 'warning':
            bgClass = 'text-bg-warning';
            break;
        default:
            bgClass = 'text-bg-primary';
    }
    
    toast.classList.add(bgClass);
    
    toast.innerHTML = `
        <div class="d-flex">
            <div class="toast-body fw-medium">
                ${message}
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
        </div>
    `;
    
    toastContainer.appendChild(toast);
    
    // Initialize and show toast
    const bsToast = new bootstrap.Toast(toast);
    bsToast.show();
    
    // Remove toast element after it's hidden
    toast.addEventListener('hidden.bs.toast', () => {
        toast.remove();
    });
}

function createToastContainer() {
    const container = document.createElement('div');
    container.id = 'toast-container';
    container.className = 'toast-container position-fixed top-0 end-0 p-3';
    container.style.zIndex = '9999';
    document.body.appendChild(container);
    return container;
}

// Login functionality
function handleLogin(event) {
    event.preventDefault();
    const formData = new FormData(event.target);
    formData.append('action', 'login');
    
    // Add loading state
    const submitBtn = event.target.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;
    submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>logging in...';
    submitBtn.disabled = true;
    
    fetch('', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification('login successful! 🐱', 'success');
            setTimeout(() => location.reload(), 1000);
        } else {
            showNotification(data.message, 'error');
        }
    })
    .catch(error => {
        console.error('Login error:', error);
        showNotification('connection error - please try again', 'error');
    })
    .finally(() => {
        submitBtn.innerHTML = originalText;
        submitBtn.disabled = false;
    });
}

function handleGuestLogin() {
    const formData = new FormData();
    formData.append('action', 'guest_login');
    
    fetch('', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification('entering as guest... 🐾', 'success');
            setTimeout(() => location.reload(), 1000);
        } else {
            showNotification(data.message, 'error');
        }
    })
    .catch(error => {
        console.error('Guest login error:', error);
        showNotification('connection error - please try again', 'error');
    });
}

// Chat functionality
function sendMessage() {
    const input = document.getElementById('message-input');
    const message = input.value.trim();
    
    if (!message) return;
    
    // Add user message to chat
    addMessageToChat(message, 'user');
    input.value = '';
    
    // Auto-resize textarea
    input.style.height = '48px';
    
    // Show typing indicator
    showTypingIndicator();
    
    // Send to backend
    const formData = new FormData();
    formData.append('action', 'send_message');
    formData.append('message', message);
    
    fetch('', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        hideTypingIndicator();
        if (data.success) {
            addMessageToChat(data.response, 'ai');
        } else {
            showNotification(data.message || 'ai service not connected', 'error');
        }
    })
    .catch(error => {
        console.error('Send message error:', error);
        hideTypingIndicator();
        showNotification('connection error - please try again', 'error');
    });
}

function getCatImage() {
    const formData = new FormData();
    formData.append('action', 'get_cat_image');
    
    showNotification('fetching a purrfect cat pic... 🐱', 'info');
    
    fetch('', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            addImageToChat(data.image_url);
        } else {
            showNotification(data.message || 'cat api not connected', 'error');
        }
    })
    .catch(error => {
        console.error('Cat image error:', error);
        showNotification('connection error - please try again', 'error');
    });
}

function getCatFact() {
    const formData = new FormData();
    formData.append('action', 'get_cat_fact');
    
    showNotification('finding an interesting cat fact... 💡', 'info');
    
    fetch('', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            addMessageToChat(`meow (here's a fun fact: ${data.fact})`, 'ai');
        } else {
            showNotification(data.message || 'cat api not connected', 'error');
        }
    })
    .catch(error => {
        console.error('Cat fact error:', error);
        showNotification('connection error - please try again', 'error');
    });
}

function addMessageToChat(message, sender) {
    const chatMessages = document.getElementById('chat-messages');
    
    // Remove welcome message if it exists
    const welcomeMessage = chatMessages.querySelector('.py-5');
    if (welcomeMessage) {
        welcomeMessage.remove();
    }
    
    const messageContainer = document.createElement('div');
    messageContainer.className = `message-container ${sender}`;
    
    const bubble = document.createElement('div');
    bubble.className = `chat-bubble ${sender}`;
    bubble.textContent = message;
    
    messageContainer.appendChild(bubble);
    chatMessages.appendChild(messageContainer);
    
    // Scroll to bottom
    chatMessages.scrollTop = chatMessages.scrollHeight;
}

function addImageToChat(imageUrl) {
    const chatMessages = document.getElementById('chat-messages');
    
    // Remove welcome message if it exists
    const welcomeMessage = chatMessages.querySelector('.py-5');
    if (welcomeMessage) {
        welcomeMessage.remove();
    }
    
    const messageContainer = document.createElement('div');
    messageContainer.className = 'message-container ai';
    
    const imageContainer = document.createElement('div');
    imageContainer.className = 'chat-bubble ai d-flex flex-column align-items-center';
    imageContainer.style.maxWidth = '300px';
    
    const img = document.createElement('img');
    img.src = imageUrl;
    img.className = 'cat-image mb-2 rounded';
    img.style.maxHeight = '200px';
    img.style.width = '100%';
    img.style.objectFit = 'cover';
    img.onerror = function() {
        this.src = 'https://images.unsplash.com/photo-1514888286974-6c03e2ca1dba?w=400&h=400&fit=crop&crop=face';
    };
    
    const caption = document.createElement('div');
    caption.textContent = "here's a purrfect cat pic for u! 🐱";
    caption.className = 'small text-center';
    
    imageContainer.appendChild(img);
    imageContainer.appendChild(caption);
    messageContainer.appendChild(imageContainer);
    chatMessages.appendChild(messageContainer);
    
    // Scroll to bottom
    chatMessages.scrollTop = chatMessages.scrollHeight;
}

function showTypingIndicator() {
    const chatMessages = document.getElementById('chat-messages');
    
    const typingContainer = document.createElement('div');
    typingContainer.id = 'typing-indicator';
    typingContainer.className = 'message-container ai';
    
    const typingBubble = document.createElement('div');
    typingBubble.className = 'chat-bubble ai typing-indicator';
    typingBubble.innerHTML = '<i class="bi bi-three-dots"></i> cat-AI is typing';
    
    typingContainer.appendChild(typingBubble);
    chatMessages.appendChild(typingContainer);
    
    // Scroll to bottom
    chatMessages.scrollTop = chatMessages.scrollHeight;
}

function hideTypingIndicator() {
    const typingIndicator = document.getElementById('typing-indicator');
    if (typingIndicator) {
        typingIndicator.remove();
    }
}

// Theme toggle
function toggleTheme() {
    const formData = new FormData();
    formData.append('action', 'toggle_theme');
    
    fetch('', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Update theme immediately
            currentTheme = data.theme;
            document.documentElement.setAttribute('data-bs-theme', currentTheme);
            
            // Update theme toggle button icon
            updateThemeToggleIcon();
            
            showNotification(`switched to ${currentTheme} mode! 🌙☀️`, 'success');
        }
    })
    .catch(error => {
        console.error('Theme toggle error:', error);
        showNotification('theme toggle failed', 'error');
    });
}

function updateThemeToggleIcon() {
    const themeButtons = document.querySelectorAll('[onclick="toggleTheme()"]');
    themeButtons.forEach(button => {
        const icon = button.querySelector('i');
        if (icon) {
            icon.className = currentTheme === 'dark' ? 'bi bi-sun' : 'bi bi-moon';
        } else {
            // Fallback for buttons without icon elements
            button.innerHTML = button.innerHTML.replace(/[🌙☀️]/, currentTheme === 'dark' ? '☀️' : '🌙');
        }
    });
}

// Management panel
function openManagementPanel() {
    const modalElement = document.getElementById('management-panel');
    if (modalElement) {
        modalElement.classList.remove('d-none');
        modalElement.style.display = 'flex';
        modalElement.style.alignItems = 'center';
        modalElement.style.justifyContent = 'center';
        
        // Add backdrop click handler
        modalElement.addEventListener('click', function(e) {
            if (e.target === modalElement) {
                closeManagementPanel();
            }
        });
    }
}

function closeManagementPanel() {
    const modalElement = document.getElementById('management-panel');
    if (modalElement) {
        modalElement.classList.add('d-none');
        modalElement.style.display = 'none';
    }
}

// Logout
function logout() {
    if (confirm('are u sure u want to logout? 🐱')) {
        const formData = new FormData();
        formData.append('action', 'logout');
        
        fetch('', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showNotification('logged out successfully! 👋', 'success');
                setTimeout(() => location.reload(), 1000);
            }
        })
        .catch(error => {
            console.error('Logout error:', error);
            showNotification('logout failed', 'error');
        });
    }
}

// Auto-resize textarea
function setupTextareaAutoResize() {
    const messageInput = document.getElementById('message-input');
    if (messageInput) {
        messageInput.addEventListener('input', function() {
            this.style.height = '48px';
            this.style.height = Math.min(this.scrollHeight, 120) + 'px';
        });
    }
}

// Event listeners
document.addEventListener('DOMContentLoaded', function() {
    // Setup textarea auto-resize
    setupTextareaAutoResize();
    
    // Enter key for chat input
    const messageInput = document.getElementById('message-input');
    if (messageInput) {
        messageInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter' && !e.shiftKey) {
                e.preventDefault();
                sendMessage();
            }
        });
    }
    
    // Initialize theme
    currentTheme = document.documentElement.getAttribute('data-bs-theme') || 'dark';
    updateThemeToggleIcon();
    
    // Close management panel with Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeManagementPanel();
        }
    });
    
    // Initialize Bootstrap tooltips if any
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
    
    // Mobile sidebar close when clicking on menu items
    const mobileSidebar = document.getElementById('mobileSidebar');
    if (mobileSidebar) {
        mobileSidebar.addEventListener('click', function(e) {
            if (e.target.closest('button') && !e.target.closest('.btn-close')) {
                bootstrap.Offcanvas.getInstance(mobileSidebar)?.hide();
            }
        });
    }
    
    console.log('🐱 cat-AI initialized successfully!');
});

// =================================================================
// CRUD OPERATIONS FOR MANAGEMENT PANEL
// =================================================================

// User Management
function openUserModal(userId = null) {
    const modal = document.getElementById('userModal');
    const form = document.getElementById('userForm');
    const title = document.getElementById('userModalLabel');
    const saveButton = document.getElementById('saveButtonText');
    
    // Reset form
    form.reset();
    document.getElementById('userId').value = '';
    
    if (userId) {
        // Edit mode
        title.textContent = 'edit user';
        saveButton.textContent = 'update user';
        document.getElementById('password').required = false;
        
        // Load user data
        loadUserData(userId);
    } else {
        // Add mode
        title.textContent = 'add new user';
        saveButton.textContent = 'save user';
        document.getElementById('password').required = true;
    }
}

function loadUserData(userId) {
    const formData = new FormData();
    formData.append('action', 'get_user');
    formData.append('id', userId);
    
    fetch('', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const user = data.user;
            document.getElementById('userId').value = user.id;
            document.getElementById('username').value = user.username;
            document.getElementById('email').value = user.email;
            document.getElementById('isAdmin').checked = user.is_admin == 1;
        } else {
            showNotification(data.message || 'failed to load user data', 'error');
        }
    })
    .catch(error => {
        console.error('Load user error:', error);
        showNotification('connection error', 'error');
    });
}

function saveUser(event) {
    event.preventDefault();
    
    const form = event.target;
    const formData = new FormData(form);
    const userId = formData.get('userId');
    
    formData.append('action', userId ? 'update_user' : 'create_user');
    
    // Add loading state
    const submitBtn = form.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;
    submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>saving...';
    submitBtn.disabled = true;
    
    fetch('', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification(data.message || 'user saved successfully! 🐱', 'success');
            bootstrap.Modal.getInstance(document.getElementById('userModal')).hide();
            setTimeout(() => location.reload(), 1000);
        } else {
            showNotification(data.message || 'failed to save user', 'error');
        }
    })
    .catch(error => {
        console.error('Save user error:', error);
        showNotification('connection error', 'error');
    })
    .finally(() => {
        submitBtn.innerHTML = originalText;
        submitBtn.disabled = false;
    });
}

function editUser(userId) {
    openUserModal(userId);
}

function deleteUser(userId) {
    if (confirm('are u sure u want to delete this user? this action cannot be undone! 🐱')) {
        const formData = new FormData();
        formData.append('action', 'delete_user');
        formData.append('id', userId);
        
        fetch('', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showNotification('user deleted successfully! 🐱', 'success');
                setTimeout(() => location.reload(), 1000);
            } else {
                showNotification(data.message || 'failed to delete user', 'error');
            }
        })
        .catch(error => {
            console.error('Delete user error:', error);
            showNotification('connection error', 'error');
        });
    }
}

// API Log Management
function deleteApiLog(logId) {
    if (confirm('are u sure u want to delete this API log? 🐱')) {
        const formData = new FormData();
        formData.append('action', 'delete_api_log');
        formData.append('id', logId);
        
        fetch('', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showNotification('API log deleted successfully! 🐱', 'success');
                setTimeout(() => location.reload(), 1000);
            } else {
                showNotification(data.message || 'failed to delete API log', 'error');
            }
        })
        .catch(error => {
            console.error('Delete API log error:', error);
            showNotification('connection error', 'error');
        });
    }
}

function exportApiLogs() {
    const formData = new FormData();
    formData.append('action', 'export_api_logs');
    
    fetch('', {
        method: 'POST',
        body: formData
    })
    .then(response => response.blob())
    .then(blob => {
        const url = window.URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.style.display = 'none';
        a.href = url;
        a.download = 'api-usage-logs-' + new Date().toISOString().split('T')[0] + '.csv';
        document.body.appendChild(a);
        a.click();
        window.URL.revokeObjectURL(url);
        showNotification('API logs exported successfully! 📊', 'success');
    })
    .catch(error => {
        console.error('Export error:', error);
        showNotification('export failed - check console for details', 'error');
    });
}

// Database status checker
function checkDatabaseStatus() {
    const formData = new FormData();
    formData.append('action', 'check_database');
    
    fetch('', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const status = data.status;
            let message = '';
            let type = 'info';
            
            switch (status.status) {
                case 'connected':
                    message = 'database connected successfully! 🐱';
                    type = 'success';
                    break;
                case 'missing':
                    message = 'database config file not found';
                    type = 'warning';
                    break;
                case 'incomplete':
                    message = 'database config incomplete: ' + status.message;
                    type = 'warning';
                    break;
                case 'connection_failed':
                    message = 'database connection failed';
                    type = 'error';
                    break;
                default:
                    message = status.message;
                    type = 'error';
            }
            
            showNotification(message, type);
        }
    })
    .catch(error => {
        console.error('Database check error:', error);
        showNotification('failed to check database status', 'error');
    });
}

// Auto-check database status on management panel load
document.addEventListener('DOMContentLoaded', function() {
    const managementPanel = document.getElementById('management-panel');
    if (managementPanel && !managementPanel.classList.contains('d-none')) {
        checkDatabaseStatus();
    }
});