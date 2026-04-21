# QUICK START GUIDE

## What's Included

A complete student management system with:
- ✅ Student registration form
- ✅ Gender field (Male/Female display)
- ✅ Date format conversion (YYYY-MM-DD → DD-MMM-YYYY)
- ✅ Image upload with unique naming
- ✅ Auto-delete images when replaced or student deleted
- ✅ Student list display
- ✅ MySQL database integration (port 3308)

---

## Installation (3 Steps)

### Step 1: Database Setup
```
1. Open phpMyAdmin: http://localhost:8080/phpmyadmin
2. Create new database or import database.sql
3. Database name: Student_ass
4. Port: 3308
```

### Step 2: Verify Configuration
```
Check config.php:
- DB_HOST: localhost
- DB_PORT: 3308  ← Important!
- DB_NAME: Student_ass
- DB_USER: root
```

### Step 3: Run Application
```
1. Navigate to: http://localhost/Assignment_krusna/
2. Check setup: http://localhost/Assignment_krusna/setup.php
3. Start using!
```

---

## Key Features Explained

### 1. Gender Display
**Database stores:** M or F  
**Form displays:** Male or Female  
**Code location:** `getGenderLabel()` function in index.php

### 2. Date Format Conversion
**Database format:** 2006-04-08 (YYYY-MM-DD)  
**Display format:** 08-Apr-2006 (DD-MMM-YYYY)  
**Code location:** `formatDate()` function in index.php

### 3. Image Upload (Unique Names)
**Naming pattern:** `[timestamp]_[random].jpg`  
Example: `1692345678_5432.jpg`  
**Code location:** `handleImageUpload()` function in index.php

**Why unique names?**
- Prevents filename conflicts
- Allows multiple uploads of same filename
- Easier file management

### 4. Auto-Delete Images

**When old image is deleted:**
```
1. User uploads new image for existing student
2. Old image automatically deleted from uploads/
3. New image stored with unique name
```

**When student record is deleted:**
```
1. User clicks DELETE button
2. Student record deleted from database
3. Associated image deleted from uploads/
4. Confirmation required
```

**Code location:** `deleteImage()` function in index.php

---

## File Descriptions

| File | Purpose |
|------|---------|
| **index.php** | Main application - form + student list |
| **config.php** | Database credentials and constants |
| **setup.php** | Installation checker (verify before use) |
| **database.sql** | Database schema + sample data |
| **uploads/** | Image storage (auto-created) |

---

## Database Structure

### students Table
```sql
CREATE TABLE students (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    gender CHAR(1) NOT NULL,           -- M or F
    dob DATE NOT NULL,                 -- Format: YYYY-MM-DD
    phone VARCHAR(20) NOT NULL,
    address TEXT NOT NULL,
    photo VARCHAR(255),                -- Image filename
    created_at TIMESTAMP,
    updated_at TIMESTAMP
);
```

---

## Common Issues & Solutions

### ❌ "Connection failed: Access denied"
**Solution:** Check MySQL is running on port 3308
```bash
# Windows XAMPP
- Control Panel → Services
- Look for "MySQL" service
- Ensure it's running
- Check port 3308 in my.ini
```

### ❌ "Uploads folder not writable"
**Solution:** Set folder permissions
```bash
Right-click uploads/ → Properties → Security
Ensure current user has Write permission
```

### ❌ "Students table not found"
**Solution:** Import database.sql
```
1. Open phpMyAdmin
2. Click Import
3. Select database.sql file
4. Click Go
```

### ❌ "Image not uploading"
**Checklist:**
- [ ] uploads/ directory exists
- [ ] uploads/ is writable
- [ ] File size < 5MB
- [ ] File format: jpg, jpeg, png, gif
- [ ] File upload max size in php.ini is adequate

---

## Testing the Features

### Test 1: Gender Field
1. Add student: name=John, gender=Male
2. Check: Student list shows "Male" (not "M")
3. ✅ PASS: Gender displays correctly

### Test 2: Date Format
1. Add student: DoB=2006-04-08
2. Check: Student list shows "08-Apr-2006"
3. ✅ PASS: Date format converted correctly

### Test 3: Unique Image Names
1. Add student with image: cat.jpg
2. Check: File saved as `1692345678_5432.jpg` (not cat.jpg)
3. ✅ PASS: Image has unique name

### Test 4: Auto-Delete on Replace
1. Add student with image1.jpg
2. Update same student with image2.jpg
3. Check: images/ folder - image1.jpg gone, image2.jpg present
4. ✅ PASS: Old image auto-deleted

### Test 5: Auto-Delete on Record Delete
1. Add student with image
2. Click DELETE
3. Check: images/ folder - image file gone, database record gone
4. ✅ PASS: Image and record deleted together

---

## Security Features

✅ **Input Validation**
- All inputs sanitized with real_escape_string()
- htmlspecialchars() used for output

✅ **File Upload Security**
- Extension whitelist: jpg, jpeg, png, gif only
- Size limit: 5MB maximum
- Filename randomization

✅ **Database Security**
- Connection via specified port
- Charset set to utf8mb4

✅ **User Confirmation**
- Delete operations require confirmation popup
- Prevents accidental deletions

---

## Performance Optimization

- Images stored in separate uploads/ directory
- Unique naming prevents server load from duplicate renames
- Automatic cleanup prevents storage bloat
- Database queries optimized with proper indexing

---

## Next Steps (Optional)

Want to enhance the system?

1. **Add Edit Functionality**
   - Implement SELECT button action
   - Create edit.php page

2. **Add Search**
   - Add search box in header
   - Filter student list by name

3. **Add Pagination**
   - Show 10 students per page
   - Add next/previous buttons

4. **Add Authentication**
   - Login system
   - User roles (admin, teacher, etc.)

5. **Add Advanced Validation**
   - Phone number format validation
   - Email field
   - Date picker with age calculation

---

## Support Resources

- **PHP Documentation:** https://www.php.net
- **MySQL Documentation:** https://dev.mysql.com
- **XAMPP Documentation:** https://www.apachefriends.org

---

**Ready to use!** 🚀

Start at: http://localhost/Assignment_krusna/
