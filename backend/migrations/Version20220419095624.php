<?php

declare(strict_types=1);

namespace App\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220419095624 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Таблица для laravel queue.';
    }

    public function up(Schema $schema): void
    {
        $this->addSql(<<<SQL
             create table jobs
            (
                id           bigint unsigned auto_increment
                    primary key,
                queue        varchar(255)     not null,
                payload      longtext         not null,
                attempts     tinyint unsigned not null,
                reserved_at  int unsigned     null,
                available_at int unsigned     not null,
                created_at   int unsigned     not null
            )
                collate = utf8mb4_unicode_ci;
            
            create index jobs_queue_index
                on jobs (queue);
        SQL);

        $this->addSql(<<<SQL
            create table failed_jobs
            (
                id         bigint unsigned auto_increment
                    primary key,
                uuid       varchar(255)                        not null,
                connection text                                not null,
                queue      text                                not null,
                payload    longtext                            not null,
                exception  longtext                            not null,
                failed_at  timestamp default CURRENT_TIMESTAMP not null,
                constraint failed_jobs_uuid_unique
                    unique (uuid)
            )
                collate = utf8mb4_unicode_ci;
        SQL);
    }

    public function down(Schema $schema): void
    {
       $this->addSql('DROP TABLE jobs');
       $this->addSql('DROP TABLE failed_jobs');
    }
}
