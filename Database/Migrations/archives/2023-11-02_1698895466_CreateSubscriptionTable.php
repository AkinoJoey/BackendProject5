<?php

namespace Database\Migrations;

use Database\SchemaMigration;

class CreateSubscriptionTable implements SchemaMigration
{
    public function up(): array
    {
        // マイグレーションロジックをここに追加してください
        return [
            "CREATE TABLE subscriptions (
                subscriptionID INT PRIMARY KEY,
                subscription VARCHAR(255),
                subscription_status VARCHAR(50),
                subscriptionCreatedAt DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
                subscriptionEndsAt DATETIME,
                userID INT, FOREIGN KEY (userID) REFERENCES users(userID) ON DELETE CASCADE
            )"
        ];
    }

    public function down(): array
    {
        // ロールバックロジックを追加してください
        return [
            "DROP TABLE subscriptions"
        ];
    }
}