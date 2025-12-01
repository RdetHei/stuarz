# Class Membership Bug Fix - Dokumentasi Lengkap

## Ringkasan Masalah

Sistem menampilkan user sebagai anggota di **semua kelas** setelah bergabung ke satu kelas, padahal database hanya menyimpan row yang benar di `class_members` untuk kelas yang sebenarnya.

### Root Cause
1. **Controller**: `joinForm()` menggunakan fallback yang salah atau query yang tidak benar
2. **Model**: `getAll()` dengan userId menggunakan INNER JOIN, tidak tersedia untuk "semua kelas"
3. **View**: Menggunakan `sessionUser.level` sebagai fallback untuk menentukan role, bukan query result
4. **Service**: ClassService belum ada (file tidak ditemukan)
5. **Database**: Tidak ada unique constraint pada (class_id, user_id), memungkinkan duplikasi

## Solusi Implementasi

### 1. SQL Migration
File: `db/2025-12-01_fix_class_members_integrity.sql`

```sql
-- Tambah UNIQUE constraint untuk mencegah duplikasi join
ALTER TABLE class_members
ADD UNIQUE KEY ux_class_user (class_id, user_id);

-- Pastikan joined_at memiliki default CURRENT_TIMESTAMP
ALTER TABLE class_members
MODIFY joined_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP;
```

**Cara apply:**
```bash
mysql -u root stuarz < db/2025-12-01_fix_class_members_integrity.sql
```

### 2. ClassService (PDO-based)
File: `app/services/ClassService.php`

Menyediakan operasi safe:
- `joinClass($userId, $classId, $role)` - idempotent, menggunakan ON DUPLICATE KEY UPDATE
- `leaveClass($userId, $classId)` - hapus membership
- `getMyClasses($userId)` - hanya kelas yang user ikuti
- `getAllClassesWithUserStatus($userId)` - **SEMUA kelas + is_joined boolean** (CRITICAL FIX)
- `isUserMember($userId, $classId)` - quick check

**Key Feature**: Semua method menggunakan prepared statements dan transaksi.

### 3. ClassModel Update
File: `app/model/ClassModel.php`

**Added method**: `getAllClassesWithUserStatus($userId)`

```php
// CRITICAL: LEFT JOIN dengan AND cm.user_id = ? di join condition
// Ini mencegah false positive; hanya baris yang relevan memiliki is_joined = 1
$sql = "SELECT
    c.*,
    CASE WHEN cm.user_id IS NOT NULL THEN 1 ELSE 0 END AS is_joined,
    cm.role AS member_role
FROM classes c
LEFT JOIN class_members cm ON cm.class_id = c.id AND cm.user_id = ?
ORDER BY c.id DESC";
```

### 4. ClassController Update
File: `app/controller/ClassController.php`

**Method `joinForm()`** sekarang:
1. Menggunakan `ClassService::getAllClassesWithUserStatus()` (if available)
2. Fallback ke `ClassModel::getAllClassesWithUserStatus()` jika service error
3. Mengirim **data lengkap dengan is_joined flag** ke view

### 5. View Update
File: `app/views/pages/classes/index.php`

**Logic perubahan role determination:**

SEBELUM (BUG):
```php
$role = $c['member_role'] ?? null;
if ($role === null && isset($c['created_by']) && ...) {
    $role = ($sessionUser['level'] ?? '') === 'admin' ? 'admin' : 'teacher'; // BUG!
}
if ($role === null) {
    $role = 'student'; // BUG! menandai user sebagai member
}
```

SESUDAH (FIXED):
```php
$is_joined = intval($c['is_joined'] ?? 0);
$role = $c['member_role'] ?? null; // dari database, bukan fallback

if ($role === null && isset($c['created_by']) && intval($c['created_by']) === intval($sessionUser['id'])) {
    $role = 'creator'; // hanya jika user adalah pembuat kelas
}

if ($role === null) {
    $role = 'not_joined'; // menampilkan status sebenarnya
}

// Di render: tampilkan "Not Joined" jika $is_joined === 0
if ($is_joined === 1 && $c['member_role']) {
    // Tampilkan member role yang sebenarnya dari DB
} elseif ($role === 'creator') {
    // Tampilkan creator badge
} else {
    // Tampilkan "Not Joined"
}
```

## Verifikasi & Testing

### SQL Verification Script
File: `db/verify_class_members_fix.sql`

Jalankan di MySQL:

