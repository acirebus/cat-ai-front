<?php
/**
 * cat-AI PHP Cleanup Script 🐱
 * 
 * This script removes all unnecessary files for the PHP-only version
 * Keep ONLY the files needed to run the PHP application
 */

echo "🐱 cat-AI PHP Cleanup Script\n";
echo "============================\n\n";

// Files and directories to KEEP
$keepFiles = [
    'index.php',
    'components/php/functions.php',
    'services/CatImageService.php', 
    'config/app.php',
    'config/database.php',
    'database/schema.sql',
    'database/connection.php',
    'database/models.php',
    'assets/css/cat-ai-bootstrap.css',
    'assets/js/cat-ai.js',
    'pages/login.php',
    'pages/chat.php',
    'pages/management.php',
    'INTEGRATION_GUIDE.md',
    'PHP_FILES_ONLY.md',
    'DATABASE_SETUP.md',
    'cleanup-for-php.php', // Keep this script for reference
    'verify-php-setup.php'
];

// Directories to KEEP (will preserve directory structure)
$keepDirectories = [
    'components/php',
    'services',
    'config', 
    'database',
    'assets/css',
    'assets/js',
    'pages'
];

// Get all files recursively
function getAllFiles($dir, $basePath = '') {
    $files = [];
    if (is_dir($dir)) {
        $items = scandir($dir);
        foreach ($items as $item) {
            if ($item === '.' || $item === '..') continue;
            
            $fullPath = $dir . '/' . $item;
            $relativePath = $basePath ? $basePath . '/' . $item : $item;
            
            if (is_dir($fullPath)) {
                $files = array_merge($files, getAllFiles($fullPath, $relativePath));
            } else {
                $files[] = $relativePath;
            }
        }
    }
    return $files;
}

// Get all files in current directory
$allFiles = getAllFiles('.');

echo "Found " . count($allFiles) . " total files\n";
echo "Will keep " . count($keepFiles) . " essential files\n";
echo "New Bootstrap-based structure with separated page files\n\n";

echo "Files to KEEP:\n";
foreach ($keepFiles as $file) {
    echo "✅ $file\n";
}

echo "\nPress 'y' to proceed with cleanup, or any other key to cancel: ";
$input = trim(fgets(STDIN));

if (strtolower($input) !== 'y') {
    echo "❌ Cleanup cancelled.\n";
    exit;
}

echo "\n🧹 Starting cleanup...\n\n";

$deletedCount = 0;
$keptCount = 0;

foreach ($allFiles as $file) {
    if (in_array($file, $keepFiles)) {
        echo "✅ KEEP: $file\n";
        $keptCount++;
    } else {
        if (file_exists($file)) {
            if (unlink($file)) {
                echo "🗑️  DELETE: $file\n";
                $deletedCount++;
            } else {
                echo "❌ FAILED to delete: $file\n";
            }
        }
    }
}

// Remove empty directories
function removeEmptyDirs($dir) {
    if (!is_dir($dir)) return false;
    
    $items = scandir($dir);
    $hasItems = false;
    
    foreach ($items as $item) {
        if ($item === '.' || $item === '..') continue;
        
        $fullPath = $dir . '/' . $item;
        if (is_dir($fullPath)) {
            if (!removeEmptyDirs($fullPath)) {
                $hasItems = true;
            }
        } else {
            $hasItems = true;
        }
    }
    
    if (!$hasItems && $dir !== '.') {
        rmdir($dir);
        echo "📁 Removed empty directory: $dir\n";
        return true;
    }
    
    return false;
}

echo "\n🧹 Cleaning up empty directories...\n";
removeEmptyDirs('.');

echo "\n✨ Cleanup Complete!\n";
echo "==================\n";
echo "📊 Summary:\n";
echo "   • Kept: $keptCount files\n";
echo "   • Deleted: $deletedCount files\n";
echo "   • Reduction: " . round(($deletedCount / ($deletedCount + $keptCount)) * 100, 1) . "%\n\n";

echo "🚀 Ready to run!\n";
echo "   php -S localhost:8000\n\n";

echo "📖 Next steps:\n";
echo "   1. Read INTEGRATION_GUIDE.md\n";
echo "   2. Add API keys to config/app.php\n";
echo "   3. Implement backend integrations\n\n";

echo "🐱 meow! ur cat-AI is now clean and ready!\n";
?>