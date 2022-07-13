<?php

declare(strict_types=1);

namespace App\Infrastructure\Serializer;

use Illuminate\Support\ServiceProvider;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

final class SerializerServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(Serializer::class, static function () {
            $encoders = [new JsonEncoder()];
            $normalizers = [ // порядок имеет значение
                new DateTimeNormalizer(),
                new ObjectNormalizer(),
            ];

            return new Serializer($normalizers, $encoders);
        });
    }
}
