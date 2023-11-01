<?php

namespace Database\Migrations;

use Database\SchemaMigration;

class CreateUserSettingTable implements SchemaMigration
{
    public function up(): array
    {
        // マイグレーションロジックをここに追加してください
        return [
            "CREATE TABLE user_settings(
                entryID INT PRIMARY KEY,
                userID INT,FOREIGN KEY (userID) REFERENCES users(userID),
                metaKey VARCHAR(255),
                metaValue VARCHAR(255)
            )"
        ];
    }

    public function down(): array
    {
        // ロールバックロジックを追加してください
        return [
            "DROP TABLE user_settings"
        ];
    }
}