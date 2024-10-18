<?php

declare(strict_types=1);

namespace App\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241018104135 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Изменение PK таблицы пользователей';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DELETE FROM user_tokens WHERE 1');
        $this->addSql('DELETE FROM users WHERE 1');
        $this->addSql('ALTER TABLE user_tokens DROP FOREIGN KEY FK_CF080AB3A76ED395');
        $this->addSql('ALTER TABLE user_tokens CHANGE user_id user_id VARCHAR(36) NOT NULL');
        $this->addSql('ALTER TABLE users CHANGE id id VARCHAR(36) NOT NULL');
        $this->addSql('ALTER TABLE user_tokens ADD CONSTRAINT FK_8B9578868DB60186 FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user_tokens DROP FOREIGN KEY FK_8B9578868DB60186');
        $this->addSql('ALTER TABLE user_tokens CHANGE user_id user_id INT NOT NULL');
        $this->addSql('ALTER TABLE users CHANGE id id INT AUTO_INCREMENT NOT NULL');
        $this->addSql('ALTER TABLE user_tokens ADD CONSTRAINT FK_CF080AB3A76ED395 FOREIGN KEY (user_id) REFERENCES users (id)');
    }
}
