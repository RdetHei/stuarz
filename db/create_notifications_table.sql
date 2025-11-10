-- Create notifications table
CREATE TABLE IF NOT EXISTS `notifications` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `type` ENUM('create', 'update', 'delete', 'info') NOT NULL DEFAULT 'info',
  `entity` VARCHAR(100) NOT NULL,
  `entity_id` INT(11) DEFAULT NULL,
  `user_id` INT(11) DEFAULT NULL,
  `message` TEXT NOT NULL,
  `url` VARCHAR(255) DEFAULT NULL,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_user` (`user_id`),
  KEY `idx_entity` (`entity`,`entity_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
