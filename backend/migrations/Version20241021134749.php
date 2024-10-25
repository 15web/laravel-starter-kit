<?php

declare(strict_types=1);

namespace App\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241021134749 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Категории товаров';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE product_categories (id INT GENERATED BY DEFAULT AS IDENTITY NOT NULL, lft INT DEFAULT NULL, lvl INT DEFAULT NULL, rgt INT DEFAULT NULL, createdAt TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updatedAt TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, title VARCHAR(255) NOT NULL, treeRoot INT DEFAULT NULL, parentId INT DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_A9941943A8A66EAC ON product_categories (treeRoot)');
        $this->addSql('CREATE INDEX IDX_A994194310EE4CEE ON product_categories (parentId)');
        $this->addSql('ALTER TABLE product_categories ADD CONSTRAINT FK_A9941943A8A66EAC FOREIGN KEY (treeRoot) REFERENCES product_categories (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE product_categories ADD CONSTRAINT FK_A994194310EE4CEE FOREIGN KEY (parentId) REFERENCES product_categories (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE product_categories DROP CONSTRAINT FK_A9941943A8A66EAC');
        $this->addSql('ALTER TABLE product_categories DROP CONSTRAINT FK_A994194310EE4CEE');
        $this->addSql('DROP TABLE product_categories');
    }
}