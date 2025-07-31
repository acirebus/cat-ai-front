<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>cat-AI login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/lucide@0.294.0/dist/umd/lucide.css" rel="stylesheet">
    <link href="/assets/css/cat-ai-bootstrap.css" rel="stylesheet">
    <style>
        :root {
            --cat-orange: #f97316;
        }
        
        .login-container {
            min-height: 100vh;
            background: linear-gradient(135deg, #f97316 0%, #ea580c 100%);
        }
        
        .login-card {
            max-width: 400px;
            background: white;
            border-radius: 16px;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
        }
        
        .cat-avatar {
            background-color: var(--cat-orange);
            border-radius: 50%;
            width: 64px;
            height: 64px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            margin: 0 auto;
        }
        
        .form-floating .form-control {
            border-radius: 12px;
        }
        
        .btn-cat {
            background-color: var(--cat-orange);
            border-color: var(--cat-orange);
            border-radius: 12px;
            font-weight: 500;
            padding: 12px;
        }
        
        .btn-cat:hover {
            background-color: #ea580c;
            border-color: #ea580c;
        }
        
        .btn-outline-cat {
            color: var(--cat-orange);
            border-color: var(--cat-orange);
            border-radius: 12px;
            font-weight: 500;
            padding: 12px;
        }
        
        .btn-outline-cat:hover {
            background-color: var(--cat-orange);
            border-color: var(--cat-orange);
            color: white;
        }
    </style>
</head>
<body>
    <div class="login-container d-flex align-items-center justify-content-center p-4">
        <div class="login-card p-5 w-100">
            <div class="text-center mb-4">
                <div class="cat-avatar mb-3">
                    <i data-lucide="cat" style="width: 32px; height: 32px;"></i>
                </div>
                <h1 class="h3 mb-2">welcome to cat-AI</h1>
                <p class="text-muted">ur feline assistant awaits</p>
            </div>

            <!-- Login Form -->
            <div id="loginForm">
                <form onsubmit="handleLogin(event)">
                    <div class="form-floating mb-3">
                        <input type="email" class="form-control" id="loginEmail" placeholder="email@example.com" required autocomplete="email">
                        <label for="loginEmail">email</label>
                    </div>
                    <div class="form-floating mb-4">
                        <input type="password" class="form-control" id="loginPassword" placeholder="password" required>
                        <label for="loginPassword">password</label>
                    </div>
                    <button type="submit" class="btn btn-cat text-white w-100 mb-3">
                        <i data-lucide="log-in" class="me-2"></i>sign in
                    </button>
                </form>
                
                <div class="text-center mb-3">
                    <button type="button" class="btn btn-outline-cat w-100" onclick="continueWithoutLogin()">
                        <i data-lucide="user-x" class="me-2"></i>continue without login
                    </button>
                </div>
                
                <div class="text-center">
                    <span class="text-muted">don't have an account? </span>
                    <button type="button" class="btn btn-link p-0" onclick="showRegister()">sign up</button>
                </div>
            </div>

            <!-- Register Form -->
            <div id="registerForm" style="display: none;">
                <form onsubmit="handleRegister(event)">
                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" id="registerUsername" placeholder="username" required>
                        <label for="registerUsername">username</label>
                    </div>
                    <div class="form-floating mb-3">
                        <input type="email" class="form-control" id="registerEmail" placeholder="email@example.com" required autocomplete="email">
                        <label for="registerEmail">email</label>
                    </div>
                    <div class="form-floating mb-4">
                        <input type="password" class="form-control" id="registerPassword" placeholder="password" required>
                        <label for="registerPassword">password</label>
                    </div>
                    <button type="submit" class="btn btn-cat text-white w-100 mb-3">
                        <i data-lucide="user-plus" class="me-2"></i>create account
                    </button>
                </form>
                
                <div class="text-center mb-3">
                    <button type="button" class="btn btn-outline-cat w-100" onclick="continueWithoutLogin()">
                        <i data-lucide="user-x" class="me-2"></i>continue without login
                    </button>
                </div>
                
                <div class="text-center">
                    <span class="text-muted">already have an account? </span>
                    <button type="button" class="btn btn-link p-0" onclick="showLogin()">sign in</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            lucide.createIcons();
        });

        function showRegister() {
            document.getElementById('loginForm').style.display = 'none';
            document.getElementById('registerForm').style.display = 'block';
        }

        function showLogin() {
            document.getElementById('registerForm').style.display = 'none';
            document.getElementById('loginForm').style.display = 'block';
        }

        function handleLogin(event) {
            event.preventDefault();
            const email = document.getElementById('loginEmail').value;
            const password = document.getElementById('loginPassword').value;

            fetch('login_api.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: `email=${encodeURIComponent(email)}&password=${encodeURIComponent(password)}`
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    window.location.href = '?page=chat';
                } else {
                    alert('Login failed: ' + data.message);
                }
            })
            .catch(() => {
                alert('Login failed: server error');
            });
        }

        function handleRegister(event) {
            event.preventDefault();
            const username = document.getElementById('registerUsername').value;
            const email = document.getElementById('registerEmail').value;
            const password = document.getElementById('registerPassword').value;

            fetch('register.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: `username=${encodeURIComponent(username)}&email=${encodeURIComponent(email)}&password=${encodeURIComponent(password)}`
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Registration successful, redirect to chat
                    window.location.href = '?page=chat';
                } else {
                    alert('Registration failed: ' + data.message);
                }
            })
            .catch(() => {
                alert('Registration failed: server error');
            });
        }

        function continueWithoutLogin() {
            // Continue as guest
            console.log('Continuing as guest');
            window.location.href = '?page=chat';
        }
    </script>
</body>
</html>
