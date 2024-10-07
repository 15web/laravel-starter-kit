<?php

declare(strict_types=1);

namespace App\Module\Products\Action\Category\Tree;

use App\Infrastructure\ApiResponse\ResolveApiResponse;
use App\Module\Products\Model\Category;
use Illuminate\Http\JsonResponse;
use Iterator;
use Kalnoy\Nestedset\Collection;
use Spatie\RouteAttributes\Attributes as Router;

/**
 * TODO: Опиши за что отвечает данный класс, какие проблемы решает
 */
final readonly class CategoryTreeAction
{
    public function __construct(
        private ResolveApiResponse $resolveApiResponse,
    ) {}

    #[Router\Get('/category/tree')]
    public function __invoke(): JsonResponse
    {
        /** @var Collection<Category> $categoriesCollection */
        $categoriesCollection = Category::query()->get();

        /** @var list<Category> $categories */
        $categories = $categoriesCollection->toTree()->all();

        return ($this->resolveApiResponse)($this->getTreeData($categories));
    }

    /**
     * @param list<Category> $categories
     *
     * @return Iterator
     */
    private function getTreeData(array $categories): iterable
    {
        foreach ($categories as $category) {
            /** @var Collection<Category> $childrenCollection */
            $childrenCollection = $category->children()->getResults();

            /** @var list<Category> $children */
            $children = $childrenCollection->all();

            yield new CategoryTreeResponse(
                $category->id,
                $category->title,
                $this->getTreeData($children),
                $category->created_at,
                $category->updated_at,
            );
        }
    }
}
