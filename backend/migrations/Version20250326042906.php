<?php

declare(strict_types=1);

namespace App\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;
use Override;

final class Version20250326042906 extends AbstractMigration
{
    #[Override]
    public function getDescription(): string
    {
        return 'Добавление тестовых пользователй';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('INSERT INTO "users" (id, email, password, roles, createdAt) VALUES (gen_random_uuid(), \'user@example.test\', \'$2y$10$ZFQEX6CmeRBZusWEgJap1OR3HaXHDnh4upG7OhqeHeBCh0J2IhKm2\', \'["user"]\', now())');
        $this->addSql('INSERT INTO "users" (id, email, password, roles, createdAt) VALUES (gen_random_uuid(), \'admin@example.test\', \'$2y$10$ZFQEX6CmeRBZusWEgJap1OR3HaXHDnh4upG7OhqeHeBCh0J2IhKm2\', \'["admin"]\', now())');
    }

    #[Override]
    public function down(Schema $schema): void
    {
        $this->addSql('DELETE FROM "user" WHERE email IN (\'user@example.test\', \'admin@example.test\')');
    }
}
