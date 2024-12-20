<?php

declare(strict_types=1);

namespace App\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;
use Override;

final class Version20241004064227 extends AbstractMigration
{
    #[Override]
    public function getDescription(): string
    {
        return 'Проверка базы данных';
    }

    #[Override]
    public function up(Schema $schema): void
    {
        $this->addSql("SELECT '1';");
    }
}
