CREATE TABLE `users` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `email` varchar(255) UNIQUE,
  `password_hash` varchar(255),
  `role` varchar(255) DEFAULT 'user',
  `failed_login_attempts` integer DEFAULT 0,
  `created_at` timestamp DEFAULT (now()),
  `updated_at` timestamp DEFAULT (now())
);

CREATE TABLE `admins` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `email` varchar(255) UNIQUE NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `role` varchar(255) DEFAULT 'admin',
  `failed_login_attempts` integer DEFAULT 0,
  `created_at` timestamp DEFAULT (now()),
  `updated_at` timestamp DEFAULT (now())
);

CREATE TABLE `categories` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `name` varchar(255) UNIQUE NOT NULL,
  `description` text,
  `color` varchar(255) NOT NULL COMMENT 'hex color code #RRGGBB',
  `icon` varchar(255) NOT NULL COMMENT 'icon class or name',
  `created_by` int NOT NULL,
  `sort_order` int DEFAULT 0,
  `created_at` timestamp DEFAULT (now()),
  `updated_at` timestamp DEFAULT (now())
);

CREATE TABLE `events` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `description` text,
  `start_datetime` timestamp NOT NULL,
  `end_datetime` timestamp,
  `location` varchar(255) NOT NULL,
  `area` varchar(255) NOT NULL COMMENT 'Toul kok, BKK, Sen Sok, Factory, Phnom Penh district',
  `event_type` varchar(255) NOT NULL COMMENT 'concert, job_fair, late_night, festival, charity',
  `category_id` int NOT NULL,
  `is_free` boolean DEFAULT true,
  `price` numeric COMMENT 'required if is_free = false',
  `approved_by` int,
  `approval_status` varchar(255) DEFAULT 'pending' COMMENT 'pending, approved, rejected',
  `created_at` timestamp DEFAULT (now()),
  `updated_at` timestamp DEFAULT (now())
);

CREATE TABLE `event_requests` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `description` text,
  `start_datetime` timestamp NOT NULL,
  `end_datetime` timestamp,
  `location` varchar(255) NOT NULL,
  `area` varchar(255) NOT NULL,
  `event_type` varchar(255) NOT NULL,
  `category_id` int NOT NULL,
  `is_free` boolean DEFAULT true,
  `price` numeric,
  `requester_email` varchar(255) NOT NULL,
  `requester_phone` varchar(255) NOT NULL,
  `reference_link` varchar(255),
  `requested_by` int,
  `approved_by` int,
  `approval_status` varchar(255) DEFAULT 'pending',
  `created_at` timestamp DEFAULT (now()),
  `updated_at` timestamp DEFAULT (now())
);

CREATE TABLE `saved_events` (
  `user_id` int NOT NULL,
  `event_id` int NOT NULL,
  `saved_at` timestamp DEFAULT (now()),
  PRIMARY KEY (`user_id`, `event_id`)
);

CREATE TABLE `admin_operations` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `admin_id` int NOT NULL,
  `operation_type` varchar(255) NOT NULL COMMENT 'event_approval, user_management, category_management, system_config',
  `target_table` varchar(255) NOT NULL COMMENT 'events, event_requests, categories, users',
  `target_id` int,
  `details` text,
  `performed_at` timestamp DEFAULT (now())
);

CREATE TABLE `user_overview` (
  `active_users` int DEFAULT 0,
  `total_users` int DEFAULT 0,
  `totalRegister_users` int DEFAULT 0,
  `new_users` int DEFAULT 0
);

CREATE TABLE `post_overview` (
  `totalEvent_posted` int DEFAULT 0,
  `totalProposal_event` int DEFAULT 0,
  `totalAccepted_event` int DEFAULT 0
);

ALTER TABLE `categories` ADD FOREIGN KEY (`created_by`) REFERENCES `admins` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE;

ALTER TABLE `events` ADD FOREIGN KEY (`approved_by`) REFERENCES `admins` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

ALTER TABLE `event_requests` ADD FOREIGN KEY (`approved_by`) REFERENCES `admins` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

ALTER TABLE `admin_operations` ADD FOREIGN KEY (`admin_id`) REFERENCES `admins` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE;

ALTER TABLE `events` ADD FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE;

ALTER TABLE `event_requests` ADD FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE;

ALTER TABLE `saved_events` ADD FOREIGN KEY (`event_id`) REFERENCES `events` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `saved_events` ADD FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `event_requests` ADD FOREIGN KEY (`requested_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

ALTER DATABASE your_database_name CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
