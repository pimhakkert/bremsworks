<?php

namespace App\Entity;

use App\Repository\ProductRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ProductRepository::class)]
class Product
{
    /* Conditions */
    const CONDITION_NEW_IN_BOX = 'New in box';
    const CONDITION_NEW = 'New';
    const CONDITION_REFURBISHED = 'Refurbished';
    const CONDITION_USED = 'Used';
    const CONDITION_PARTS = 'Parts';

    const ALL_CONDITIONS = [
        self::CONDITION_NEW_IN_BOX,
        self::CONDITION_NEW,
        self::CONDITION_REFURBISHED,
        self::CONDITION_USED,
        self::CONDITION_PARTS
    ];

    /* Categories */
    const CATEGORY_MINERALS = 'Minerals';
    const CATEGORY_SMOKE_ALARMS = 'Smoke alarms';
    const CATEGORY_DIALS = 'Dials';
    const CATEGORY_CERAMICS = 'Ceramics';
    const CATEGORY_ELECTRONIC_COMPONENTS = 'Electronic components';
    const MISCELLANEOUS = 'Miscellaneous';

    const ALL_CATEGORIES = [
        self::CATEGORY_MINERALS,
        self::CATEGORY_SMOKE_ALARMS,
        self::CATEGORY_DIALS,
        self::CATEGORY_CERAMICS,
        self::CATEGORY_ELECTRONIC_COMPONENTS,
        self::MISCELLANEOUS
    ];

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    #[Gedmo\Timestampable]
    public ?\DateTimeImmutable $updatedAt = null;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    public ?\DateTimeImmutable $createdAt = null;

    #[ORM\ManyToOne]
    private ?User $reservedBy = null;

    #[Assert\Length(min: 15, max: 255)]
    #[ORM\Column(length: 255)]
    private string $title;

    #[Assert\Length(min: 30)]
    #[ORM\Column(type: 'text')]
    private string $description;

    #[ORM\Column]
    private int $quantity;

    #[Assert\GreaterThanOrEqual(100)]
    #[ORM\Column]
    private int $price;

    #[ORM\Column(length: 255)]
    private string $category;

    /**
     * @var Collection<int, Isotope>
     */
    #[ORM\ManyToMany(targetEntity: Isotope::class, inversedBy: 'products')]
    private Collection $isotopes;

    #[ORM\Column]
    private int $width;

    #[ORM\Column]
    private int $height;

    #[ORM\Column]
    private int $depth;

    #[ORM\Column]
    private int $weight;

    #[ORM\Column()]
    private string $color;

    #[ORM\Column()]
    private string $brand;

    #[ORM\Column()]
    private string $model;

    #[ORM\Column()]
    private string $condition;

    public function __construct()
    {
        $this->isotopes = new ArrayCollection();
        $this->createdAt = new \DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): Product
    {
        $this->id = $id;
        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeImmutable $updatedAt): Product
    {
        $this->updatedAt = $updatedAt;
        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getReservedBy(): ?User
    {
        return $this->reservedBy;
    }

    public function setReservedBy(?User $reservedBy): Product
    {
        $this->reservedBy = $reservedBy;
        return $this;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): Product
    {
        $this->title = $title;
        return $this;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): Product
    {
        $this->description = $description;
        return $this;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): Product
    {
        $this->quantity = $quantity;
        return $this;
    }

    public function getPrice($inCents = false): int|float
    {
        if(!$inCents) {
            return $this->price / 100.0;
        }
        return $this->price;
    }

    public function setPrice(int $price): Product
    {
        $this->price = $price;
        return $this;
    }

    public function getCategory(): string
    {
        return $this->category;
    }

    public function setCategory(string $category): Product
    {
        if(!in_array($category, self::ALL_CATEGORIES, true)) {
            throw new \Exception("Illegal product category: " . $category);
        }

        $this->category = $category;
        return $this;
    }

    public function getWidth(): int
    {
        return $this->width;
    }

    public function setWidth(int $width): Product
    {
        $this->width = $width;
        return $this;
    }

    public function getHeight(): int
    {
        return $this->height;
    }

    public function setHeight(int $height): Product
    {
        $this->height = $height;
        return $this;
    }

    public function getDepth(): int
    {
        return $this->depth;
    }

    public function setDepth(int $depth): Product
    {
        $this->depth = $depth;
        return $this;
    }

    public function getWeight(): int
    {
        return $this->weight;
    }

    public function setWeight(int $weight): Product
    {
        $this->weight = $weight;
        return $this;
    }

    public function getColor(): string
    {
        return $this->color;
    }

    public function setColor(string $color): Product
    {
        $this->color = $color;
        return $this;
    }

    public function getBrand(): string
    {
        return $this->brand;
    }

    public function setBrand(string $brand): Product
    {
        $this->brand = $brand;
        return $this;
    }

    public function getModel(): string
    {
        return $this->model;
    }

    public function setModel(string $model): Product
    {
        $this->model = $model;
        return $this;
    }

    public function getCondition(): string
    {
        return $this->condition;
    }

    public function setCondition(string $condition): Product
    {
        if(!in_array($condition, self::ALL_CONDITIONS, true)) {
            throw new \Exception("Illegal product condition: " . $condition);
        }

        $this->condition = $condition;
        return $this;
    }

    /**
     * @return Collection<int, Isotope>
     */
    public function getIsotopes(): Collection
    {
        return $this->isotopes;
    }

    public function addIsotope(Isotope $isotope): static
    {
        if (!$this->isotopes->contains($isotope)) {
            $this->isotopes->add($isotope);
            $isotope->addProduct($this);
        }

        return $this;
    }

    public function removeIsotope(Isotope $isotope): static
    {
        if ($this->isotopes->removeElement($isotope)) {
            $isotope->removeProduct($this);
        }

        return $this;
    }
}
