-- ============================================================
-- JSPS Accounting Solutions — Database Schema
-- Run this once to initialise the database
-- ============================================================

CREATE DATABASE IF NOT EXISTS jsps_db
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE jsps_db;

-- ── Users ─────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS users (
    id            INT UNSIGNED     NOT NULL AUTO_INCREMENT,
    first_name    VARCHAR(80)      NOT NULL,
    last_name     VARCHAR(80)      NOT NULL,
    email         VARCHAR(180)     NOT NULL UNIQUE,
    password_hash VARCHAR(255)     NOT NULL,
    role          ENUM('admin','user') NOT NULL DEFAULT 'user',
    is_active     TINYINT(1)       NOT NULL DEFAULT 1,
    created_at    DATETIME         NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at    DATETIME         NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    INDEX idx_email (email),
    INDEX idx_role  (role)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Default admin — password: Admin@123
-- Hash generated with: password_hash('Admin@123', PASSWORD_BCRYPT, ['cost'=>12])
INSERT INTO users (first_name, last_name, email, password_hash, role) VALUES
('Admin', 'JSPS', 'admin@jspsaccounting.com',
 '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin');

-- ── Blog Articles ─────────────────────────────────────────
CREATE TABLE IF NOT EXISTS articles (
    id            INT UNSIGNED     NOT NULL AUTO_INCREMENT,
    user_id       INT UNSIGNED     NOT NULL,
    title         VARCHAR(255)     NOT NULL,
    slug          VARCHAR(280)     NOT NULL UNIQUE,
    category      VARCHAR(100)     NOT NULL,
    excerpt       TEXT             NOT NULL,
    content       LONGTEXT         NOT NULL,
    tags          VARCHAR(500)     DEFAULT NULL,
    emoji         VARCHAR(10)      DEFAULT '📰',
    status        ENUM('draft','published') NOT NULL DEFAULT 'published',
    created_at    DATETIME         NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at    DATETIME         NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    INDEX idx_user      (user_id),
    INDEX idx_status    (status),
    INDEX idx_category  (category),
    FULLTEXT INDEX ft_search (title, content),
    CONSTRAINT fk_article_user FOREIGN KEY (user_id)
        REFERENCES users (id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ── Contact Submissions ───────────────────────────────────
CREATE TABLE IF NOT EXISTS contact_submissions (
    id            INT UNSIGNED     NOT NULL AUTO_INCREMENT,
    user_id       INT UNSIGNED     DEFAULT NULL,
    full_name     VARCHAR(160)     NOT NULL,
    email         VARCHAR(180)     NOT NULL,
    phone         VARCHAR(20)      DEFAULT NULL,
    service       VARCHAR(120)     DEFAULT NULL,
    message       TEXT             NOT NULL,
    is_read       TINYINT(1)       NOT NULL DEFAULT 0,
    created_at    DATETIME         NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    INDEX idx_user    (user_id),
    INDEX idx_is_read (is_read),
    CONSTRAINT fk_contact_user FOREIGN KEY (user_id)
        REFERENCES users (id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ── Sample Data ───────────────────────────────────────────
-- (Insert only after running the PHP seed script for proper password hashing)
