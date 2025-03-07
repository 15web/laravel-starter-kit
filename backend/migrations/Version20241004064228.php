<?php

declare(strict_types=1);

namespace App\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;
use Override;

final class Version20241004064228 extends AbstractMigration
{
    #[Override]
    public function getDescription(): string
    {
        return 'Laravel Queue';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('create table public.jobs
            (
                id           bigserial primary key,
                queue        varchar(255) not null,
                payload      text         not null,
                attempts     smallint     not null,
                reserved_at  integer,
                available_at integer      not null,
                created_at   integer      not null
            );');

        $this->addSql('alter table public.jobs owner to postgres');
        $this->addSql('create index jobs_queue_index on public.jobs (queue);');

        $this->addSql('create table public.job_batches
            (
                id             varchar(255) not null primary key,
                name           varchar(255) not null,
                total_jobs     integer      not null,
                pending_jobs   integer      not null,
                failed_jobs    integer      not null,
                failed_job_ids text         not null,
                options        text,
                cancelled_at   integer,
                created_at     integer      not null,
                finished_at    integer
            );');

        $this->addSql('alter table public.job_batches owner to postgres;');

        $this->addSql('create table public.failed_jobs
            (
                id         bigserial primary key,
                uuid       varchar(255) constraint failed_jobs_uuid_unique unique,
                connection text                                   not null,
                queue      text                                   not null,
                payload    text                                   not null,
                exception  text                                   not null,
                failed_at  timestamp(0) default CURRENT_TIMESTAMP not null
            );');

        $this->addSql('alter table public.failed_jobs owner to postgres;');
    }

    #[Override]
    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE failed_jobs');
        $this->addSql('DROP TABLE job_batches');
        $this->addSql('DROP TABLE jobs');
    }
}
