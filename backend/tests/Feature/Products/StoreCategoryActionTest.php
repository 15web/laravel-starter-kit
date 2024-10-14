<?php

declare(strict_types=1);

namespace Tests\Feature\Products;

use App\Contract\Error;
use Carbon\Carbon;
use Iterator;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\TestDox;
use Tests\Feature\TestCase;

/**
 * @internal
 */
#[TestDox('Ручка создания категории')]
final class StoreCategoryActionTest extends TestCase
{
    #[TestDox('Успешный запрос')]
    public function testSucceedRequest(): void
    {
        $response = $this
            ->post('api/products/category', [
                'title' => 'Title',
            ])
            ->assertOk();

        /**
         * @var array{
         *     id: mixed,
         *     title: non-empty-string,
         *     createdAt: non-empty-string,
         *     updatedAt: non-empty-string|null,
         * } $data
         */
        $data = $response->json();

        self::assertIsNumeric($data['id']);
        self::assertSame($data['title'], 'Title');
        self::assertInstanceOf(Carbon::class, Carbon::createFromFormat('c', $data['createdAt']));
        self::assertNull($data['updatedAt']);
    }

    #[TestDox('Создание вложенной категории')]
    public function testChildRequest(): void
    {
        $response = $this
            ->post('api/products/category', ['title' => 'Parent'])
            ->assertOk();

        /** @var positive-int $parentId */
        $parentId = $response->json('id');

        $response = $this
            ->post('api/products/category', [
                'title' => 'Child',
                'parentId' => $parentId,
            ])
            ->assertOk();

        /**
         * @var array{
         *     id: mixed,
         *     title: non-empty-string,
         *     createdAt: non-empty-string,
         *     updatedAt: non-empty-string|null,
         * } $data
         */
        $data = $response->json();

        self::assertIsNumeric($data['id']);
        self::assertSame($data['title'], 'Child');
        self::assertInstanceOf(Carbon::class, Carbon::createFromFormat('c', $data['createdAt']));
        self::assertNull($data['updatedAt']);
    }

    #[TestDox('Запись с таким заголовком уже существует')]
    public function testExists(): void
    {
        $this
            ->post('api/products/category', ['title' => 'Title'])
            ->assertOk();

        $response = $this
            ->post('api/products/category', ['title' => 'Title'])
            ->assertOk();

        $this->assertApiError($response, Error::EXISTS->value);
    }

    #[TestDox('Запись с одинаковым заголовком, но с разными "родителями')]
    public function testSameTitles(): void
    {
        $response = $this
            ->post('api/products/category', ['title' => 'Title'])
            ->assertOk();

        /** @var positive-int $parentId */
        $parentId = $response->json('id');

        $response = $this
            ->post('api/products/category', ['title' => 'Title', 'parent' => $parentId])
            ->assertOk();

        /**
         * @var array{
         *     id: mixed,
         *     title: non-empty-string,
         *     createdAt: non-empty-string,
         *     updatedAt: non-empty-string|null,
         * } $data
         */
        $data = $response->json();

        self::assertIsNumeric($data['id']);
        self::assertSame($data['title'], 'Title');
        self::assertInstanceOf(Carbon::class, Carbon::createFromFormat('c', $data['createdAt']));
        self::assertNull($data['updatedAt']);
    }

    /**
     * @param array<int|string> $body
     */
    #[DataProvider('notValidRequestProvider')]
    #[TestDox('Неправильный запрос')]
    public function testBadRequest(array $body): void
    {
        $this
            ->post('api/products/category', $body)
            ->assertBadRequest();
    }

    public static function notValidRequestProvider(): Iterator
    {
        yield 'пустой запрос' => [['']];

        yield 'пустой заголовок' => [['title' => '']];
    }
}
