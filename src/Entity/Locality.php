<?php

namespace App\Entity;

use App\Repository\LocalityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LocalityRepository::class)]
class Locality
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $region = null;

    #[ORM\Column(length: 255)]
    private ?string $department = null;

    #[ORM\OneToMany(mappedBy: 'locality', targetEntity: Hike::class)]
    private Collection $hike;

    #[ORM\OneToMany(mappedBy: 'locality', targetEntity: User::class)]
    private Collection $user;

    public function __construct()
    {
        $this->hike = new ArrayCollection();
        $this->user = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRegion(): ?string
    {
        return $this->region;
    }

    public function setRegion(string $region): self
    {
        $this->region = $region;

        return $this;
    }

    public function getDepartment(): ?string
    {
        return $this->department;
    }

    public function setDepartment(string $department): self
    {
        $this->department = $department;

        return $this;
    }

    /**
     * @return Collection<int, Hike>
     */
    public function getHike(): Collection
    {
        return $this->hike;
    }

    public function addHike(Hike $hike): self
    {
        if (!$this->hike->contains($hike)) {
            $this->hike->add($hike);
            $hike->setLocality($this);
        }

        return $this;
    }

    public function removeHike(Hike $hike): self
    {
        if ($this->hike->removeElement($hike)) {
            // set the owning side to null (unless already changed)
            if ($hike->getLocality() === $this) {
                $hike->setLocality(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getUser(): Collection
    {
        return $this->user;
    }

    public function addUserId(User $user): self
    {
        if (!$this->user->contains($user)) {
            $this->user->add($user);
            $user->setLocality($this);
        }

        return $this;
    }

    public function removeUserId(User $user): self
    {
        if ($this->user->removeElement($user)) {
            // set the owning side to null (unless already changed)
            if ($user->getLocality() === $this) {
                $user->setLocality(null);
            }
        }

        return $this;
    }

    public function addUser(User $user): self
    {
        if (!$this->user->contains($user)) {
            $this->user->add($user);
            $user->setLocality($this);
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->user->removeElement($user)) {
            // set the owning side to null (unless already changed)
            if ($user->getLocality() === $this) {
                $user->setLocality(null);
            }
        }

        return $this;
    }
}
