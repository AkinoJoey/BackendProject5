<?php

namespace Database\Migrations;

use Database\SchemaMigration;

class CreateTagTable implements SchemaMigration
{
    public function up(): array
    {
        // マイグレーションロジックをここに追加してください
        return [
            "CREATE TABLE tags(
                tagID INT PRIMARY KEY,
                tagName VARCHAR(255)
            )"
        ];
    }

    public function down(): array
    {
        // ロールバックロジックを追加してください
        return [
            "DROP TABLE tags"
        ];
    }
}