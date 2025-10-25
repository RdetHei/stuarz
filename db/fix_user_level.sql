-- Fix user level to support 'guru' level
-- Run this in phpMyAdmin or MySQL command line

USE stuarz;

-- Update users table to support guru level
ALTER TABLE users MODIFY COLUMN level enum('user','admin','guru') DEFAULT 'user';

-- Add subject_id column to tasks_completed if not exists
ALTER TABLE tasks_completed ADD COLUMN subject_id INT(11) DEFAULT NULL AFTER class_id;

-- Add index for better performance
ALTER TABLE tasks_completed ADD INDEX idx_tasks_completed_subject_id (subject_id);

-- Add foreign key constraint
ALTER TABLE tasks_completed ADD CONSTRAINT fk_tasks_completed_subject FOREIGN KEY (subject_id) REFERENCES subjects(id) ON DELETE SET NULL;

-- Verify changes
SELECT COUNT(*) as column_exists FROM information_schema.columns 
WHERE table_schema = DATABASE() 
AND table_name = 'users' 
AND column_name = 'level' 
AND column_type LIKE '%guru%';

SELECT COUNT(*) as subject_column_exists FROM information_schema.columns 
WHERE table_schema = DATABASE() 
AND table_name = 'tasks_completed' 
AND column_name = 'subject_id';

