CREATE TABLE IF NOT EXISTS comment_likes(
    user_id INT , FOREIGN KEY user_fk(user_id) REFERENCES users(id),
    comment_id INT , FOREIGN KEY comment_fk(comment_id) REFERENCES comments(id),
    PRIMARY KEY(user_id, comment_id)
)