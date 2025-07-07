<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use App\Enum\ApplicationStatus;
use App\Repository\ApplicationRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: ApplicationRepository::class)]
#[ApiResource(
    normalizationContext: ['groups' => ['read_application']],
    denormalizationContext: ['groups' => ['write_application']]
)]
#[GetCollection(security: "is_granted('ROLE_USER')")]
#[Get(security: "object.getCandidate().getUser() == user")]
#[Post(security: "is_granted('ROLE_USER')")]
#[Patch(security: "object.getCandidate().getUser() == user")]
#[Delete(security: "object.getCandidate().getUser() == user")]


class Application
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['read_application'])]
    private ?int $id = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Groups(['read_application', 'write_application'])]
    private ?string $message = null;

    #[ORM\Column]
    #[Groups(['read_application', 'write_application'])]
    private ?\DateTime $applicationDate = null;

    #[ORM\Column(enumType: ApplicationStatus::class)]
    #[Groups(['read_application', 'write_application'])]
    private ?ApplicationStatus $status = null;

    #[ORM\ManyToOne(inversedBy: 'applications')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['read_application', 'write_application'])]
    private ?Offer $offer = null;

    #[ORM\ManyToOne(inversedBy: 'applications')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['read_application', 'write_application'])]
    private ?Candidate $candidate = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(string $message): static
    {
        $this->message = $message;

        return $this;
    }

    public function getApplicationDate(): ?\DateTime
    {
        return $this->applicationDate;
    }

    public function setApplicationDate(\DateTime $applicationDate): static
    {
        $this->applicationDate = $applicationDate;

        return $this;
    }

    public function getStatus(): ?ApplicationStatus
    {
        return $this->status;
    }

    public function setStatus(ApplicationStatus $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getOffer(): ?Offer
    {
        return $this->offer;
    }

    public function setOffer(?Offer $offer): static
    {
        $this->offer = $offer;

        return $this;
    }

    public function getCandidate(): ?Candidate
    {
        return $this->candidate;
    }

    public function setCandidate(?Candidate $candidate): static
    {
        $this->candidate = $candidate;

        return $this;
    }
}
