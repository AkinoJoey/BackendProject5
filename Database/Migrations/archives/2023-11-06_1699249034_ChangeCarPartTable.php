<?php

namespace Database\Migrations;

use Database\SchemaMigration;

class ChangeCarPartTable implements SchemaMigration
{
    public function up(): array
    {
        // マイグレーションロジックをここに追加してください
        return [
            "ALTER TABLE car_parts
                ADD created_at DATETIME,
                ADD updated_at DATETIME    
            "
        ];
    }

    public function down(): array
    {
        // ロールバックロジックを追加してください
        return [
            "ALTER TABLE car_parts
                DROP column created_at,
                DROP column updated_at
            "
        ];
    }
}