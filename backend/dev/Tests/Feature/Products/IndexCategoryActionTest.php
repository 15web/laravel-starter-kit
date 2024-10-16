<?php

declare(strict_types=1);

namespace Dev\Tests\Feature\Products;

use DateTimeImmutable;
use Dev\Tests\Feature\TestCase;
use PHPUnit\Framework\Attributes\TestDox;

/**
 * @internal
 */
#[TestDox('Ручка просмотра всех категорий')]
final class IndexCategoryActionTest extends TestCase
{
    #[TestDox('Успешный запрос')]
    public function testSucceedRequest(): void
    {
        $response = $this
            ->postJson('api/products/category', ['title' => 'Parent1', 'parent' => null])
            ->assertOk();

        /** @var positive-int $parentId1 */
        $parentId1 = $response->json('id');

        $response = $this
            ->postJson('api/products/category', ['title' => 'Parent2', 'parent' => null])
            ->assertOk();

        /** @var positive-int $parentId2 */
        $parentId2 = $response->json('id');

        $response = $this
            ->postJson('api/products/category', ['title' => 'Child1', 'parent' => $parentId1])
            ->assertOk();

        /** @var positive-int $childId1 */
        $childId1 = $response->json('id');

        $response = $this
            ->postJson('api/products/category', ['title' => 'Child2', 'parent' => $parentId2])
            ->assertOk();

        /** @var positive-int $childId2 */
        $childId2 = $response->json('id');

        $response = $this
            ->postJson('api/products/category', ['title' => 'Child3', 'parent' => $parentId2])
            ->assertOk();

        /** @var positive-int $childId3 */
        $childId3 = $response->json('id');

        $response = $this->getJson('api/products/category')->assertOk();

        /**
         * @var list<array{
         *     id: mixed,
         *     title: non-empty-string,
         *     children: non-empty-list<array{
         *         id: mixed,
         *         title: non-empty-string,
         *         children: array<array-key, mixed>,
         *         createdAt: non-empty-string,
         *         updatedAt: non-empty-string|null
         *     }>,
         *     createdAt: non-empty-string,
         *     updatedAt: non-empty-string|null
         * }> $data
         */
        $data = $response->json();

        self::assertCount(2, $data);
        self::assertSame($data[0]['id'], $parentId1);
        self::assertSame($data[0]['title'], 'Parent1');
        self::assertInstanceOf(DateTimeImmutable::class, DateTimeImmutable::createFromFormat(DateTimeImmutable::ATOM, $data[0]['createdAt']));
        self::assertNull($data[0]['updatedAt']);

        self::assertCount(1, $data[0]['children']);

        self::assertSame($data[0]['children'][0]['id'], $childId1);
        self::assertSame($data[0]['children'][0]['title'], 'Child1');
        self::assertSame($data[0]['children'][0]['children'], []);
        self::assertInstanceOf(DateTimeImmutable::class, DateTimeImmutable::createFromFormat(DateTimeImmutable::ATOM, $data[0]['children'][0]['createdAt']));
        self::assertNull($data[0]['children'][0]['updatedAt']);

        self::assertSame($data[1]['id'], $parentId2);
        self::assertSame($data[1]['title'], 'Parent2');
        self::assertInstanceOf(DateTimeImmutable::class, DateTimeImmutable::createFromFormat(DateTimeImmutable::ATOM, $data[1]['createdAt']));
        self::assertNull($data[1]['updatedAt']);

        self::assertCount(2, $data[1]['children']);

        self::assertSame($data[1]['children'][0]['id'], $childId2);
        self::assertSame($data[1]['children'][0]['title'], 'Child2');
        self::assertSame($data[1]['children'][0]['children'], []);
        self::assertInstanceOf(DateTimeImmutable::class, DateTimeImmutable::createFromFormat(DateTimeImmutable::ATOM, $data[1]['children'][0]['createdAt']));
        self::assertNull($data[1]['children'][0]['updatedAt']);

        self::assertSame($data[1]['children'][1]['id'], $childId3);
        self::assertSame($data[1]['children'][1]['title'], 'Child3');
        self::assertSame($data[1]['children'][1]['children'], []);
        self::assertInstanceOf(DateTimeImmutable::class, DateTimeImmutable::createFromFormat(DateTimeImmutable::ATOM, $data[1]['children'][1]['createdAt']));
        self::assertNull($data[1]['children'][1]['updatedAt']);
    }

    #[TestDox('Нет записей')]
    public function testEmptyCollection(): void
    {
        $this
            ->getJson('api/products/category')
            ->assertOk()
            ->assertJson([]);
    }
}
