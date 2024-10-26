<?php

declare(strict_types=1);

namespace App\Products\Domain;

use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\Tree\Entity\Repository\NestedTreeRepository;

/**
 * Категория товаров
 *
 * @final
 */
#[Gedmo\Tree(type: 'nested')]
#[ORM\Table(name: 'product_categories')]
#[ORM\Entity(repositoryClass: NestedTreeRepository::class)]
class Category
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private int $id;

    #[Gedmo\TreeLeft]
    #[ORM\Column(name: 'lft', nullable: true)]
    private ?int $lft = null;

    #[Gedmo\TreeLevel]
    #[ORM\Column(name: 'lvl', nullable: true)]
    private ?int $lvl = null;

    #[Gedmo\TreeRight]
    #[ORM\Column(name: 'rgt', nullable: true)]
    private ?int $rgt = null;

    #[Gedmo\TreeRoot]
    #[ORM\ManyToOne(targetEntity: self::class)]
    #[ORM\JoinColumn(name: 'treeRoot', referencedColumnName: 'id', onDelete: 'CASCADE')]
    private ?self $root = null;

    /**
     * @var Collection<int, self>
     */
    #[ORM\OneToMany(targetEntity: self::class, mappedBy: 'parent', fetch: 'EAGER')]
    #[ORM\OrderBy(['lft' => 'ASC'])]
    private Collection $children;

    #[ORM\Column]
    private readonly DateTimeImmutable $createdAt;

    #[ORM\Column(nullable: true)]
    private ?DateTimeImmutable $updatedAt;

    public function __construct(
        #[ORM\Column]
        private string $title,
        #[Gedmo\TreeParent]
        #[ORM\ManyToOne(targetEntity: self::class, inversedBy: 'children')]
        #[ORM\JoinColumn(name: 'parentId', referencedColumnName: 'id', onDelete: 'CASCADE')]
        private ?self $parent = null,
    ) {
        /** @var Collection<int, self> $children */
        $children = new ArrayCollection();
        $this->children = $children;

        $this->createdAt = new DateTimeImmutable();
        $this->updatedAt = null;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): ?DateTimeImmutable
    {
        return $this->updatedAt;
    }
}
