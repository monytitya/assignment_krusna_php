# Student Management Form

## Features

✅ **Student Form with the following functionality:**

1. **Gender Field** - Displays "Male" for M and "Female" for F
2. **Date of Birth** - Converts format from YYYY-MM-DD to DD-MMM-YYYY (e.g., 2006-04-08 → 08-Apr-2006)
3. **Image Upload** - Generates unique filenames using timestamp and random number to prevent duplicates
4. **Auto-delete Images** - Old images are automatically deleted when:
   - A new image is uploaded to replace an existing one
   - Student record is deleted
5. **Student List** - Displays all students with filtering and action buttons
6. **Database Integration** - Connects to MySQL database on port 3308

## Setup Instructions

### Step 1: Create Database
1. Open phpMyAdmin (http://localhost:8080/phpmyadmin)
2. Import the `database.sql` file or run the SQL queries manually
3. Ensure database name is `Student_ass` and running on port 3308

### Step 2: Configure Database Connection
Edit `config.php` and update if needed:
```php
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASSWORD', '');
define('DB_NAME', 'Student_ass');
define('DB_PORT', 3308);
```

### Step 3: Create Upload Directory
The system will automatically create an `uploads/` directory, but you can create it manually:
```
Assignment_krusna/
├── uploads/
├── index.php
├── config.php
├── database.sql
└── README.md
```

### Step 4: Access the Application
Open your browser and navigate to:
```
http://localhost/Assignment_krusna/
```

## File Structure

- **index.php** - Main form and student list page
- **config.php** - Database configuration and constants
- **database.sql** - Database schema and sample data
- **uploads/** - Image storage directory (auto-created)

## Form Fields

| Field | Type | Description |
|-------|------|-------------|
| Name | Text | Student's full name (required) |
| Gender | Dropdown | Male or Female (required) |
| Date of Birth | Date | Date in format YYYY-MM-DD (required) |
| Phone | Phone | Contact number (required) |
| Address | Textarea | Student's address (required) |
| Image | File | Student photo (optional) |

## Functionality

### Adding a Student
1. Fill in all required fields
2. Select an image (optional)
3. Click the "ADD" button
4. Image will be saved with unique name: `[timestamp]_[random].jpg`

### Viewing Students
- The student list appears below the form
- Shows all information in formatted display
- Gender displays as "Male" or "Female"
- Date of Birth displays as "DD-MMM-YYYY"

### Deleting a Student
1. Click the "DELETE" button in the student row
2. Confirm the deletion
3. Student record and associated image will be deleted from both database and file system

### Image Management
- **Unique Naming**: Each image gets a unique name using timestamp + random number
- **No Duplicates**: If file already exists, a new unique name is generated
- **Auto-delete**: Old images are removed when:
  - Uploading a new image for a student
  - Deleting a student record
- **Size Limit**: Maximum 5MB per image
- **Allowed Formats**: JPG, JPEG, PNG, GIF

## Database Schema

### students Table
```sql
CREATE TABLE students (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    gender CHAR(1) NOT NULL,
    dob DATE NOT NULL,
    phone VARCHAR(20) NOT NULL,
    address TEXT NOT NULL,
    photo VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
)
```

## Important Notes

1. **Database Port**: Make sure MySQL is running on port 3308
2. **Uploads Folder**: Must have write permissions
3. **Image Validation**: Only validates extension and size
4. **Date Format**: Always input dates as YYYY-MM-DD in the form
5. **Gender Storage**: Stored as 'M' or 'F' in database, displayed as 'Male' or 'Female'

## Security Features

✅ SQL Injection Prevention - Using mysqli prepared statements (with real_escape_string)
✅ File Upload Validation - Extension and size checking
✅ XSS Prevention - Using htmlspecialchars() for output
✅ Delete Confirmation - Requires user confirmation before deletion
✅ Unique Image Naming - Prevents filename conflicts

## Future Enhancements

- Add Edit functionality (SELECT button)
- Add search/filter capabilities
- Add pagination
- Add input validation on client-side
- Add user authentication
- Add image compression
