<?php
// Include the baseInfo.php file
require_once __DIR__ . '/../wizwizxui-timebot/baseInfo.php';

// Escape database credentials with special characters
$DB_NAME = escapeshellarg($dbName);
$DB_USER = escapeshellarg($dbUserName);
$DB_PASS = escapeshellarg($dbPassword);

// Set up database host
$DB_HOST = "localhost";

// Set up backup file path
$BACKUP_DIR = "/tmp";
$BACKUP_FILE = "$BACKUP_DIR/db_backup_" . date("Y-m-d_H-i-s") . ".sql";

// Set up Telegram bot API token and chat ID
$BOT_API_TOKEN = $botToken;
$CHAT_ID = $admin;

// Create database backup
$command = "mysqldump -h $DB_HOST -u $DB_USER -p$DB_PASS $DB_NAME > $BACKUP_FILE 2>&1";
$output = [];
$return_code = 0;
exec($command, $output, $return_code);

if ($return_code !== 0) {
    $error_message = "Error creating database backup:\n" . implode("\n", $output);
    echo $error_message;
    error_log($error_message);
    exit(1);
}

// Get the size of the backup file
$file_size = filesize($BACKUP_FILE);

// Check if the file size exceeds the Telegram API limit
$telegram_api_limit = 20 * 1024 * 1024; // 20 MB

// Compress the backup file if it exceeds the limit
$compressed_file = null;
if ($file_size > $telegram_api_limit) {
    $compressed_file = "$BACKUP_FILE.gz";
    $command = "gzip $BACKUP_FILE";
    exec($command, $output, $return_code);

    if ($return_code !== 0) {
        $error_message = "Error compressing backup file: " . implode("\n", $output);
        echo $error_message;
        error_log($error_message);
        unlink($BACKUP_FILE);
        exit(1);
    }
}

// Send the backup file to Telegram
$url = "https://api.telegram.org/bot$BOT_API_TOKEN/sendDocument";
$post_fields = [
    'chat_id' => $CHAT_ID,
    'document' => new CURLFile($compressed_file ?: $BACKUP_FILE)
];

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $post_fields);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
$response = curl_exec($ch);

if (curl_errno($ch)) {
    $error_message = "cURL Error: " . curl_error($ch);
    echo $error_message;
    error_log($error_message);
} else {
    $response_data = json_decode($response, true);
    if ($response_data['ok']) {
        echo "Database backup sent to Telegram successfully.";
    } else {
        $error_message = "Failed to send database backup to Telegram. Error: " . $response_data['description'];
        echo $error_message;
        error_log($error_message);
    }
}

curl_close($ch);

// Remove the local backup files
unlink($compressed_file ?: $BACKUP_FILE);
if ($compressed_file) {
    unlink($BACKUP_FILE);
}
?>