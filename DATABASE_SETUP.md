

### Step 1: Create Database
```sql
-- Run this in ur MySQL/MariaDB client
CREATE DATABASE cat_ai CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

### Step 2: Import Schema
```bash
# Import the schema file
mysql -u ur_username -p cat_ai < database/schema.sql
```

### Step 3: Configure Database Connection
Edit `config/database.php` with ur database credentials:

```php
return [
    'host' => 'localhost',
    'port' => '3306',
    'database' => 'cat_ai',
    'username' => 'ur_username',
    'password' => 'ur_password',
];
```

## Environment Variables (Recommended)

Create a `.env` file in ur project root:

```env
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=cat_ai
DB_USERNAME=ur_username
DB_PASSWORD=ur_password
DB_LOG_QUERIES=false
```

## Database Schema

### Tables Created

1. **users** - User accounts and authentication
2. **chats** - Chat sessions between users and AI
3. **messages** - Individual messages in chats
4. **api_usage_logs** - API usage tracking and analytics
5. **sessions** - Database session storage (optional)

### Default Users

The schema creates two default users:

- **admin** / admin123 (admin user)
- **testuser** / user123 (regular user)

⚠️ **Change these passwords in production!**

## Features Ready to Use

### ✅ User Management (CRUD)
- Create new users with username, email, password
- Edit user details and admin privileges
- Delete users (cannot delete admin users)
- User authentication and sessions

### ✅ API Usage Tracking
- Automatic logging of all API calls
- Response time tracking
- User activity monitoring
- Export logs to CSV

### ✅ Chat System Ready
- Database structure for chat sessions
- Message storage with metadata
- User-chat associations

### ✅ Admin Panel
- User management interface
- API usage analytics
- Real-time database status checking
- Bootstrap-powered responsive design

## Database Connection Status

The application automatically checks database connectivity:

- **Connected**: Full CRUD functionality available
- **Not Connected**: Falls back to session-based storage
- **Config Missing**: Shows setup instructions

## Security Features

- Password hashing with PHP's `password_hash()`
- SQL injection protection with prepared statements
- Input validation and sanitization
- Admin user protection (cannot be deleted)
- Session-based authentication

## Performance Optimizations

- Database indexes on frequently queried columns
- Connection pooling support
- Query logging for development
- Automatic old log cleanup functions

## Troubleshooting

### Connection Failed
1. Check database credentials in `config/database.php`
2. Ensure MySQL/MariaDB service is running
3. Verify database exists and user has permissions

### Tables Not Found
1. Import the schema: `mysql -u username -p cat_ai < database/schema.sql`
2. Check database name in config
3. Verify user has CREATE/SELECT permissions

### Permission Denied
1. Grant proper permissions to database user:
```sql
GRANT ALL PRIVILEGES ON cat_ai.* TO 'ur_username'@'localhost';
FLUSH PRIVILEGES;
```

## Advanced Configuration

### Custom Database Settings
Edit `config/database.php` for advanced options:

```php
'options' => [
    PDO::ATTR_PERSISTENT => true,        // Connection pooling
    PDO::ATTR_TIMEOUT => 30,             // Connection timeout
    PDO::MYSQL_ATTR_USE_BUFFERED_QUERY => true,
],
```

### Query Logging
Enable query logging for development:

```php
'log_queries' => true,
'slow_query_threshold' => 2000, // Log queries slower than 2 seconds
```

## Production Recommendations

1. **Change default passwords** immediately
2. **Use environment variables** for sensitive data
3. **Enable SSL connections** for production
4. **Set up database backups** regularly
5. **Monitor query performance** with logging
6. **Implement rate limiting** for API endpoints

## Next Steps

After database setup:

1. 🔗 **Connect Groq API** for AI responses
2. 🐱 **Connect Cat API** for random cat content
3. 🔐 **Implement proper authentication** flow
4. 📊 **Set up monitoring** and analytics
5. 🚀 **Deploy to production** environment

## Need Help?

- Check `verify-php-setup.php` for configuration validation
- Review logs in ur web server error log
- Test database connection in management panel
- Check `INTEGRATION_GUIDE.md` for API setup
