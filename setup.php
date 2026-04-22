<?php



require_once 'config.php';

$setup_status = array(
    'database' => false,
    'uploads_dir' => false,
    'db_error' => '',
    'dir_error' => ''
);

try {
    if ($conn->connect_error) {
        $setup_status['db_error'] = "Connection failed: " . $conn->connect_error;
    } else {
        // Check if students table exists
        $result = $conn->query("SHOW TABLES LIKE 'students'");
        if ($result->num_rows > 0) {
            $setup_status['database'] = true;
        } else {
            $setup_status['db_error'] = "Students table not found. Please import database.sql";
        }
    }
} catch (Exception $e) {
    $setup_status['db_error'] = $e->getMessage();
}

if (!file_exists(UPLOAD_DIR)) {
    if (mkdir(UPLOAD_DIR, 0777, true)) {
        $setup_status['uploads_dir'] = true;
    } else {
        $setup_status['dir_error'] = "Failed to create uploads directory";
    }
} else {
    if (is_writable(UPLOAD_DIR)) {
        $setup_status['uploads_dir'] = true;
    } else {
        $setup_status['dir_error'] = "Uploads directory exists but is not writable";
    }
}

?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Setup - Student Management</title>
    <style>
    body {
        font-family: Arial, sans-serif;
        max-width: 800px;
        margin: 50px auto;
        padding: 20px;
    }

    .status {
        padding: 15px;
        margin: 10px 0;
        border-radius: 4px;
    }

    .success {
        background: #d4edda;
        color: #155724;
        border: 1px solid #c3e6cb;
    }

    .error {
        background: #f8d7da;
        color: #721c24;
        border: 1px solid #f5c6cb;
    }

    h1 {
        color: #333;
    }

    .check-item {
        padding: 10px;
        margin: 10px 0;
    }

    .check-item::before {
        content: "✓ ";
        color: green;
        font-weight: bold;
        margin-right: 5px;
    }

    .check-item.error::before {
        content: "✗ ";
        color: red;
    }

    .button-group {
        margin-top: 20px;
    }

    a {
        display: inline-block;
        padding: 10px 20px;
        margin: 5px;
        background: #27ae60;
        color: white;
        text-decoration: none;
        border-radius: 4px;
    }

    a:hover {
        background: #229954;
    }
    </style>
</head>

<body>
    <h1>📋 Setup Status - Student Management System</h1>

    <div class="check-item <?php echo $setup_status['database'] ? 'success' : 'error'; ?>">
        <strong>Database Connection:</strong>
        <?php if ($setup_status['database']): ?>
        ✓ Connected successfully to 'Student_ass' database
        <?php else: ?>
        ✗ <?php echo $setup_status['db_error']; ?>
        <?php endif; ?>
    </div>

    <div class="check-item <?php echo $setup_status['uploads_dir'] ? 'success' : 'error'; ?>">
        <strong>Uploads Directory:</strong>
        <?php if ($setup_status['uploads_dir']): ?>
        ✓ Upload directory is ready and writable
        <?php else: ?>
        ✗ <?php echo $setup_status['dir_error']; ?>
        <?php endif; ?>
    </div>

    <div style="margin-top: 30px; padding: 20px; background: #f5f5f5; border-radius: 4px;">
        <h3>📝 Setup Instructions:</h3>
        <ol>
            <li>Import <strong>database.sql</strong> to your MySQL database (port 3308)</li>
            <li>Verify database connection in <strong>config.php</strong></li>
            <li>Ensure upload directory has write permissions</li>
            <li>Once all checks pass, go to the application</li>
        </ol>
    </div>

    <div class="button-group">
        <?php if ($setup_status['database'] && $setup_status['uploads_dir']): ?>
        <a href="index.php" style="background: #27ae60;">✓ Go to Application</a>
        <?php else: ?>
        <a href="setup.php" style="background: #3498db;">🔄 Refresh</a>
        <?php endif; ?>
    </div>

    <div style="margin-top: 30px; font-size: 12px; color: #666;">
        <p><strong>Database Configuration:</strong></p>
        <ul>
            <li>Host: <?php echo DB_HOST; ?></li>
            <li>Port: <?php echo DB_PORT; ?></li>
            <li>Database: <?php echo DB_NAME; ?></li>
            <li>Upload Directory: <?php echo UPLOAD_DIR; ?></li>
        </ul>
    </div>
</body>

</html>