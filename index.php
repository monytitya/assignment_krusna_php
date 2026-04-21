<?php
session_start();
require_once 'config.php';

$message = '';
$messageType = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'add') {
    $name = $conn->real_escape_string($_POST['name']);
    $gender = $conn->real_escape_string($_POST['gender']);
    $dob = $conn->real_escape_string($_POST['dob']);
    $phone = $conn->real_escape_string($_POST['phone']);
    $address = $conn->real_escape_string($_POST['address']);
    $photo = '';

    if (empty($name) || empty($gender) || empty($dob) || empty($phone) || empty($address)) {
        $message = "All fields are required!";
        $messageType = "error";
    } else {
        if (!empty($_FILES['image']['name'])) {
            $upload_result = handleImageUpload($_FILES['image']);
            if ($upload_result['success']) {
                $photo = $upload_result['filename'];
            } else {
                $message = $upload_result['error'];
                $messageType = "error";
            }
        }

        if (empty($message)) {
            $sql = "INSERT INTO students (name, gender, dob, phone, address, photo) 
                    VALUES ('$name', '$gender', '$dob', '$phone', '$address', '$photo')";

            if ($conn->query($sql) === TRUE) {
                $message = "Student added successfully!";
                $messageType = "success";
            } else {
                $message = "Error: " . $conn->error;
                $messageType = "error";
            }
        }
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['action']) && $_GET['action'] == 'delete') {
    $id = intval($_GET['id']);

    $sql = "SELECT photo FROM students WHERE id = $id";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        if (!empty($row['photo'])) {
            deleteImage($row['photo']);
        }

        $delete_sql = "DELETE FROM students WHERE id = $id";
        if ($conn->query($delete_sql) === TRUE) {
            $message = "Student deleted successfully!";
            $messageType = "success";
        } else {
            $message = "Error deleting student: " . $conn->error;
            $messageType = "error";
        }
    } else {
        $message = "Student not found!";
        $messageType = "error";
    }
}

function handleImageUpload($file)
{
    if (!file_exists(UPLOAD_DIR)) {
        mkdir(UPLOAD_DIR, 0777, true);
    }

    $allowed_extensions = array('jpg', 'jpeg', 'png', 'gif');
    $file_ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

    if (!in_array($file_ext, $allowed_extensions)) {
        return array('success' => false, 'error' => 'Only JPG, JPEG, PNG, and GIF files are allowed!');
    }

    if ($file['size'] > 5 * 1024 * 1024) {
        return array('success' => false, 'error' => 'File size must be less than 5MB!');
    }

    $unique_filename = time() . '_' . rand(1000, 9999) . '.' . $file_ext;
    $target_file = UPLOAD_DIR . $unique_filename;

    if (move_uploaded_file($file['tmp_name'], $target_file)) {
        return array('success' => true, 'filename' => $unique_filename);
    } else {
        return array('success' => false, 'error' => 'Failed to upload image!');
    }
}
function deleteImage($filename)
{
    if (!empty($filename)) {
        $file_path = UPLOAD_DIR . $filename;
        if (file_exists($file_path)) {
            unlink($file_path);
        }
    }
}

function formatDate($date)
{
    if (empty($date)) return '';
    $date_obj = DateTime::createFromFormat('Y-m-d', $date);
    if ($date_obj) {
        return $date_obj->format('d-M-Y');
    }
    return $date;
}

function getGenderLabel($gender)
{
    if ($gender == 'M') {
        return 'Male';
    } elseif ($gender == 'F') {
        return 'Female';
    }
    return $gender;
}

