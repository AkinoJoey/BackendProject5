<?php

namespace Database\Migrations;

use Database\SchemaMigration;

class CreatePostTaxonomy implements SchemaMigration
{
    public function up(): array
    {
        // マイグレーションロジックをここに追加してください
        return [
            "CREATE TABLE post_taxonomies(
                postTaxonomyID INT PRIMARY KEY,
                postID INT, FOREIGN KEY (postID) REFERENCES posts(postID),
                taxonomyID INT, FOREIGN KEY (taxonomyID) REFERENCES taxonomies(taxonomyID)
            )"
        ];
    }

    public function down(): array
    {
        // ロールバックロジックを追加してください
        return [
            "DROP TABLE post_taxonomies"
        ];
    }
}