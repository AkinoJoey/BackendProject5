<?php

namespace Database\Migrations;

use Database\SchemaMigration;

class CreateCategoryTable implements SchemaMigration
{
    public function up(): array
    {
        // マイグレーションロジックをここに追加してください
        return [
            "CREATE TABLE categories(
                categoryID INT PRIMARY KEY,
                categoryName VARCHAR(255)
            )"
        ];
    }

    public function down(): array
    {
        // ロールバックロジックを追加してください
        return [
            "DROP TABLE categories"
        ];
    }
}