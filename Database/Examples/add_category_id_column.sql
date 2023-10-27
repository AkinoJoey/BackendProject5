ALTER TABLE posts
ADD category_id INT,
ADD CONSTRAINT fk_category_id FOREIGN KEY (category_id) REFERENCES categories(id);
