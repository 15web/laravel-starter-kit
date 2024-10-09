<?php

declare(strict_types=1);

namespace App\Module\Blog\Model;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $title
 * @property string $author
 * @property string $content
 * @property DateTimeInterface $created_at
 * @property DateTimeInterface $updated_at
 */
final class Post extends Model
{
    public function create(string $title, string $author, string $content): void
    {
        $this->title = $title;
        $this->author = $author;
        $this->content = $content;
    }
}
