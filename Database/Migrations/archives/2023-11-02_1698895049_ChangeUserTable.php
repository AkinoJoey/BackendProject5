<?php

namespace Database\Migrations;

use Database\SchemaMigration;

class ChangeUserTable implements SchemaMigration
{
    public function up(): array
    {
        // マイグレーションロジックをここに追加してください
        return [
            "ALTER TABLE users
                DROP COLUMN subscription,
                DROP COLUMN subscription_status,
                DROP COLUMN subscription_created_at,
                DROP COLUMN subscription_ends_at
            "
        ];
    }

    public function down(): array
    {
        // ロールバックロジックを追加してください
        return [
            "ALTER TABLE users
                ADD COLUMN subscription VARCHAR(255),
                ADD COLUMN subscription_status VARCHAR(50),
                ADD COLUMN subscription_created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
                ADD COLUMN subscription_ends_at DATETIME
            "
        ];
    }
}