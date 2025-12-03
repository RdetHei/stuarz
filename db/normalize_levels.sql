-- normalize_levels.sql
-- Migration script to normalize role/level strings to 'guru' and 'user'
-- IMPORTANT: Backup your database before running this script.

-- 1) Backup
-- CREATE DATABASE stuarz_backup; -- do this manually or use mysqldump

-- 2) Alter enum columns to accept new values (adjust column definitions to match your schema)
ALTER TABLE users MODIFY `level` ENUM('user','guru','admin') NOT NULL DEFAULT 'user';

-- If class_members.role is enum('student','teacher','admin'), alter it too:
ALTER TABLE class_members MODIFY `role` ENUM('user','guru','admin') NOT NULL DEFAULT 'user';

-- 3) Update existing data values
UPDATE users SET `level` = 'guru' WHERE `level` = 'teacher';
UPDATE users SET `level` = 'user' WHERE `level` = 'student';

UPDATE class_members SET `role` = 'guru' WHERE `role` = 'teacher';
UPDATE class_members SET `role` = 'user' WHERE `role` = 'student';

-- 4) Optional: cleanup any other tables that may store legacy strings (search first then run)
-- Example: if you have other columns named `role` in other tables, update them similarly.

-- 5) Verify counts
SELECT `level`, COUNT(*) as cnt FROM users GROUP BY `level`;
SELECT `role`, COUNT(*) as cnt FROM class_members GROUP BY `role`;

-- End of script
