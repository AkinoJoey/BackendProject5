CREATE TABLE IF NOT EXISTS user_settings(
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT, FOREIGN KEY user_fk(user_id) REFERENCES users(id),
    meta_key VARCHAR(50),
    meta_value VARCHAR(50)
)