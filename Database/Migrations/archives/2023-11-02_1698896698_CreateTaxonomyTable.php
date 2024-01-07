<?php

namespace Database\Migrations;

use Database\SchemaMigration;

class CreateTaxonomyTable implements SchemaMigration
{
    public function up(): array
    {
        // マイグレーションロジックをここに追加してください
        return [
            "CREATE TABLE taxonomies(
                taxonomyID INT PRIMARY KEY,
                taxonomyName VARCHAR(255),
                description VARCHAR(255)
            )"
        ];
    }

    public function down(): array
    {
        // ロールバックロジックを追加してください
        return [
            "DROP TABLE taxonomies"
        ];
    }
}