-- SQL Verification Script for Class Membership Fix
-- Usage: mysql -u root stuarz < db/verify_class_members_fix.sql

-- ======================================
-- 1. Verify Schema Updates
-- ======================================
ECHO "=== 1. CLASS_MEMBERS TABLE STRUCTURE ===";
DESCRIBE class_members;

ECHO "\n=== 2. UNIQUE CONSTRAINT CHECK ===";
SELECT CONSTRAINT_NAME, COLUMN_NAME, REFERENCED_TABLE_NAME 
FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE 
WHERE TABLE_NAME = 'class_members' 
  AND TABLE_SCHEMA = 'stuarz'
  AND CONSTRAINT_NAME = 'ux_class_user';

-- ======================================
-- 2. Sample Data Query
-- ======================================
ECHO "\n=== 3. SAMPLE: Class Members for User ID 3 ===";
SELECT id, class_id, user_id, role, joined_at 
FROM class_members 
WHERE user_id = 3
ORDER BY class_id;

-- ======================================
-- 3. Critical Test: LEFT JOIN with Correct Condition
-- ======================================
ECHO "\n=== 4. CRITICAL TEST: All Classes with is_joined for User 3 ===";
ECHO "This query MUST show is_joined=1 ONLY for classes user 3 actually joined";
ECHO "If all classes show is_joined=1, the fix is NOT applied correctly";

SET @testUserId = 3;

SELECT 
  c.id,
  c.name,
  c.code,
  CASE WHEN cm.user_id IS NOT NULL THEN 1 ELSE 0 END AS is_joined,
  cm.role AS member_role,
  cm.joined_at
FROM classes c
LEFT JOIN class_members cm 
  ON cm.class_id = c.id 
  AND cm.user_id = @testUserId
ORDER BY c.id;

-- ======================================
-- 4. Verification: No Duplicate Memberships
-- ======================================
ECHO "\n=== 5. CHECK FOR DUPLICATE MEMBERSHIPS ===";
ECHO "Query should return 0 rows (no duplicates)";

SELECT class_id, user_id, COUNT(*) as cnt
FROM class_members
GROUP BY class_id, user_id
HAVING COUNT(*) > 1;

-- ======================================
-- 5. Statistics
-- ======================================
ECHO "\n=== 6. STATISTICS ===";
SELECT 
  COUNT(DISTINCT class_id) as total_classes,
  COUNT(DISTINCT user_id) as total_members,
  COUNT(*) as total_memberships,
  AVG(members_per_class) as avg_members_per_class
FROM (
  SELECT class_id, COUNT(*) as members_per_class
  FROM class_members
  GROUP BY class_id
) subq;

-- ======================================
-- 6. Test: Idempotent Join (ON DUPLICATE KEY UPDATE)
-- ======================================
ECHO "\n=== 7. TEST: Idempotent Join ===";
ECHO "Before: Count memberships for user 5, class 10";

SELECT COUNT(*) as before_count 
FROM class_members 
WHERE user_id = 5 AND class_id = 10;

ECHO "Insert twice (idempotent):";
-- Note: Adjust user_id/class_id if they don't exist in your test data
-- INSERT INTO class_members (class_id, user_id, role, joined_at)
-- VALUES (10, 5, 'student', NOW())
-- ON DUPLICATE KEY UPDATE role = VALUES(role), joined_at = NOW();

-- INSERT INTO class_members (class_id, user_id, role, joined_at)
-- VALUES (10, 5, 'teacher', NOW())
-- ON DUPLICATE KEY UPDATE role = VALUES(role), joined_at = NOW();

ECHO "After: Count should still be 1, and role should be 'teacher'";

SELECT COUNT(*) as after_count, role
FROM class_members 
WHERE user_id = 5 AND class_id = 10;

-- ======================================
-- 7. Performance Check
-- ======================================
ECHO "\n=== 8. QUERY PERFORMANCE CHECK ===";
ECHO "Ensure indexes are being used:";

EXPLAIN SELECT 
  c.id, c.name,
  CASE WHEN cm.user_id IS NOT NULL THEN 1 ELSE 0 END AS is_joined,
  cm.role AS member_role
FROM classes c
LEFT JOIN class_members cm 
  ON cm.class_id = c.id 
  AND cm.user_id = 3
ORDER BY c.id;

-- ======================================
-- Summary
-- ======================================
ECHO "\n=== VERIFICATION SUMMARY ===";
ECHO "✓ If unique constraint exists: PASS";
ECHO "✓ If LEFT JOIN query shows is_joined=1 only for actual members: PASS";
ECHO "✓ If no duplicates found: PASS";
ECHO "✓ If idempotent join works correctly: PASS";
ECHO "";
ECHO "If all above PASS, the fix is correctly applied!";
