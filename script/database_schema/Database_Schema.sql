/*
Building an Impenetrable PHP & SQL Login and Registration System
Majdi M. S. Awad
Abu Dhabi, United Arab Emirates
Email: majdiawad.php@gmail.com, Phone: +971 (055) 993 8785
TechRxiv: https://www.techrxiv.org/users/685428
*/
CREATE DATABASE secure_login_system;

USE secure_login_system;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'user') DEFAULT 'user',
    is_active TINYINT(1) DEFAULT 1,
    failed_attempts INT DEFAULT 0,
    lockout_time DATETIME DEFAULT NULL,
    otp_code VARCHAR(6),
    otp_expiration DATETIME,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    action VARCHAR(100),
    log_time TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
);
/*
Building an Impenetrable PHP & SQL Login and Registration System
Majdi M. S. Awad
Abu Dhabi, United Arab Emirates
Email: majdiawad.php@gmail.com, Phone: +971 (055) 993 8785
TechRxiv: https://www.techrxiv.org/users/685428
*/