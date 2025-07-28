<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>cat-AI management panel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/lucide@0.294.0/dist/umd/lucide.css" rel="stylesheet">
    <link href="/assets/css/cat-ai-bootstrap.css" rel="stylesheet">
    <style>
        :root {
            --cat-orange: #f97316;
        }
        
        .dark-mode {
            background-color: #0f172a !important;
            color: #f1f5f9 !important;
        }
        
        .dark-mode .bg-light {
            background-color: #1e293b !important;
            color: #f1f5f9 !important;
        }
        
        .dark-mode .bg-white {
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
        
        .stats-card {
            transition: transform 0.2s;
        }
        
        .stats-card:hover {
            transform: translateY(-2px);
        }
        
        .table-actions {
            min-width: 120px;
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <!-- Header -->
        <div class="row">
            <div class="col-12">
                <nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom">
                    <div class="container-fluid">
                        <div class="d-flex align-items-center gap-3">
                            <button class="btn btn-outline-secondary" onclick="goBack()">
                                <i data-lucide="arrow-left" class="me-2"></i>back to chat
                            </button>
                            <div class="cat-avatar">
                                <i data-lucide="cat"></i>
                            </div>
                            <h5 class="mb-0">cat-AI management panel</h5>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <span class="text-muted small">logged in as: <?php echo htmlspecialchars($_SESSION['user']['name']); ?></span>
                            <button class="btn btn-outline-secondary btn-sm" onclick="toggleDarkMode()">
                                <i data-lucide="moon" id="themeIcon"></i>
                            </button>
                        </div>
                    </div>
                </nav>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="row mt-4">
            <div class="col-md-3 mb-3">
                <div class="card stats-card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="cat-avatar me-3">
                                <i data-lucide="users"></i>
                            </div>
                            <div>
                                <h3 class="mb-0" id="totalUsers">-</h3>
                                <small class="text-muted">total users</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card stats-card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="cat-avatar me-3">
                                <i data-lucide="message-circle"></i>
                            </div>
                            <div>
                                <h3 class="mb-0" id="totalChats">-</h3>
                                <small class="text-muted">total chats</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card stats-card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="cat-avatar me-3">
                                <i data-lucide="activity"></i>
                            </div>
                            <div>
                                <h3 class="mb-0" id="apiCalls">-</h3>
                                <small class="text-muted">API calls today</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card stats-card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="cat-avatar me-3">
                                <i data-lucide="image"></i>
                            </div>
                            <div>
                                <h3 class="mb-0" id="catImages">-</h3>
                                <small class="text-muted">cat images served</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabs -->
        <div class="row mt-4">
            <div class="col-12">
                <ul class="nav nav-tabs" id="managementTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="users-tab" data-bs-toggle="tab" data-bs-target="#users" type="button" role="tab">
                            <i data-lucide="users" class="me-2"></i>users
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="api-logs-tab" data-bs-toggle="tab" data-bs-target="#api-logs" type="button" role="tab">
                            <i data-lucide="activity" class="me-2"></i>API usage logs
                        </button>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Tab Content -->
        <div class="tab-content mt-3" id="managementTabContent">
            <!-- Users Tab -->
            <div class="tab-pane fade show active" id="users" role="tabpanel">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h6 class="mb-0">user management</h6>
                        <button class="btn btn-primary btn-sm" onclick="showAddUserModal()">
                            <i data-lucide="plus" class="me-2"></i>add user
                        </button>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>username</th>
                                        <th>email</th>
                                        <th>role</th>
                                        <th>status</th>
                                        <th>joined</th>
                                        <th class="table-actions">actions</th>
                                    </tr>
                                </thead>
                                <tbody id="usersTableBody">
                                    <!-- Will be populated by JavaScript -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- API Logs Tab -->
            <div class="tab-pane fade" id="api-logs" role="tabpanel">
                <div class="card">
                    <div class="card-header">
                        <h6 class="mb-0">API usage logs</h6>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>timestamp</th>
                                        <th>user</th>
                                        <th>endpoint</th>
                                        <th>method</th>
                                        <th>status</th>
                                        <th>response time</th>
                                        <th>tokens used</th>
                                    </tr>
                                </thead>
                                <tbody id="apiLogsTableBody">
                                    <!-- Will be populated by JavaScript -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add User Modal -->
    <div class="modal fade" id="addUserModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">add new user</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="addUserForm">
                        <div class="mb-3">
                            <label class="form-label">username</label>
                            <input type="text" class="form-control" id="newUsername" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">email</label>
                            <input type="email" class="form-control" id="newEmail" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">role</label>
                            <select class="form-select" id="newRole">
                                <option value="user">user</option>
                                <option value="admin">admin</option>
                            </select>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">cancel</button>
                    <button type="button" class="btn btn-primary" onclick="addUser()">add user</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit User Modal -->
    <div class="modal fade" id="editUserModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">edit user</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="editUserForm">
                        <input type="hidden" id="editUserId">
                        <div class="mb-3">
                            <label class="form-label">username</label>
                            <input type="text" class="form-control" id="editUsername" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">email</label>
                            <input type="email" class="form-control" id="editEmail" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">role</label>
                            <select class="form-select" id="editRole">
                                <option value="user">user</option>
                                <option value="admin">admin</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">status</label>
                            <select class="form-select" id="editStatus">
                                <option value="active">active</option>
                                <option value="inactive">inactive</option>
                                <option value="banned">banned</option>
                            </select>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">cancel</button>
                    <button type="button" class="btn btn-primary" onclick="updateUser()">update user</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>
    <script>
        let users = [];
        let apiLogs = [];
        let isDarkMode = false;

        document.addEventListener('DOMContentLoaded', function() {
            lucide.createIcons();
            loadData();
        });

        function goBack() {
            window.location.href = '?page=chat';
        }

        function toggleDarkMode() {
            isDarkMode = !isDarkMode;
            document.body.classList.toggle('dark-mode', isDarkMode);
            
            const icon = document.getElementById('themeIcon');
            icon.setAttribute('data-lucide', isDarkMode ? 'sun' : 'moon');
            lucide.createIcons();
        }

        function loadData() {
            // Load stats
            document.getElementById('totalUsers').textContent = '0';
            document.getElementById('totalChats').textContent = '0';
            document.getElementById('apiCalls').textContent = '0';
            document.getElementById('catImages').textContent = '0';

            // Load empty tables - ready for backend integration
            loadUsers();
            loadApiLogs();
        }

        function loadUsers() {
            const tbody = document.getElementById('usersTableBody');
            if (users.length === 0) {
                tbody.innerHTML = `
                    <tr>
                        <td colspan="7" class="text-center text-muted py-4">
                            <i data-lucide="users" class="mb-2"></i><br>
                            no users found. ready for backend integration!
                        </td>
                    </tr>
                `;
            } else {
                tbody.innerHTML = users.map(user => `
                    <tr>
                        <td>${user.id}</td>
                        <td>${user.username}</td>
                        <td>${user.email}</td>
                        <td><span class="badge bg-${user.role === 'admin' ? 'danger' : 'primary'}">${user.role}</span></td>
                        <td><span class="badge bg-${user.status === 'active' ? 'success' : user.status === 'inactive' ? 'warning' : 'danger'}">${user.status}</span></td>
                        <td>${user.created_at}</td>
                        <td class="table-actions">
                            <button class="btn btn-sm btn-outline-primary me-1" onclick="editUser(${user.id})">
                                <i data-lucide="edit-2"></i>
                            </button>
                            <button class="btn btn-sm btn-outline-danger" onclick="deleteUser(${user.id})">
                                <i data-lucide="trash-2"></i>
                            </button>
                        </td>
                    </tr>
                `).join('');
            }
            lucide.createIcons();
        }

        function loadApiLogs() {
            const tbody = document.getElementById('apiLogsTableBody');
            if (apiLogs.length === 0) {
                tbody.innerHTML = `
                    <tr>
                        <td colspan="7" class="text-center text-muted py-4">
                            <i data-lucide="activity" class="mb-2"></i><br>
                            no API logs found. ready for backend integration!
                        </td>
                    </tr>
                `;
            } else {
                tbody.innerHTML = apiLogs.map(log => `
                    <tr>
                        <td>${log.timestamp}</td>
                        <td>${log.user}</td>
                        <td>${log.endpoint}</td>
                        <td><span class="badge bg-${log.method === 'GET' ? 'info' : log.method === 'POST' ? 'success' : 'warning'}">${log.method}</span></td>
                        <td><span class="badge bg-${log.status === 200 ? 'success' : 'danger'}">${log.status}</span></td>
                        <td>${log.response_time}ms</td>
                        <td>${log.tokens_used || '-'}</td>
                    </tr>
                `).join('');
            }
            lucide.createIcons();
        }

        function showAddUserModal() {
            new bootstrap.Modal(document.getElementById('addUserModal')).show();
        }

        function addUser() {
            const username = document.getElementById('newUsername').value;
            const email = document.getElementById('newEmail').value;
            const role = document.getElementById('newRole').value;

            if (!username || !email) {
                alert('please fill in all required fields');
                return;
            }

            const newUser = {
                id: users.length + 1,
                username: username,
                email: email,
                role: role,
                status: 'active',
                created_at: new Date().toLocaleDateString()
            };

            users.push(newUser);
            loadUsers();
            bootstrap.Modal.getInstance(document.getElementById('addUserModal')).hide();
            
            // Clear form
            document.getElementById('addUserForm').reset();
            
            // Update stats
            document.getElementById('totalUsers').textContent = users.length;

            console.log('User added:', newUser, '- Ready for backend integration');
        }

        function editUser(userId) {
            const user = users.find(u => u.id === userId);
            if (!user) return;

            document.getElementById('editUserId').value = user.id;
            document.getElementById('editUsername').value = user.username;
            document.getElementById('editEmail').value = user.email;
            document.getElementById('editRole').value = user.role;
            document.getElementById('editStatus').value = user.status;

            new bootstrap.Modal(document.getElementById('editUserModal')).show();
        }

        function updateUser() {
            const userId = parseInt(document.getElementById('editUserId').value);
            const username = document.getElementById('editUsername').value;
            const email = document.getElementById('editEmail').value;
            const role = document.getElementById('editRole').value;
            const status = document.getElementById('editStatus').value;

            const userIndex = users.findIndex(u => u.id === userId);
            if (userIndex === -1) return;

            users[userIndex] = {
                ...users[userIndex],
                username: username,
                email: email,
                role: role,
                status: status
            };

            loadUsers();
            bootstrap.Modal.getInstance(document.getElementById('editUserModal')).hide();

            console.log('User updated:', users[userIndex], '- Ready for backend integration');
        }

        function deleteUser(userId) {
            if (confirm('are you sure you want to delete this user? this action cannot be undone.')) {
                users = users.filter(u => u.id !== userId);
                loadUsers();
                document.getElementById('totalUsers').textContent = users.length;
                
                console.log('User deleted:', userId, '- Ready for backend integration');
            }
        }
    </script>
</body>
</html>