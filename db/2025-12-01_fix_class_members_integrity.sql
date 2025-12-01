-- Migration: Fix class_members integrity and add unique constraint
-- Date: 2025-12-01
-- Purpose: Prevent duplicate joins and ensure data consistency

-- 1) Add UNIQUE constraint on (class_id, user_id) to prevent duplicate joins
ALTER TABLE class_members
ADD UNIQUE KEY ux_class_user (class_id, user_id);

-- 2) Ensure joined_at has default timestamp
ALTER TABLE class_members
MODIFY joined_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP;

-- 3) (Optional) Add status column to track member status
-- Uncomment if needed:
-- ALTER TABLE class_members
-- ADD COLUMN status ENUM('active','left') NOT NULL DEFAULT 'active' AFTER role;

-- 4) Verify schema
-- DESCRIBE class_members;
