-- Migration: ensure classes have a unique code and class_members pivot exists
-- 1) Add `code` column to `classes` if missing (adapt to existing schema)
ALTER TABLE classes
  ADD COLUMN IF NOT EXISTS code VARCHAR(20) NOT NULL UNIQUE AFTER name;

-- 2) Create pivot table `class_members` (if not exists) - repository uses this name already
CREATE TABLE IF NOT EXISTS class_members (
  id INT AUTO_INCREMENT PRIMARY KEY,
  class_id INT NOT NULL,
  user_id INT NOT NULL,
  role ENUM('student','teacher','admin') DEFAULT 'student',
  joined_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP(),
  UNIQUE KEY unique_user_class (class_id, user_id),
  CONSTRAINT fk_cm_class FOREIGN KEY (class_id) REFERENCES classes(id) ON DELETE CASCADE,
  CONSTRAINT fk_cm_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Note: run these statements in your MySQL client if your schema differs. The project already includes tables named `classes` and `class_members`.
