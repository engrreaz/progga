-- New tables for AI-Powered Personalized Learning System

CREATE TABLE IF NOT EXISTS `diagnosis_results` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `sccode` INT NOT NULL,
  `stid` VARCHAR(20) NOT NULL,
  `topic_code` VARCHAR(50) NOT NULL,
  `score` DECIMAL(5,2) NOT NULL,
  `max_score` DECIMAL(5,2) DEFAULT NULL,
  `level` ENUM('weak','average','strong') NOT NULL,
  `attempted_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `details` TEXT DEFAULT NULL,
  INDEX(`sccode`,`stid`),
  INDEX(`topic_code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE IF NOT EXISTS `learning_paths` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `sccode` INT NOT NULL,
  `stid` VARCHAR(20) NOT NULL,
  `topic_code` VARCHAR(50) NOT NULL,
  `suggested_resource` VARCHAR(255) NOT NULL,
  `resource_type` ENUM('video','pdf','quiz','text','interactive') DEFAULT 'pdf',
  `status` ENUM('pending','in_progress','completed') NOT NULL DEFAULT 'pending',
  `assigned_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `completed_at` DATETIME DEFAULT NULL,
  `remarks` TEXT DEFAULT NULL,
  INDEX(`sccode`,`stid`),
  INDEX(`topic_code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE IF NOT EXISTS `syllabus_topics` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `class` VARCHAR(10) NOT NULL,
  `topic_code` VARCHAR(50) NOT NULL,
  `topic_name` VARCHAR(150) NOT NULL,
  `parent_topic` VARCHAR(50) DEFAULT NULL,
  `sequence_order` INT DEFAULT 0,
  UNIQUE KEY(`class`,`topic_code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE IF NOT EXISTS `syllabus_progress` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `sccode` INT NOT NULL,
  `stid` VARCHAR(20) NOT NULL,
  `class` VARCHAR(10) NOT NULL,
  `topic_code` VARCHAR(50) NOT NULL,
  `is_completed` TINYINT(1) NOT NULL DEFAULT 0,
  `completed_at` DATETIME DEFAULT NULL,
  INDEX(`sccode`,`stid`),
  INDEX(`class`),
  INDEX(`topic_code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE IF NOT EXISTS `feedback_logs` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `sccode` INT NOT NULL,
  `stid` VARCHAR(20) NOT NULL,
  `quiz_id` INT NOT NULL,
  `topic_code` VARCHAR(50) DEFAULT NULL,
  `score` DECIMAL(5,2) NOT NULL,
  `max_score` DECIMAL(5,2) DEFAULT NULL,
  `feedback_text` TEXT DEFAULT NULL,
  `provided_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  INDEX(`sccode`,`stid`),
  INDEX(`quiz_id`),
  INDEX(`topic_code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
