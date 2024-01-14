<?php

namespace Database\Migrations;

use Database\SchemaMigration;

class UpdateUserTable3 implements SchemaMigration
{
    public function up(): array
    {
        return ["ALTER TABLE users DROP COLUMN email_confirmed_at;"];
    }

    public function down(): array
    {
        return ["ALTER TABLE users ADD email_confirmed_at VARCHAR(255) AFTER password;"];
    }
}