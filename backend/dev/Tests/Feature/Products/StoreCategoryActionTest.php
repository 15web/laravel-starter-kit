<?php

declare(strict_types=1);

namespace Dev\Tests\Feature\Products;

use App\Contract\Error;
use App\Infrastructure\OpenApiSchemaValidator\ValidateOpenApiSchema;
use DateTimeImmutable;
use Dev\Tests\Feature\TestCase;
use Iterator;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\TestDox;

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
            ->postJson('api/products/category', [
                'title' => 'Title',
                'parent' => null,
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
        self::assertInstanceOf(DateTimeImmutable::class, DateTimeImmutable::createFromFormat(DateTimeImmutable::ATOM, $data['createdAt']));
        self::assertNull($data['updatedAt']);
    }

    #[TestDox('Создание вложенной категории')]
    public function testChildRequest(): void
    {
        $response = $this
            ->postJson('api/products/category', ['title' => 'Parent', 'parent' => null])
            ->assertOk();

        /** @var positive-int $parentId */
        $parentId = $response->json('id');

        $response = $this
            ->postJson('api/products/category', [
                'title' => 'Child',
                'parent' => $parentId,
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
        self::assertInstanceOf(DateTimeImmutable::class, DateTimeImmutable::createFromFormat(DateTimeImmutable::ATOM, $data['createdAt']));
        self::assertNull($data['updatedAt']);
    }

    #[TestDox('Запись с таким заголовком уже существует')]
    public function testExists(): void
    {
        $this
            ->postJson('api/products/category', ['title' => 'Title', 'parent' => null])
            ->assertOk();

        $response = $this
            ->postJson('api/products/category', ['title' => 'Title', 'parent' => null])
            ->assertOk();

        $this->assertApiError($response, Error::EXISTS->value);
    }

    #[TestDox('Запись с одинаковым заголовком, но с разными "родителями')]
    public function testSameTitles(): void
    {
        $response = $this
            ->postJson('api/products/category', ['title' => 'Title', 'parent' => null])
            ->assertOk();

        /** @var positive-int $parentId */
        $parentId = $response->json('id');

        $response = $this
            ->postJson('api/products/category', ['title' => 'Title', 'parent' => $parentId])
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
        self::assertInstanceOf(DateTimeImmutable::class, DateTimeImmutable::createFromFormat(DateTimeImmutable::ATOM, $data['createdAt']));
        self::assertNull($data['updatedAt']);
    }

    /**
     * @param array<int|string> $body
     */
    #[DataProvider('notValidRequestProvider')]
    #[TestDox('Неправильный запрос')]
    public function testBadRequest(array $body): void
    {
        $body[ValidateOpenApiSchema::VALIDATE_REQUEST_KEY] = false;

        $this
            ->postJson('api/products/category', $body)
            ->assertBadRequest();
    }

    public static function notValidRequestProvider(): Iterator
    {
        yield 'пустой запрос' => [['']];

        yield 'пустой заголовок' => [['title' => '']];
    }
}
