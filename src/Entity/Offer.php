<?php

namespace App\Entity;

use ApiPlatform\Doctrine\Orm\Filter\OrderFilter;
use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use App\Enum\OfferStatus;
use App\Repository\OfferRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: OfferRepository::class)]
#[ApiResource(
    normalizationContext: ['groups' => ['read_offer']],
    denormalizationContext: ['groups' => ['write_offer']],
    security: "is_granted('ROLE_USER')"
)]
#[GetCollection()]
#[Get()]
#[ApiFilter(SearchFilter::class, properties: [
    'title' => 'ipartial',
    'contractType' => 'exact',
    'company.name' => 'ipartial'
])]
#[ApiFilter(OrderFilter::class, properties: ['salary', 'publicationDate'])]
#[Post(security: "is_granted('ROLE_COMPANY')")]
#[Patch(security: "is_granted('ROLE_COMPANY')")]
#[Delete(security: "is_granted('ROLE_COMPANY')")]
class Offer
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['read_offer'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['read_offer', 'write_offer'])]
    private ?string $title = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Groups(['read_offer', 'write_offer'])]
    private ?string $description = null;

    #[ORM\Column(length: 255)]
    #[Groups(['read_offer', 'write_offer'])]
    private ?string $contractType = null;

    #[ORM\Column]
    #[Groups(['read_offer', 'write_offer'])]
    private ?float $salary = null;

    #[ORM\Column]
    #[Groups(['read_offer', 'write_offer'])]
    private ?\DateTime $publicationDate = null;

    #[ORM\Column(enumType: OfferStatus::class)]
    #[Groups(['read_offer', 'write_offer'])]
    private ?OfferStatus $status = null;

    #[ORM\ManyToOne(inversedBy: 'offers')]
    #[Groups(['read_offer'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?Company $company = null;

    /**
     * @var Collection<int, Tag>
     */
    #[ORM\ManyToMany(targetEntity: Tag::class, inversedBy: 'offers')]
    #[Groups(['read_offer'])]
    private Collection $tags;

    /**
     * @var Collection<int, Application>
     */
    #[ORM\OneToMany(targetEntity: Application::class, mappedBy: 'offer')]
    private Collection $applications;

    public function __construct()
    {
        $this->tags = new ArrayCollection();
        $this->applications = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getContractType(): ?string
    {
        return $this->contractType;
    }

    public function setContractType(string $contractType): static
    {
        $this->contractType = $contractType;

        return $this;
    }

    public function getSalary(): ?float
    {
        return $this->salary;
    }

    public function setSalary(float $salary): static
    {
        $this->salary = $salary;

        return $this;
    }

    public function getPublicationDate(): ?\DateTime
    {
        return $this->publicationDate;
    }

    public function setPublicationDate(\DateTime $publicationDate): static
    {
        $this->publicationDate = $publicationDate;

        return $this;
    }

    public function getStatus(): ?OfferStatus
    {
        return $this->status;
    }

    public function setStatus(OfferStatus $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getCompany(): ?Company
    {
        return $this->company;
    }

    public function setCompany(?Company $company): static
    {
        $this->company = $company;

        return $this;
    }

    /**
     * @return Collection<int, Tag>
     */
    public function getTags(): Collection
    {
        return $this->tags;
    }

    public function addTag(Tag $tag): static
    {
        if (!$this->tags->contains($tag)) {
            $this->tags->add($tag);
        }

        return $this;
    }

    public function removeTag(Tag $tag): static
    {
        $this->tags->removeElement($tag);

        return $this;
    }

    /**
     * @return Collection<int, Application>
     */
    public function getApplications(): Collection
    {
        return $this->applications;
    }

    public function addApplication(Application $application): static
    {
        if (!$this->applications->contains($application)) {
            $this->applications->add($application);
            $application->setOffer($this);
        }

        return $this;
    }

    public function removeApplication(Application $application): static
    {
        if ($this->applications->removeElement($application)) {
            // set the owning side to null (unless already changed)
            if ($application->getOffer() === $this) {
                $application->setOffer(null);
            }
        }

        return $this;
    }
}
