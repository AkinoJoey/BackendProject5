<?php

namespace Database\Migrations;

use Database\SchemaMigration;

class UpdateUserTable2 implements SchemaMigration
{
    public function up(): array
    {
        return ["ALTER TABLE users ADD email_verified INT;"];
    }

    public function down(): array
    {
        return ["ALTER TABLE users DROP COLUMN email_verified"];
    }
}