```sql
-- 1. Cek struktur class_members
DESCRIBE class_members;

-- 2. Cek unique constraint
SELECT CONSTRAINT_NAME, COLUMN_NAME 
FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE 
WHERE TABLE_NAME = 'class_members' 
  AND CONSTRAINT_NAME = 'ux_class_user';

-- 3. Cek data: user 3 hanya ada di kelas mana?
SELECT class_id, user_id, role, joined_at 
FROM class_members 
WHERE user_id = 3;

-- 4. Test query: semua kelas + is_joined untuk user 3
SET @userId = 3;
SELECT 
  c.id, c.name, c.code,
  CASE WHEN cm.user_id IS NOT NULL THEN 1 ELSE 0 END AS is_joined,
  cm.role AS member_role
FROM classes c
LEFT JOIN class_members cm 
  ON cm.class_id = c.id 
  AND cm.user_id = @userId
ORDER BY c.id;
-- Harus menunjukkan: is_joined = 1 HANYA untuk class_id yang user 3 ikuti

-- 5. Test insert idempotent
-- Coba insert 2x user 3 ke class 7
INSERT INTO class_members (class_id, user_id, role, joined_at)
VALUES (7, 3, 'student', NOW())
ON DUPLICATE KEY UPDATE role = VALUES(role), joined_at = NOW();

-- Cek: hanya ada 1 row
SELECT COUNT(*) FROM class_members WHERE class_id = 7 AND user_id = 3;
-- Harus return 1, bukan 2
```

### Langkah Reproduksi untuk QA

#### Prasyarat
- User ID 3 login
- Setup: User 3 belum bergabung ke kelas apapun
- Siapkan 2-3 kelas di database (IDs: 7, 8, 9)

#### Test Case 1: User bergabung ke 1 kelas
```
1. Login sebagai user 3
2. Kunjungi halaman "Kelas Saya" (class list page)
3. Observasi: Harus ada form "Gabung Kelas"
4. Submit form dengan code kelas 7
5. Redirect ke detail kelas 7 ✓
6. Kunjungi ulang "Kelas Saya"
```

**Expected Result AFTER FIX:**
- List menampilkan **semua kelas** (7, 8, 9)
- Hanya **kelas 7** memiliki status "Joined as student"
- Kelas 8, 9 menampilkan status "Not Joined"
- User dapat klik "Masuk" pada kelas 8, 9 untuk join tambahan

**Expected Result BEFORE FIX (BUG):**
- Hanya kelas 7 ditampilkan di list (karena menggunakan INNER JOIN)
- Atau jika semua ditampilkan, semua menunjukkan "student" (fallback error)

#### Test Case 2: User join multiple classes
```
1. (Continue dari Test Case 1, user sudah di kelas 7)
2. Kunjungi "Kelas Saya"
3. Klik "Masuk" pada kelas 8
4. Kunjungi ulang "Kelas Saya"
```

**Expected Result:**
- Kelas 7: "Joined as student"
- Kelas 8: "Joined as student"
- Kelas 9: "Not Joined"

#### Test Case 3: Leave class
```
1. (Continue, user di kelas 7, 8)
2. Di halaman detail kelas 7, klik tombol "Leave Class"
3. Kunjungi "Kelas Saya"
```

**Expected Result:**
- Kelas 7: "Not Joined"
- Kelas 8: "Joined as student"
- Kelas 9: "Not Joined"

### PHPUnit Test Example

File: `tests/ClassServiceTest.php`

```php
<?php
use PHPUnit\Framework\TestCase;

class ClassServiceTest extends TestCase {
    private PDO $db;
    private ClassService $service;
    private int $testUserId = 9999;
    private int $testClassId1 = 8888;
    private int $testClassId2 = 7777;

    protected function setUp(): void {
        $this->db = new PDO('mysql:host=localhost;dbname=stuarz;charset=utf8mb4', 'root', '');
        $this->service = new ClassService($this->db);
        
        // Setup: create test user and classes
        // (assumes test data exists or uses fixtures)
    }

    public function testJoinClassCreatesCorrectMembership(): void {
        // Arrange: ensure user not in class
        $this->service->leaveClass($this->testUserId, $this->testClassId1);
        
        // Act
        $result = $this->service->joinClass($this->testUserId, $this->testClassId1, 'student');
        
        // Assert
        $this->assertTrue($result);
        $this->assertTrue($this->service->isUserMember($this->testUserId, $this->testClassId1));
        
        // Verify NOT in other class
        $this->assertFalse($this->service->isUserMember($this->testUserId, $this->testClassId2));
    }

    public function testGetAllClassesWithUserStatusShowsCorrectFlags(): void {
        // Setup
        $this->service->joinClass($this->testUserId, $this->testClassId1, 'student');
        $this->service->leaveClass($this->testUserId, $this->testClassId2);
        
        // Act
        $classes = $this->service->getAllClassesWithUserStatus($this->testUserId);
        
        // Assert: find classes and check is_joined
        $class1 = array_find($classes, fn($c) => $c['id'] == $this->testClassId1);
        $class2 = array_find($classes, fn($c) => $c['id'] == $this->testClassId2);
        
        $this->assertEquals(1, $class1['is_joined'], 'User should be joined to class 1');
        $this->assertEquals(0, $class2['is_joined'], 'User should NOT be joined to class 2');
        $this->assertEquals('student', $class1['member_role']);
        $this->assertNull($class2['member_role']);
    }

    public function testJoinClassIdempotent(): void {
        // Join once
        $this->service->joinClass($this->testUserId, $this->testClassId1, 'student');
        
        // Join again (should not error, should update)
        $result = $this->service->joinClass($this->testUserId, $this->testClassId1, 'teacher');
        
        // Assert
        $this->assertTrue($result);
        $role = $this->service->getUserRoleInClass($this->testUserId, $this->testClassId1);
        $this->assertEquals('teacher', $role, 'Role should be updated to teacher');
        
        // Verify still only 1 row in database
        $stmt = $this->db->prepare('SELECT COUNT(*) FROM class_members WHERE user_id = ? AND class_id = ?');
        $stmt->execute([$this->testUserId, $this->testClassId1]);
        $count = $stmt->fetchColumn();
        $this->assertEquals(1, $count, 'Should only have 1 membership row');
    }

    protected function tearDown(): void {
        // Cleanup test data
        $this->service->leaveClass($this->testUserId, $this->testClassId1);
        $this->service->leaveClass($this->testUserId, $this->testClassId2);
    }
}
?>
```

