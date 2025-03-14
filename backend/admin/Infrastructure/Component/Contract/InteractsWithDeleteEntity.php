<?php

declare(strict_types=1);

namespace Admin\Infrastructure\Component\Contract;

/**
 * Из компонента можно удалять сущность
 */
interface InteractsWithDeleteEntity
{
    /**
     * @param non-empty-string $entityId
     */
    public static function deleteEntity(string $entityId): void;
}
