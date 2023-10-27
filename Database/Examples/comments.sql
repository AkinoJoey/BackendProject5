CREATE TABLE IF NOT EXISTS comments(
    id INT PRIMARY KEY AUTO_INCREMENT,
    comment_text VARCHAR(255),
    created_at Datetime,
    updated_at Datetime,
    user_id INT, FOREIGN KEY user_fk(user_id) REFERENCES users(id),
    post_id INT, FOREIGN KEY post_fk(post_id) REFERENCES posts(id)

)