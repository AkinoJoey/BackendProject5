<?php

namespace Database\Migrations;

use Database\SchemaMigration;

class CreateCarTable implements SchemaMigration
{
    public function up(): array
    {
        // マイグレーションロジックをここに追加してください
        return [
            "CREATE TABLE cars(
                id INT PRIMARY KEY AUTO_INCREMENT,
                make VARCHAR(50) NOT NULL,
                model VARCHAR(100) NOT NULL,
                year INT,
                color VARCHAR(50),
                price FLOAT,
                mileage FLOAT,
                transmission VARCHAR(100),
                engine VARCHAR(50),
                status VARCHAR(255)
            )"
        ];
    }

    public function down(): array
    {
        // ロールバックロジックを追加してください
        return [
            "DROP TABLE cars"
        ];
    }
}