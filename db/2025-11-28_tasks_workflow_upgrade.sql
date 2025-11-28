-- ------------------------------------------------------------------
-- Tasks & grading workflow upgrade (2025-11-28)
-- ------------------------------------------------------------------
-- This migration introduces multi-step approval, rubric scoring, and
-- reminder capabilities for assignments and submissions.
-- Run sequentially after existing schema files.
-- ------------------------------------------------------------------

ALTER TABLE `tasks_completed`
    ADD COLUMN `approval_required` TINYINT(1) NOT NULL DEFAULT 0 AFTER `status`,
    ADD COLUMN `grading_rubric` LONGTEXT NULL AFTER `approval_required`,
    ADD COLUMN `max_attempts` INT NOT NULL DEFAULT 1 AFTER `grading_rubric`,
    ADD COLUMN `reminder_at` DATETIME NULL AFTER `max_attempts`,
    ADD COLUMN `reminder_sent_at` DATETIME NULL AFTER `reminder_at`,
    ADD COLUMN `allow_late` TINYINT(1) NOT NULL DEFAULT 0 AFTER `reminder_sent_at`,
    ADD COLUMN `late_deadline` DATETIME NULL AFTER `allow_late`,
    ADD COLUMN `workflow_state` ENUM('draft','published','in_review','closed') NOT NULL DEFAULT 'published' AFTER `late_deadline`;

ALTER TABLE `tasks_completed`
    ADD INDEX `idx_tasks_workflow_state` (`workflow_state`),
    ADD INDEX `idx_tasks_reminder_at` (`reminder_at`);

ALTER TABLE `task_submissions`
    ADD COLUMN `attempt_no` INT NOT NULL DEFAULT 1 AFTER `status`,
    ADD COLUMN `is_final` TINYINT(1) NOT NULL DEFAULT 0 AFTER `attempt_no`,
    ADD COLUMN `review_status` ENUM('pending','in_review','needs_revision','approved','graded') NOT NULL DEFAULT 'pending' AFTER `is_final`,
    ADD COLUMN `grade_breakdown` LONGTEXT NULL AFTER `grade`,
    ADD COLUMN `reviewed_by` INT NULL AFTER `feedback`,
    ADD COLUMN `reviewed_at` DATETIME NULL AFTER `reviewed_by`;

ALTER TABLE `task_submissions`
    ADD INDEX `idx_task_submissions_review_status` (`review_status`),
    ADD INDEX `idx_task_submissions_user` (`user_id`),
    ADD INDEX `idx_task_submissions_task_user` (`task_id`, `user_id`);

CREATE TABLE IF NOT EXISTS `task_reminders` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `task_id` INT NOT NULL,
    `user_id` INT NULL,
    `reminder_type` ENUM('deadline','revision','general') NOT NULL DEFAULT 'deadline',
    `message` TEXT,
    `sent_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `idx_task_reminders_task` (`task_id`),
    KEY `idx_task_reminders_user` (`user_id`),
    CONSTRAINT `fk_task_reminders_task`
        FOREIGN KEY (`task_id`) REFERENCES `tasks_completed` (`id`)
        ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

