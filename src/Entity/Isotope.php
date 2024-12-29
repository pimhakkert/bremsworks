<?php

namespace App\Entity;

use App\Repository\IsotopeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: IsotopeRepository::class)]
class Isotope
{
    /*Isotope names*/
    const AMERICIUM_241 = 'Americium-241';
    const CESIUM_137 = 'CESIUM-137';
    const COBALT_60 = 'Cobalt-60';
    const STRONTIUM_90 = 'Strontium-90';
    const URANIUM_235 = 'Uranium-235';
    const URANIUM_238 = 'Uranium-238';
    const THORIUM_232 = 'Thorium-232';

    /*Isotope shorthands*/
    const AM_241 = 'Am-241';
    const CS_137 = 'Cs-137';
    const CO_60 = 'Co-60';
    const SR_90 = 'Sr-90';
    const U_235 = 'U-235';
    const U_238 = 'U-238';
    const TH_232 = 'Th-232';

    const ALL_NAMES = [
        self::AMERICIUM_241,
        self::CESIUM_137,
        self::COBALT_60,
        self::STRONTIUM_90,
        self::URANIUM_235,
        self::URANIUM_238,
        self::THORIUM_232
    ];

    const ALL_SHORTHANDS = [
        self::AM_241,
        self::CS_137,
        self::CO_60,
        self::SR_90,
        self::U_235,
        self::U_238,
        self::TH_232
    ];

    const ALL_SHORTHANDS_TO_NAMES = [
        self::AM_241 => self::AMERICIUM_241,
        self::CS_137 => self::CESIUM_137,
        self::CO_60 => self::COBALT_60,
        self::SR_90 => self::STRONTIUM_90,
        self::U_235 => self::URANIUM_235,
        self::U_238 => self::URANIUM_238,
        self::TH_232 => self::THORIUM_232
    ];

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $shorthand = null;

    /**
     * @var Collection<int, Product>
     */
    #[ORM\ManyToMany(targetEntity: Product::class, inversedBy: 'isotopes')]
    private Collection $products;

    public function __construct()
    {
        $this->products = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        if(!in_array($name, self::ALL_NAMES, true)) {
            throw new \Exception("Illegal isotope name: " . $name);
        }

        $this->name = $name;

        return $this;
    }

    public function getShorthand(): ?string
    {
        return $this->shorthand;
    }

    public function setShorthand(string $shorthand): static
    {
        if(!in_array($shorthand, self::ALL_SHORTHANDS, true)) {
            throw new \Exception("Illegal isotope shorthand: " . $shorthand);
        }

        $this->shorthand = $shorthand;

        return $this;
    }

    /**
     * @return Collection<int, Product>
     */
    public function getProducts(): Collection
    {
        return $this->products;
    }

    public function addProduct(Product $product): static
    {
        if (!$this->products->contains($product)) {
            $this->products->add($product);
        }

        return $this;
    }

    public function removeProduct(Product $product): static
    {
        $this->products->removeElement($product);

        return $this;
    }
}
