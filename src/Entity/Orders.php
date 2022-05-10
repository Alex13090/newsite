<?php

namespace App\Entity;

use App\Repository\OrdersRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OrdersRepository::class)]
class Orders
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 20, unique: true)]
    private $reference;

    #[ORM\Column(type: 'datetime_immutable', options: ['default' => 'CURRENT_TIMESTAMP'])]
    private $created_at;

    #[ORM\OneToMany(mappedBy: 'orders', targetEntity: OrdersDetails::class, orphanRemoval: true)]
    private $products;

    public function __construct()
    {
        $this->products = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getReference(): ?string
    {
        return $this->reference;
    }

    public function setReference(string $reference): self
    {
        $this->reference = $reference;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeImmutable $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }

    /**
     * @return Collection<int, OrdersDetails>
     */
    public function getProducts(): Collection
    {
        return $this->products;
    }

    public function addProduct(OrdersDetails $product): self
    {
        if (!$this->products->contains($product)) {
            $this->products[] = $product;
            $product->setOrders($this);
        }

        return $this;
    }

    public function removeProduct(OrdersDetails $product): self
    {
        if ($this->products->removeElement($product)) {
            // set the owning side to null (unless already changed)
            if ($product->getOrders() === $this) {
                $product->setOrders(null);
            }
        }

        return $this;
    }
}
