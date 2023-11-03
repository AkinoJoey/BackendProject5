<?php

namespace Database\Migrations;

use Database\SchemaMigration;

class ChangeCarTable implements SchemaMigration
{
    public function up(): array
    {
        // マイグレーションロジックをここに追加してください
        return [
            "ALTER TABLE cars
                ADD created_at DATETIME,
                ADD updated_at DATETIME    
            "
        ];
    }

    public function down(): array
    {
        // ロールバックロジックを追加してください
        return [
            "ALTER TABLE cars
                DROP column created_at,
                DROP column updated_at
            "
        ];
    }
}
