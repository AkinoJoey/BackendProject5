<?php

namespace Database\Migrations;

use Database\SchemaMigration;

class RemoveCategoryTable implements SchemaMigration
{
    public function up(): array
    {
        // マイグレーションロジックをここに追加してください
        return [
            "DROP TABLE categories"
        ];
    }

    public function down(): array
    {
        // ロールバックロジックを追加してください
        return [
            "CREATE TABLE categories(
                categoryID INT PRIMARY KEY,
                categoryName VARCHAR(255)
            )"
        ];
    }
}
