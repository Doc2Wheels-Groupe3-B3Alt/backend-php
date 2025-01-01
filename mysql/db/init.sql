CREATE OR REPLACE TABLE users (
    id SERIAL PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,   -- Unique ID for each user
    username VARCHAR(50) NOT NULL UNIQUE,       -- Username field, max length 50
    password VARCHAR(255) NOT NULL, -- Password hash for secure storage
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP -- Timestamp of user creation
);