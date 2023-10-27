CREATE TABLE IF NOT EXISTS post_tags(
    post_id INT, FOREIGN KEY post_fk(post_id) REFERENCES posts(id),
    tag_id INT, FOREIGN KEY tag_fk(tag_id) REFERENCES tags(id),
    PRIMARY KEY(post_id, tag_id)
);