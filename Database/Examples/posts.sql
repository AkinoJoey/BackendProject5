CREATE TABLE IF NOT EXISTS posts(
    id INT PRIMARY KEY AUTO_INCREMENT,
    title VARCHAR(50),
    context VARCHAR(255),
    created_at Datetime,
    updated_at Datetime,
    user_id INT, FOREIGN KEY user_fk(user_id) REFERENCES users(id)
)