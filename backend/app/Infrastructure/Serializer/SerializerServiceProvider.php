<?php

declare(strict_types=1);

namespace App\Infrastructure\Serializer;

use Illuminate\Support\ServiceProvider;
use Override;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

/**
 * Сервис провайдер для подключения сериалайзера
 */
final class SerializerServiceProvider extends ServiceProvider
{
    #[Override]
    public function register(): void
    {
        $this->app->bind(Serializer::class, static function (): Serializer {
            $encoders = [new JsonEncoder()];
            $normalizers = [ // порядок имеет значение
                new DateTimeNormalizer(),
                new ObjectNormalizer(),
            ];

            return new Serializer($normalizers, $encoders);
        });
    }
}
