<?php

declare(strict_types=1);

namespace App\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;
use Override;

final class Version20241023061645 extends AbstractMigration
{
    #[Override]
    public function getDescription(): string
    {
        return 'Хэш токена авторизации';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('TRUNCATE TABLE user_tokens');
        $this->addSql('ALTER TABLE user_tokens ADD hash VARCHAR(255) NOT NULL');
    }

    #[Override]
    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE user_tokens DROP hash');
    }
}
