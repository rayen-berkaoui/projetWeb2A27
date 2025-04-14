-- SQL script to verify and update database structure for the feedback system

-- Create or update the feedbacks table
CREATE TABLE IF NOT EXISTS feedbacks (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(50) NOT NULL,
    prenom VARCHAR(50) NOT NULL,
    email VARCHAR(100) DEFAULT NULL,
    commentaire TEXT NOT NULL,
    note INT NOT NULL CHECK (note BETWEEN 1 AND 5),
    status ENUM('pending','approved','rejected') DEFAULT 'pending',
    featured TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Check if columns exist and add them if they don't
-- These ALTER TABLE statements are safe to run even if columns already exist
-- as we'll wrap them in procedures that check if the column exists first

-- For is_public column
DELIMITER //
CREATE PROCEDURE AddIsPublicColumn()
BEGIN
    IF NOT EXISTS (
        SELECT * FROM INFORMATION_SCHEMA.COLUMNS 
        WHERE TABLE_SCHEMA = 'avis_db' 
        AND TABLE_NAME = 'feedbacks' 
        AND COLUMN_NAME = 'is_public'
    ) THEN
        ALTER TABLE feedbacks ADD COLUMN is_public TINYINT(1) DEFAULT 0;
    END IF;
END//
DELIMITER ;
CALL AddIsPublicColumn();
DROP PROCEDURE IF EXISTS AddIsPublicColumn;

-- For admin_response column
DELIMITER //
CREATE PROCEDURE AddAdminResponseColumn()
BEGIN
    IF NOT EXISTS (
        SELECT * FROM INFORMATION_SCHEMA.COLUMNS 
        WHERE TABLE_SCHEMA = 'avis_db' 
        AND TABLE_NAME = 'feedbacks' 
        AND COLUMN_NAME = 'admin_response'
    ) THEN
        ALTER TABLE feedbacks ADD COLUMN admin_response TEXT NULL;
    END IF;
END//
DELIMITER ;
CALL AddAdminResponseColumn();
DROP PROCEDURE IF EXISTS AddAdminResponseColumn;

-- For admin_response_by column
DELIMITER //
CREATE PROCEDURE AddAdminResponseByColumn()
BEGIN
    IF NOT EXISTS (
        SELECT * FROM INFORMATION_SCHEMA.COLUMNS 
        WHERE TABLE_SCHEMA = 'avis_db' 
        AND TABLE_NAME = 'feedbacks' 
        AND COLUMN_NAME = 'admin_response_by'
    ) THEN
        ALTER TABLE feedbacks ADD COLUMN admin_response_by INT NULL;
    END IF;
END//
DELIMITER ;
CALL AddAdminResponseByColumn();
DROP PROCEDURE IF EXISTS AddAdminResponseByColumn;

-- For admin_response_date column
DELIMITER //
CREATE PROCEDURE AddAdminResponseDateColumn()
BEGIN
    IF NOT EXISTS (
        SELECT * FROM INFORMATION_SCHEMA.COLUMNS 
        WHERE TABLE_SCHEMA = 'avis_db' 
        AND TABLE_NAME = 'feedbacks' 
        AND COLUMN_NAME = 'admin_response_date'
    ) THEN
        ALTER TABLE feedbacks ADD COLUMN admin_response_date DATETIME NULL;
    END IF;
END//
DELIMITER ;
CALL AddAdminResponseDateColumn();
DROP PROCEDURE IF EXISTS AddAdminResponseDateColumn;

-- For featured column
DELIMITER //
CREATE PROCEDURE AddFeaturedColumn()
BEGIN
    IF NOT EXISTS (
        SELECT * FROM INFORMATION_SCHEMA.COLUMNS 
        WHERE TABLE_SCHEMA = 'avis_db' 
        AND TABLE_NAME = 'feedbacks' 
        AND COLUMN_NAME = 'featured'
    ) THEN
        ALTER TABLE feedbacks ADD COLUMN featured TINYINT(1) DEFAULT 0;
    END IF;
END//
DELIMITER ;
CALL AddFeaturedColumn();
DROP PROCEDURE IF EXISTS AddFeaturedColumn;

-- Create products table if it doesn't exist
CREATE TABLE IF NOT EXISTS products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL
);

-- Add some sample products if the table is empty
INSERT INTO products (name)
SELECT * FROM (
    SELECT 'Plant Pot - Terracotta' AS name
    UNION SELECT 'Eco-Friendly Watering Can'
    UNION SELECT 'Indoor Herb Garden Kit'
    UNION SELECT 'Bamboo Plant Stand'
    UNION SELECT 'Organic Plant Food'
) AS tmp
WHERE NOT EXISTS (
    SELECT 1 FROM products LIMIT 1
);