### Run Test

```bash
php vendor/bin/phpunit tests/ClassServiceTest.php
```

## API Response Examples

### POST /index.php?page=join_class (Join Class)

**Request:**
```json
{
  "class_code": "ABC123"
}
```

**Success Response (HTTP 200):**
```json
{
  "ok": true,
  "message": "Berhasil bergabung ke kelas.",
  "class_id": 7,
  "user_id": 3
}
```

**Error Response (HTTP 200, but ok=false):**
```json
{
  "ok": false,
  "message": "Kode kelas tidak valid atau kelas tidak ditemukan."
}
```

### GET /index.php?page=class (Show All Classes with Status)

**Response (implicit via view, can be JSON with ?ajax=1):**
```json
[
  {
    "id": 7,
    "name": "Algoritma Dasar",
    "code": "ABC123",
    "is_joined": 1,
    "member_role": "student",
    "joined_at": "2025-12-01 10:30:00",
    "members_count": 15
  },
  {
    "id": 8,
    "name": "Web Development",
    "code": "DEF456",
    "is_joined": 0,
    "member_role": null,
    "joined_at": null,
    "members_count": 20
  }
]
```

## Checklist Implementasi untuk Developer

- [x] Apply SQL migration untuk unique index
- [x] Implementasi ClassService dengan prepared statements
- [x] Update ClassModel::getAllClassesWithUserStatus()
- [x] Update ClassController::joinForm() untuk gunakan ClassService
- [x] Update view untuk tidak pakai sessionUser.level fallback
- [ ] Run SQL verification script dan pastikan output benar
- [ ] Run PHPUnit tests
- [ ] Manual QA: test case 1, 2, 3
- [ ] Monitor logs untuk exception di production
- [ ] Dokumentasi di wiki atau internal docs
- [ ] Commit & merge ke main branch

## Edge Cases & Security

### Race Condition Prevention
✓ **Solved** oleh UNIQUE constraint + ON DUPLICATE KEY UPDATE dalam transaksi

### Authorization Check
⚠️ **TODO**: Tambahkan check di controller
```php
// Optional: restrict join ke kelas private atau memerlukan approval
if ($class['is_private'] ?? false) {
    if (!$this->hasApproval($userId, $classId)) {
        throw new Exception('Class requires invitation');
    }
}
```

### Input Validation
✓ **Done**: Prepared statements digunakan di semua queries

### CSRF Protection
⚠️ **TODO**: Pastikan form menyertakan CSRF token

### Logging
⚠️ **TODO**: Enable error logging di ClassService untuk debugging

### Caching
⚠️ **Important**: Invalidate cache saat join/leave
```php
// Di controller
cache_delete('user_classes_' . $userId);
cache_delete('all_classes');
```

## Kesimpulan

Dengan fix ini:
1. ✓ Query menggunakan LEFT JOIN dengan AND cm.user_id = ? yang benar
2. ✓ is_joined diambil langsung dari database, bukan fallback
3. ✓ Unique constraint mencegah duplikasi
4. ✓ Prepared statements menjamin safety
5. ✓ View menampilkan status yang sesuai realitas

**Hasil**: User hanya ditampilkan sebagai "joined" di kelas yang benar-benar mereka ikuti.
