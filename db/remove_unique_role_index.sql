-- Remove unique index on users.role to allow repeated role values
-- Usage: run this in phpMyAdmin or MySQL client against the 'stuarz' database

USE stuarz;

-- Find the index name on role (should return the actual index name if exists)
-- SELECT INDEX_NAME FROM information_schema.statistics
-- WHERE table_schema = DATABASE() AND table_name = 'users' AND column_name = 'role';

-- Attempt to drop common index names safely
-- If the index is named exactly 'role'
ALTER TABLE users DROP INDEX role;

-- If your index has a different name, uncomment and modify one of the lines below:
-- ALTER TABLE users DROP INDEX users_role_unique;
-- ALTER TABLE users DROP INDEX idx_role_unique;
-- ALTER TABLE users DROP INDEX role_unique;

-- Ensure role column allows duplicates and is nullable (optional but recommended)
ALTER TABLE users MODIFY COLUMN role VARCHAR(50) NULL;


