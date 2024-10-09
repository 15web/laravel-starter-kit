<?php

declare(strict_types=1);

namespace App\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241004064228 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Laravel Queue';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('create table jobs
            (
                id           bigint unsigned auto_increment primary key,
                queue        varchar(255)     not null,
                payload      longtext         not null,
                attempts     tinyint unsigned not null,
                reserved_at  int unsigned     null,
                available_at int unsigned     not null,
                created_at   int unsigned     not null
            ) collate = utf8mb4_unicode_ci;');

        $this->addSql('create index jobs_queue_index on jobs (queue);');

        $this->addSql('create table job_batches
            (
                id             varchar(255) not null primary key,
                name           varchar(255) not null,
                total_jobs     int          not null,
                pending_jobs   int          not null,
                failed_jobs    int          not null,
                failed_job_ids longtext     not null,
                options        mediumtext   null,
                cancelled_at   int          null,
                created_at     int          not null,
                finished_at    int          null
            ) collate = utf8mb4_unicode_ci;');

        $this->addSql('create table failed_jobs
            (
                id         bigint unsigned auto_increment primary key,
                uuid       varchar(255)                        not null,
                connection text                                not null,
                queue      text                                not null,
                payload    longtext                            not null,
                exception  longtext                            not null,
                failed_at  timestamp default CURRENT_TIMESTAMP not null,
                constraint failed_jobs_uuid_unique unique (uuid)
            ) collate = utf8mb4_unicode_ci;');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE failed_jobs');
        $this->addSql('DROP TABLE job_batches');
        $this->addSql('DROP TABLE jobs');
    }
}
