<?php

declare(strict_types=1);

namespace App\Module\Products\Model;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Kalnoy\Nestedset\Collection;
use Kalnoy\Nestedset\NodeTrait;

/**
 * @property int $id
 * @property string $title
 * @property ?int $parent_id
 * @property DateTimeInterface $created_at
 * @property DateTimeInterface $updated_at
 *
 * @method static Builder where($column, $operator = null, $value = null, $boolean = 'and')
 * @method static Collection|Category get($columns = ['*'])
 * @method static Collection|Category toTree()
 */
final class Category extends Model
{
    use NodeTrait;
}
