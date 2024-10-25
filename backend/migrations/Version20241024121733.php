<?php

declare(strict_types=1);

namespace App\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241024121733 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Id пользователя в таблице новостей';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('TRUNCATE TABLE news');
        $this->addSql('ALTER TABLE news ADD user_id UUID NOT NULL');
        $this->addSql('ALTER TABLE news ADD CONSTRAINT FK_1DD39950A76ED395 FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_1DD39950A76ED395 ON news (user_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE news DROP CONSTRAINT FK_1DD39950A76ED395');
        $this->addSql('DROP INDEX IDX_1DD39950A76ED395');
        $this->addSql('ALTER TABLE news DROP user_id');
    }
}
