<?php

namespace App\Entity;

use App\Entity\Traits\TimestampableTrait;
use App\Repository\ClubRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation\Slug;

#[ORM\Entity(repositoryClass: ClubRepository::class)]
class Club
{
    use TimestampableTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\ManyToOne(inversedBy: 'clubs')]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    private ?User $manager = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $adresse = null;

    #[ORM\OneToMany(mappedBy: 'club', targetEntity: Hike::class, orphanRemoval: true)]
    private Collection $hikes;

    #[ORM\OneToMany(mappedBy: 'club', targetEntity: Picture::class)]
    private Collection $pictures;

    #[ORM\Column(length: 255)]
    #[Slug(fields: ['name'])]
    private ?string $slug = null;

    public function __construct()
    {
        $this->hikes = new ArrayCollection();
        $this->pictures = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getManager(): ?User
    {
        return $this->manager;
    }

    public function setManager(?User $manager): self
    {
        $this->manager = $manager;

        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(?string $adresse): self
    {
        $this->adresse = $adresse;

        return $this;
    }

    /**
     * @return Collection<int, Hike>
     */
    public function getHikes(): Collection
    {
        return $this->hikes;
    }

    public function addHike(Hike $hike): self
    {
        if (!$this->hikes->contains($hike)) {
            $this->hikes->add($hike);
            $hike->setClub($this);
        }

        return $this;
    }

    public function removeHike(Hike $hike): self
    {
        if ($this->hikes->removeElement($hike)) {
            // set the owning side to null (unless already changed)
            if ($hike->getClub() === $this) {
                $hike->setClub(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Picture>
     */
    public function getPictures(): Collection
    {
        return $this->pictures;
    }

    public function addPicture(Picture $picture): self
    {
        if (!$this->pictures->contains($picture)) {
            $this->pictures->add($picture);
            $picture->setClub($this);
        }

        return $this;
    }

    public function removePicture(Picture $picture): self
    {
        if ($this->pictures->removeElement($picture)) {
            // set the owning side to null (unless already changed)
            if ($picture->getClub() === $this) {
                $picture->setClub(null);
            }
        }

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }
}
