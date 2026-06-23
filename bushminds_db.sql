-- ============================================================
-- BUSH MINDS TOURS & TRAVEL — DATABASE SETUP
-- Run this in phpMyAdmin > SQL tab
-- ============================================================

-- 1. Create the database
CREATE DATABASE IF NOT EXISTS bushminds_db
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

-- 2. Use it
USE bushminds_db;

-- 3. Create the enquiries table
CREATE TABLE IF NOT EXISTS enquiries (
  id           INT AUTO_INCREMENT PRIMARY KEY,
  fname        VARCHAR(100)  NOT NULL,
  lname        VARCHAR(100)  NOT NULL,
  email        VARCHAR(150)  NOT NULL,
  phone        VARCHAR(30)   DEFAULT NULL,
  destination  VARCHAR(200)  DEFAULT NULL,
  travelers    INT           DEFAULT 0,
  travel_date  DATE          DEFAULT NULL,
  message      TEXT          NOT NULL,
  is_read      TINYINT(1)    NOT NULL DEFAULT 0,
  submitted_at DATETIME      NOT NULL DEFAULT CURRENT_TIMESTAMP
);

-- ============================================================
-- DONE. Your table is ready.
-- Access admin at: http://localhost/bushminds/admin.php
-- Username: bushminds_admin
-- Password: BushIsHome2026!
-- ============================================================
