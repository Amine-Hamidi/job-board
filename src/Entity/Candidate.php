<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use App\Repository\CandidateRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: CandidateRepository::class)]
#[ApiResource(
    normalizationContext: ['groups' => ['read_candidate']],
    denormalizationContext: ['groups' => ['write_candidate']]
)]
#[GetCollection(security: "is_granted('ROLE_ADMIN')")]
#[Get(security: "object.getUser() == user")]
#[Post(security: "is_granted('ROLE_USER')")]
#[Patch(security: "object.getUser() == user")]
#[Delete(security: "object.getUser() == user")]

class Candidate
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['read_candidate'])]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['read_candidate', 'write_candidate'])]
    private ?string $cv = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups(['read_candidate', 'write_candidate'])]
    private ?string $skills = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups(['read_candidate', 'write_candidate'])]
    private ?string $experience = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['read_candidate', 'write_candidate'])]
    private ?string $educationLevel = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['read_candidate', 'write_candidate'])]
    private ?string $availability = null;

    /**
     * @var Collection<int, Application>
     */
    #[ORM\OneToMany(targetEntity: Application::class, mappedBy: 'candidate', cascade: ['remove'], orphanRemoval: true)]
    #[Groups(['read_candidate'])]
    private Collection $applications;

    #[ORM\ManyToOne(inversedBy: 'candidates')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['read_candidate'])]
    private ?User $user = null;

    public function __construct()
    {
        $this->applications = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCv(): ?string
    {
        return $this->cv;
    }

    public function setCv(?string $cv): static
    {
        $this->cv = $cv;

        return $this;
    }

    public function getSkills(): ?string
    {
        return $this->skills;
    }

    public function setSkills(?string $skills): static
    {
        $this->skills = $skills;

        return $this;
    }

    public function getExperience(): ?string
    {
        return $this->experience;
    }

    public function setExperience(?string $experience): static
    {
        $this->experience = $experience;

        return $this;
    }

    public function getEducationLevel(): ?string
    {
        return $this->educationLevel;
    }

    public function setEducationLevel(?string $educationLevel): static
    {
        $this->educationLevel = $educationLevel;

        return $this;
    }

    public function getAvailability(): ?string
    {
        return $this->availability;
    }

    public function setAvailability(?string $availability): static
    {
        $this->availability = $availability;

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
            $application->setCandidate($this);
        }

        return $this;
    }

    public function removeApplication(Application $application): static
    {
        if ($this->applications->removeElement($application)) {
            // set the owning side to null (unless already changed)
            if ($application->getCandidate() === $this) {
                $application->setCandidate(null);
            }
        }

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }
}
