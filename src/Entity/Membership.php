<?php

namespace App\Entity;

use App\Repository\MembershipRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MembershipRepository::class)]
class Membership
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;


    #[ORM\ManyToOne(targetEntity: User::class,inversedBy: 'memberships')]
    #[ORM\JoinColumn(nullable: false)]
    private ?string $user = null;

    #[ORM\ManyToOne(targetEntity: Club::class,inversedBy: 'memberships')]
    #[ORM\JoinColumn(nullable: false)]
    private ?string $club = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): string
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getClub(): string
    {
        return $this->club;
    }

    public function setClub(?Club $club): self
    {
        $this->club = $club;

        return $this;
    }

}
