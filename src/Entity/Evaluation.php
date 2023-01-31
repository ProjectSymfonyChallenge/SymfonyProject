<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\EvaluationRepository;
use App\Entity\Traits\TimestampableTrait;

#[ORM\Entity(repositoryClass: EvaluationRepository::class)]
class Evaluation
{
    use TimestampableTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 15, nullable: true)]
    private ?string $effort = null;

    #[ORM\Column(length: 15, nullable: true)]
    private ?string $beauty = null;

    #[ORM\Column(type: Types::TIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $duration = null;

    #[ORM\ManyToOne(inversedBy: 'evaluations')]
    private ?Hike $hike = null;

    #[ORM\ManyToOne(inversedBy: 'evaluations')]
    private ?User $user = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEffort(): ?string
    {
        return $this->effort;
    }

    public function setEffort(?string $effort): self
    {
        $this->effort = $effort;

        return $this;
    }

    public function getBeauty(): ?string
    {
        return $this->beauty;
    }

    public function setBeauty(?string $beauty): self
    {
        $this->beauty = $beauty;

        return $this;
    }

    public function getDuration(): ?\DateTimeInterface
    {
        return $this->duration;
    }

    public function setDuration(?\DateTimeInterface $duration): self
    {
        $this->duration = $duration;

        return $this;
    }

    public function getHike(): ?Hike
    {
        return $this->hike;
    }

    public function setHike(?Hike $hike): self
    {
        $this->hike = $hike;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }
}
