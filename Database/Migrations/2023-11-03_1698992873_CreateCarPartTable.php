<?php

namespace Database\Migrations;

use Database\SchemaMigration;

class CreateCarPartTable implements SchemaMigration
{
    public function up(): array
    {
        // マイグレーションロジックをここに追加してください
        return [
            "CREATE TABLE car_parts(
                id INT PRIMARY KEY AUTO_INCREMENT,
                carID INT, FOREIGN KEY (carID) REFERENCES cars(id),
                name VARCHAR(255) NOT NULL,
                description TEXT,
                price FLOAT,
                quantityInStock INT
            )"
        ];
    }

    public function down(): array
    {
        // ロールバックロジックを追加してください
        return [
            "DROP TABLE car_parts"
        ];
    }
}