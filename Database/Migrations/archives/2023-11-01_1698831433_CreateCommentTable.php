<?php

namespace Database\Migrations;

use Database\SchemaMigration;

class CreateCommentTable implements SchemaMigration
{
    public function up(): array
    {
        // マイグレーションロジックをここに追加してください
        return [
            "CREATE TABLE comments(
                commentID INT PRIMARY KEY,
                commentText VARCHAR(255),
                created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
                updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                userID INT, FOREIGN KEY (userID) REFERENCES users(userID),
                postID INT, FOREIGN KEY (postID) REFERENCES posts(postID)
            )"
        ];
    }

    public function down(): array
    {
        // ロールバックロジックを追加してください
        return [
            "DROP TABLE comments"
        ];
    }
}