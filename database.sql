-- ============================================================
--  BUSH MINDS TOURS & TRAVEL — DATABASE SETUP
--  Run this in phpMyAdmin or MySQL command line
-- ============================================================

CREATE DATABASE IF NOT EXISTS bushminds_db;
USE bushminds_db;

-- ── TABLE: contact enquiries from the website form ──
CREATE TABLE IF NOT EXISTS enquiries (
  id           INT AUTO_INCREMENT PRIMARY KEY,
  first_name   VARCHAR(100) NOT NULL,
  last_name    VARCHAR(100) NOT NULL,
  email        VARCHAR(150) NOT NULL,
  phone        VARCHAR(50),
  destination  VARCHAR(200),
  travelers    INT DEFAULT 1,
  travel_date  DATE,
  message      TEXT NOT NULL,
  status       ENUM('new', 'read', 'replied') DEFAULT 'new',
  submitted_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- ── TABLE: admin login ──
CREATE TABLE IF NOT EXISTS admin_users (
  id           INT AUTO_INCREMENT PRIMARY KEY,
  username     VARCHAR(100) NOT NULL UNIQUE,
  password     VARCHAR(255) NOT NULL,
  created_at   TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- ── Default admin account ──
-- Username: admin | Password: BushMinds2026
INSERT INTO admin_users (username, password)
VALUES ('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uSsNTNgiO');
-- NOTE: Change this password after first login!
