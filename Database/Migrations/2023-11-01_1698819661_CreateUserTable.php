<?php

namespace Database\Migrations;

use Database\SchemaMigration;

class CreateUserTable implements SchemaMigration
{
    public function up(): array
    {
        return [
            "CREATE TABLE users (
                userID INT PRIMARY KEY,
                username VARCHAR(255) NOT NULL,
                email VARCHAR(255) NOT NULL UNIQUE,
                password VARCHAR(255) NOT NULL,
                email_confirmed_at VARCHAR(255),
                created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
                updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                subscription VARCHAR(255),
                subscription_status VARCHAR(50),
                subscription_created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
                subscription_ends_at DATETIME
            )"
        ];
    }

    public function down(): array
    {
        return [
            "DROP TABLE users"
        ];
    }
}