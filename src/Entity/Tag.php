<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use App\Repository\TagRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: TagRepository::class)]
#[ApiResource(
    normalizationContext: ['groups' => ['read_tag']],
    denormalizationContext: ['groups' => ['write_tag']],
    security: "is_granted('ROLE_ADMIN')"
)]
#[GetCollection(security: "is_granted('ROLE_USER')")]
#[Get(security: "is_granted('ROLE_USER')")]
#[Post(security: "is_granted('ROLE_ADMIN')")]
#[Patch(security: "is_granted('ROLE_ADMIN')")]
#[Delete(security: "is_granted('ROLE_ADMIN')")]
class Tag
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['read_tag', 'read_offer'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['read_tag', 'write_tag', 'read_offer'])]
    private ?string $label = null;

    /**
     * @var Collection<int, Offer>
     */
    #[ORM\ManyToMany(targetEntity: Offer::class, mappedBy: 'tags')]
    #[Groups(['read_tag'])]
    private Collection $offers;

    public function __construct()
    {
        $this->offers = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLabel(): ?string
    {
        return $this->label;
    }

    public function setLabel(string $label): static
    {
        $this->label = $label;

        return $this;
    }

    /**
     * @return Collection<int, Offer>
     */
    public function getOffers(): Collection
    {
        return $this->offers;
    }

    public function addOffer(Offer $offer): static
    {
        if (!$this->offers->contains($offer)) {
            $this->offers->add($offer);
            $offer->addTag($this);
        }

        return $this;
    }

    public function removeOffer(Offer $offer): static
    {
        if ($this->offers->removeElement($offer)) {
            $offer->removeTag($this);
        }

        return $this;
    }
}
