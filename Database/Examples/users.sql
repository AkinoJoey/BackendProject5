CREATE TABLE IF NOT EXISTS users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50),
    email VARCHAR(50),
    password VARCHAR(50),
    email_confirmed_at VARCHAR(50),
    created_at Datetime,
    updated_at Datetime
);