$students = array();
$sql = "SELECT * FROM students ORDER BY id DESC";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $students = $result->fetch_all(MYSQLI_ASSOC);
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Management Form</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f5f5;
            padding: 20px;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        h1 {
            color: #333;
            margin-bottom: 30px;
            text-align: center;
        }

        h2 {
            color: #333;
            margin-top: 30px;
            margin-bottom: 20px;
            border-bottom: 2px solid #27ae60;
            padding-bottom: 10px;
        }

        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-bottom: 20px;
        }

        .form-group {
            display: flex;
            flex-direction: column;
        }

        .form-group label {
            font-weight: 600;
            margin-bottom: 8px;
            color: #333;
        }

        .form-group input,
        .form-group select,
        .form-group textarea {
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
            font-family: inherit;
        }

        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: #27ae60;
            box-shadow: 0 0 5px rgba(39, 174, 96, 0.3);
        }

        .form-group textarea {
            resize: vertical;
            min-height: 60px;
        }

        .form-group-full {
            grid-column: 1 / -1;
        }

        .image-section {
            display: grid;
            grid-template-columns: 200px 1fr;
            gap: 20px;
            align-items: start;
            grid-column: 1 / -1;
            padding: 20px;
            background: #f9f9f9;
            border-radius: 4px;
        }

        .image-preview {
            display: flex;
            justify-content: center;
            align-items: center;
            width: 200px;
            height: 200px;
            background: #e0e0e0;
            border-radius: 4px;
            overflow: hidden;
        }

        .image-preview img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .image-preview.empty {
            display: flex;
            justify-content: center;
            align-items: center;
            color: #999;
            font-size: 12px;
            text-align: center;
        }

        .image-upload {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .file-input-wrapper {
            position: relative;
            overflow: hidden;
            display: inline-block;
        }

        .file-input-wrapper input[type=file] {
            position: absolute;
            left: -9999px;
        }

        .file-input-label {
            display: inline-block;
            padding: 10px 20px;
            background: #27ae60;
            color: white;
            border-radius: 4px;
            cursor: pointer;
            font-weight: 600;
            transition: background 0.3s;
        }

        .file-input-label:hover {
            background: #229954;
        }

        .file-name {
            padding: 8px;
            background: white;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 13px;
            color: #666;
        }

        .button-group {
            display: flex;
            gap: 10px;
            grid-column: 1 / -1;
        }

        button {
            padding: 12px 24px;
            font-size: 14px;
            font-weight: 600;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: all 0.3s;
        }

        .btn-primary {
            background: #27ae60;
            color: white;
            flex: 1;
        }

        .btn-primary:hover {
            background: #229954;
        }

        .btn-secondary {
            background: #95a5a6;
            color: white;
        }

        .btn-secondary:hover {
            background: #7f8c8d;
        }

        .btn-select {
            background: #3498db;
            color: white;
            padding: 8px 16px;
            font-size: 12px;
        }

        .btn-select:hover {
            background: #2980b9;
        }

        .btn-delete {
            background: #e74c3c;
            color: white;
            padding: 8px 16px;
            font-size: 12px;
        }

        .btn-delete:hover {
            background: #c0392b;
        }

        .message {
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 4px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .message.success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .message.error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        .table-container {
            overflow-x: auto;
            margin-top: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        table thead {
            background: #34495e;
            color: white;
        }

        table th {
            padding: 12px;
            text-align: left;
            font-weight: 600;
        }

        table td {
            padding: 12px;
            border-bottom: 1px solid #ddd;
        }

        table tbody tr:hover {
            background: #f9f9f9;
        }

        .student-photo {
            width: 50px;
            height: 50px;
            border-radius: 4px;
            object-fit: cover;
        }

        .student-photo.placeholder {
            background: #e0e0e0;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #999;
            font-size: 12px;
        }

        .action-buttons {
            display: flex;
            gap: 5px;
        }

        .no-data {
            text-align: center;
            padding: 40px;
            color: #999;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>Form Student</h1>

        <?php if (!empty($message)): ?>
            <div class="message <?php echo $messageType; ?>">
                <?php echo htmlspecialchars($message); ?>
            </div>
        <?php endif; ?>

        <form method="POST" enctype="multipart/form-data">
            <input type="hidden" name="action" value="add">

            <div class="form-grid">
                <div class="form-group">
                    <label for="name">Name:</label>
                    <input type="text" id="name" name="name" required>
                </div>

                <div class="form-group">
                    <label for="gender">Gender:</label>
                    <select id="gender" name="gender" required>
                        <option value="">Select Gender</option>
                        <option value="M">Male</option>
                        <option value="F">Female</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="dob">Date of Birth:</label>
                    <input type="date" id="dob" name="dob" required>
                </div>

                <div class="form-group">
                    <label for="phone">Phone:</label>
                    <input type="tel" id="phone" name="phone" required>
                </div>

                <div class="form-group form-group-full">
                    <label for="address">Address:</label>
                    <textarea id="address" name="address" required></textarea>
                </div>

                <div class="image-section">
                    <div class="image-preview empty" id="preview">
                        No file chosen
                    </div>
                    <div class="image-upload">
                        <label>Image:</label>
                        <div class="file-input-wrapper">
                            <input type="file" id="image" name="image" accept="image/*" onchange="previewImage(event)">
                            <label for="image" class="file-input-label">Choose File</label>
                        </div>
                        <div class="file-name" id="fileName">No file chosen</div>
                    </div>
                </div>

                <div class="button-group">
                    <button type="submit" class="btn-primary">ADD</button>
                    <button type="reset" class="btn-secondary">RESET</button>
                </div>
            </div>
        </form>

        <h2>Student List</h2>

        <div class="table-container">
            <?php if (count($students) > 0): ?>
                <table>
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Name</th>
                            <th>Gender</th>
                            <th>DoB</th>
                            <th>Phone</th>
                            <th>Address</th>
                            <th>Photo</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $counter = 1;
                        foreach ($students as $student):
                        ?>
                            <tr>
                                <td><?php echo $counter++; ?></td>
                                <td><?php echo htmlspecialchars($student['name']); ?></td>
                                <td><?php echo getGenderLabel($student['gender']); ?></td>
                                <td><?php echo formatDate($student['dob']); ?></td>
                                <td><?php echo htmlspecialchars($student['phone']); ?></td>
                                <td><?php echo htmlspecialchars($student['address']); ?></td>
                                <td>
                                    <?php if (!empty($student['photo'])): ?>
                                        <img src="uploads/<?php echo htmlspecialchars($student['photo']); ?>" alt="Student Photo" class="student-photo">
                                    <?php else: ?>
                                        <div class="student-photo placeholder">No image</div>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div class="action-buttons">
                                        <button type="button" class="btn-select" onclick="selectStudent(<?php echo $student['id']; ?>)">SELECT</button>
                                        <a href="?action=delete&id=<?php echo $student['id']; ?>" class="btn-delete" onclick="return confirm('Are you sure you want to delete this student?');">DELETE</a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <div class="no-data">No students found. Add a new student to get started.</div>
            <?php endif; ?>
        </div>
    </div>

    <script>
        function previewImage(event) {
            const file = event.target.files[0];
            const preview = document.getElementById('preview');
            const fileName = document.getElementById('fileName');

            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.innerHTML = '<img src="' + e.target.result + '" alt="Preview">';
                };
                reader.readAsDataURL(file);
                fileName.textContent = file.name;
            } else {
                preview.innerHTML = 'No file chosen';
                fileName.textContent = 'No file chosen';
            }
        }

        function selectStudent(id) {
            alert('SELECT functionality can be used to edit student. You can implement edit form.');
        }
    </script>
</body>

</html>