-- ALTER helper: add `subject_id` to `tasks_completed`
-- This file contains commented instructions and the ALTER statements.
-- IMPORTANT: backup your DB before running ALTER statements.

/* Manual steps (recommended):
1) Open MySQL shell (via XAMPP shell or mysql client):
   mysql -u root -p
2) Select the application database (if not already):
   USE your_database_name;
3) Check if column exists:
   SELECT COUNT(*) FROM information_schema.columns WHERE table_schema = DATABASE() AND table_name = 'tasks_completed' AND column_name = 'subject_id';
   -- If result is 0, run the ALTERs below.
4) Run the ALTER statements below (one by one):
   ALTER TABLE tasks_completed ADD COLUMN subject_id INT(11) DEFAULT NULL AFTER class_id;
   ALTER TABLE tasks_completed ADD INDEX idx_tasks_completed_subject_id (subject_id);
   ALTER TABLE tasks_completed ADD CONSTRAINT fk_tasks_completed_subject FOREIGN KEY (subject_id) REFERENCES subjects(id) ON DELETE SET NULL;
5) Verify:
   SELECT COUNT(*) FROM information_schema.columns WHERE table_schema = DATABASE() AND table_name = 'tasks_completed' AND column_name = 'subject_id';
*/

-- If you prefer to run from PowerShell (will prompt for password):
-- mysql -u root -p -e "ALTER TABLE tasks_completed ADD COLUMN subject_id INT(11) DEFAULT NULL AFTER class_id;"
-- mysql -u root -p -e "ALTER TABLE tasks_completed ADD INDEX idx_tasks_completed_subject_id (subject_id);"
-- mysql -u root -p -e "ALTER TABLE tasks_completed ADD CONSTRAINT fk_tasks_completed_subject FOREIGN KEY (subject_id) REFERENCES subjects(id) ON DELETE SET NULL;"

-- Below are the ALTER statements (commented out). Remove the leading '--' to execute.
-- ALTER TABLE tasks_completed ADD COLUMN subject_id INT(11) DEFAULT NULL AFTER class_id;
-- ALTER TABLE tasks_completed ADD INDEX idx_tasks_completed_subject_id (subject_id);
-- ALTER TABLE tasks_completed ADD CONSTRAINT fk_tasks_completed_subject FOREIGN KEY (subject_id) REFERENCES subjects(id) ON DELETE SET NULL;
