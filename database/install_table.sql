CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
ALTER TABLE `users` ADD `role_id` INT(11) NOT NULL AFTER `password`;
CREATE TABLE `roles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Add foreign key constraint to users table
ALTER TABLE `users`
  ADD CONSTRAINT `fk_user_role` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`);

-- This table defines the "things" in your system.
-- Each row represents a unique entity, like a user, a course, an instructor, or an enrollment.
CREATE TABLE `entities` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `entity_type` VARCHAR(50) NOT NULL COMMENT 'e.g., user, course, instructor, enrollment',
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  INDEX `idx_entity_type` (`entity_type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


-- This table defines all the possible attributes (properties) you can use.
-- This is like the "column names" in a traditional database.
CREATE TABLE `attributes` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `attribute_name` VARCHAR(100) NOT NULL,
  `attribute_datatype` VARCHAR(20) NOT NULL COMMENT 'e.g., varchar, text, int, datetime, decimal',
  `description` TEXT DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_attribute_name` (`attribute_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


-- This is the main table that stores all the data.
-- It connects an entity with an attribute and its value.
CREATE TABLE `eav_values` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `entity_id` INT UNSIGNED NOT NULL,
  `attribute_id` INT UNSIGNED NOT NULL,
  `value` TEXT NOT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_entity_attribute` (`entity_id`, `attribute_id`), -- Each entity can only have one value per attribute
  FOREIGN KEY (`entity_id`) REFERENCES `entities` (`id`) ON DELETE CASCADE,
  FOREIGN KEY (`attribute_id`) REFERENCES `attributes` (`id`) ON DELETE CASCADE,
  INDEX `idx_attribute_value` (`attribute_id`, `value`(255)) -- Index for searching by attribute and value
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


-- This table holds all global settings for the application.
-- It is a simple and efficient key-value store.
CREATE TABLE `options` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `option_name` VARCHAR(191) NOT NULL,
  `option_value` LONGTEXT NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_option_name` (`option_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

