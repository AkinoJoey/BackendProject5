<?php

namespace Database\Migrations;

use Database\SchemaMigration;

class CreateCommentLikeTable implements SchemaMigration
{
    public function up(): array
    {
        // マイグレーションロジックをここに追加してください
        return [
            "CREATE TABLE comment_likes(
                userID INT, FOREIGN KEY (userID) REFERENCES users(userID) ON DELETE CASCADE,
                commentID INT, FOREIGN KEY (commentID) REFERENCES comments(commentID) ON DELETE CASCADE
            )"
        ];
    }

    public function down(): array
    {
        // ロールバックロジックを追加してください
        return [
            "DROP TABLE comment_likes"
        ];
    }
}