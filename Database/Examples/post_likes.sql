CREATE TABLE IF NOT EXISTS post_likes(
    user_id INT, FOREIGN KEY user_fk(user_id) REFERENCES users(id),
    post_id INT, FOREIGN KEY post_fk(post_id) REFERENCES posts(id),
    PRIMARY KEY(user_id, post_id)
)