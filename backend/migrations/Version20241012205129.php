<?php

declare(strict_types=1);

namespace App\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241012205129 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Категории товаров';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE product_categories (id INT AUTO_INCREMENT NOT NULL, lft INT DEFAULT NULL, lvl INT DEFAULT NULL, rgt INT DEFAULT NULL, createdAt DATETIME NOT NULL, updatedAt DATETIME DEFAULT NULL, title VARCHAR(255) NOT NULL, treeRoot INT DEFAULT NULL, parentId INT DEFAULT NULL, INDEX IDX_A9941943A8A66EAC (treeRoot), INDEX IDX_A994194310EE4CEE (parentId), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE product_categories ADD CONSTRAINT FK_A9941943A8A66EAC FOREIGN KEY (treeRoot) REFERENCES product_categories (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE product_categories ADD CONSTRAINT FK_A994194310EE4CEE FOREIGN KEY (parentId) REFERENCES product_categories (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE product_categories DROP FOREIGN KEY FK_A9941943A8A66EAC');
        $this->addSql('ALTER TABLE product_categories DROP FOREIGN KEY FK_A994194310EE4CEE');
        $this->addSql('DROP TABLE product_categories');
    }
}
