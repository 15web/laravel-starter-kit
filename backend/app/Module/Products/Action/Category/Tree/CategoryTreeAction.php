<?php

declare(strict_types=1);

namespace App\Module\Products\Action\Category\Tree;

use App\Infrastructure\ApiResponse\ResolveApiResponse;
use App\Module\Products\Model\Category;
use Illuminate\Http\JsonResponse;
use Spatie\RouteAttributes\Attributes as Router;

#[Router\Prefix('api')]
final class CategoryTreeAction
{
    public function __construct(
        private ResolveApiResponse $resolveApiResponse,
    ) {
    }

    #[Router\Get('/category/tree')]
    public function __invoke(): JsonResponse
    {
        /** @var Category[] $categories */
        $categories = Category::get()->toTree()->all();

        return ($this->resolveApiResponse)($this->getTreeData($categories));
    }

    /**
     * @param Category[] $categories
     *
     * @return \Iterator
     */
    private function getTreeData(array $categories): iterable
    {
        foreach ($categories as $category) {
            yield new CategoryTreeResponse(
                $category->id,
                $category->title,
                $this->getTreeData($category->children()->getResults()->all()),
                $category->created_at,
                $category->updated_at,
            );
        }
    }
}
