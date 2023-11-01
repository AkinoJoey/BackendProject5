<?php

namespace Database\Migrations;

use Database\SchemaMigration;

class CreatePostTagTable implements SchemaMigration
{
    public function up(): array
    {
        // マイグレーションロジックをここに追加してください
        return [
            "CREATE TABLE post_tags(
                postID INT, FOREIGN KEY (postID) REFERENCES posts(postID) ON DELETE CASCADE,
                tagID INT, FOREIGN KEY (tagID) REFERENCES tags(tagID) ON DELETE CASCADE,
                PRIMARY KEY(postID, tagID)
            )"
        ];
    }

    public function down(): array
    {
        // ロールバックロジックを追加してください
        return [
            "DROP TABLE post_tags"
        ];
    }
}