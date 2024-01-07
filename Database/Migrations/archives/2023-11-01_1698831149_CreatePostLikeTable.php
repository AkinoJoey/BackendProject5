<?php

namespace Database\Migrations;

use Database\SchemaMigration;

class CreatePostLikeTable implements SchemaMigration
{
    public function up(): array
    {
        // マイグレーションロジックをここに追加してください
        return [
            "CREATE TABLE post_likes(
                userID INT, FOREIGN KEY (userID) REFERENCES users(userID) ON DELETE CASCADE,
                postID INT, FOREIGN KEY (postID) REFERENCES posts(postID) ON DELETE CASCADE
            )"
        ];
    }

    public function down(): array
    {
        // ロールバックロジックを追加してください
        return [
            "DROP TABLE post_likes"
        ];
    }
}