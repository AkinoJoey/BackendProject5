<?php

namespace Database\Migrations;

use Database\SchemaMigration;

class ChangePostTable implements SchemaMigration
{
    public function up(): array
    {
        // マイグレーションロジックをここに追加してください
        return [
            "ALTER TABLE posts
                DROP FOREIGN KEY posts_ibfk_2,
                DROP COLUMN categoryID"
        ];
    }

    public function down(): array
    {
        // ロールバックロジックを追加してください
        return [
            "ALTER TABLE posts ADD COLUMN categoryID INT, FOREIGN KEY (categoryID) REFERENCES categories(categoryID)"
        ];
    }
}
