<?php
/**
 * cat-AI PHP Setup Verification 🐱
 * 
 * Run this script to verify that all required files exist
 * and the PHP application is ready to run
 */

echo "🐱 cat-AI PHP Setup Verification\n";
echo "=================================\n\n";

// Required files for PHP version
$requiredFiles = [
    'index.php' => 'Main application file',
    'components/php/functions.php' => 'PHP utility functions',
    'services/CatImageService.php' => 'API service handlers',
    'config/app.php' => 'Configuration file',
    'config/database.php' => 'Database configuration',
    'database/schema.sql' => 'Database schema',
    'database/connection.php' => 'Database connection class',
    'database/models.php' => 'Database models and CRUD',
    'pages/login.php' => 'Login page template',
    'pages/chat.php' => 'Chat interface template', 
    'pages/management.php' => 'Admin panel template',
    'assets/css/cat-ai-bootstrap.css' => 'Bootstrap-based styles',
    'assets/js/cat-ai.js' => 'Application JavaScript'
];

// Check PHP version
echo "📋 System Requirements:\n";
echo "PHP Version: " . PHP_VERSION;
if (version_compare(PHP_VERSION, '7.4.0', '>=')) {
    echo " ✅ (Required: 7.4+)\n";
} else {
    echo " ❌ (Required: 7.4+)\n";
}

// Check required extensions
$requiredExtensions = ['json', 'session', 'curl'];
echo "PHP Extensions:\n";
foreach ($requiredExtensions as $ext) {
    echo "  - $ext: " . (extension_loaded($ext) ? '✅' : '❌') . "\n";
}

echo "\n📁 Required Files:\n";
$allFilesExist = true;

foreach ($requiredFiles as $file => $description) {
    $exists = file_exists($file);
    $allFilesExist = $allFilesExist && $exists;
    
    echo ($exists ? '✅' : '❌') . " $file";
    if ($exists) {
        $size = filesize($file);
        echo " (" . round($size / 1024, 1) . " KB)";
    }
    echo " - $description\n";
}

echo "\n🔧 Configuration Check:\n";

// Check config file
if (file_exists('config/app.php')) {
    $config = include 'config/app.php';
    
    echo "✅ Config file loaded\n";
    echo "  - App name: " . ($config['name'] ?? 'Not set') . "\n";
    echo "  - Version: " . ($config['version'] ?? 'Not set') . "\n";
    
    // Check API keys
    $groqKey = $config['groq_api']['api_key'] ?? '';
    $catKey = $config['cat_api']['api_key'] ?? '';
    
    echo "  - Groq API key: " . ($groqKey ? '🔑 Set' : '❌ Not set') . "\n";
    echo "  - Cat API key: " . ($catKey ? '🔑 Set' : '❌ Not set') . "\n";
} else {
    echo "❌ Config file not found\n";
}

echo "\n🎨 Assets Check:\n";

// Check CSS
if (file_exists('assets/css/cat-ai-bootstrap.css')) {
    $cssContent = file_get_contents('assets/css/cat-ai-bootstrap.css');
    $cssSize = strlen($cssContent);
    echo "✅ Bootstrap CSS file exists (" . round($cssSize / 1024, 1) . " KB)\n";
    
    // Check for key CSS classes
    $keyClasses = ['.btn-cat-primary', '.chat-bubble', '.cat-theme', '--cat-primary'];
    foreach ($keyClasses as $class) {
        echo "  - $class: " . (strpos($cssContent, $class) !== false ? '✅' : '❌') . "\n";
    }
} else {
    echo "❌ Bootstrap CSS file not found\n";
}

// Check JavaScript
if (file_exists('assets/js/cat-ai.js')) {
    $jsContent = file_get_contents('assets/js/cat-ai.js');
    $jsSize = strlen($jsContent);
    echo "✅ JavaScript file exists (" . round($jsSize / 1024, 1) . " KB)\n";
    
    // Check for key functions
    $keyFunctions = ['handleLogin', 'sendMessage', 'toggleTheme', 'showNotification'];
    foreach ($keyFunctions as $func) {
        echo "  - $func(): " . (strpos($jsContent, "function $func") !== false ? '✅' : '❌') . "\n";
    }
} else {
    echo "❌ JavaScript file not found\n";
}

echo "\n📄 Page Templates Check:\n";
$pageFiles = ['login.php', 'chat.php', 'management.php'];
foreach ($pageFiles as $page) {
    $exists = file_exists("pages/$page");
    echo ($exists ? '✅' : '❌') . " pages/$page";
    if ($exists) {
        $size = filesize("pages/$page");
        echo " (" . round($size / 1024, 1) . " KB)";
    }
    echo "\n";
}

echo "\n💾 Database Files Check:\n";
$dbFiles = [
    'config/database.php' => 'Database configuration',
    'database/schema.sql' => 'Database schema',
    'database/connection.php' => 'Connection class',
    'database/models.php' => 'CRUD models'
];

foreach ($dbFiles as $file => $desc) {
    $exists = file_exists($file);
    echo ($exists ? '✅' : '❌') . " $file";
    if ($exists) {
        $size = filesize($file);
        echo " (" . round($size / 1024, 1) . " KB)";
    }
    echo "\n";
}

echo "\n🚀 Server Test:\n";

// Test if we can start a server (just check if port is available)
$port = 8000;
$socket = @socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
if ($socket) {
    $result = @socket_bind($socket, 'localhost', $port);
    if ($result) {
        echo "✅ Port $port is available\n";
        socket_close($socket);
    } else {
        echo "⚠️  Port $port is in use (server might already be running)\n";
        socket_close($socket);
    }
} else {
    echo "⚠️  Could not test port availability\n";
}

echo "\n📊 Summary:\n";
echo "===========\n";

if ($allFilesExist) {
    echo "✅ All required files are present\n";
    echo "✅ Bootstrap-based responsive design ready!\n";
    echo "✅ Separated page templates for maintainability\n\n";
    
    echo "🚀 To start the application:\n";
    echo "   php -S localhost:$port\n";
    echo "   Then open: http://localhost:$port\n\n";
    
    echo "🎨 New Features:\n";
    echo "   • Bootstrap 5.3.2 responsive framework\n";
    echo "   • Mobile-friendly design with offcanvas sidebar\n";
    echo "   • Toast notifications\n";
    echo "   • Separated page templates (login, chat, management)\n";
    echo "   • Clean utility functions only\n";
    echo "   • Modern animations and transitions\n\n";
    
    if (empty($groqKey) || empty($catKey)) {
        echo "🔧 Next steps:\n";
        echo "   1. Add API keys to config/app.php\n";
        echo "   2. Follow INTEGRATION_GUIDE.md\n";
        echo "   3. Implement backend connections\n\n";
    }
    
    echo "🐱 meow! ur modern Bootstrap cat-AI is ready to go!\n";
} else {
    echo "❌ Some required files are missing\n";
    echo "❌ Please ensure all files are in place\n\n";
    
    echo "📖 Check PHP_FILES_ONLY.md for the updated file list\n";
    echo "🔧 Run cleanup-for-php.php to clean up old files\n";
    echo "🐱 meow! please fix the missing files first\n";
}

echo "\n" . str_repeat("=", 50) . "\n";
?>