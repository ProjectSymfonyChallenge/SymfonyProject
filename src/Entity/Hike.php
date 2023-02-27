<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\HikeRepository;
use Gedmo\Mapping\Annotation\Slug;
use App\Entity\Traits\TimestampableTrait;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints\Range;

#[ORM\Entity(repositoryClass: HikeRepository::class)]
class Hike
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

    #[ORM\ManyToOne(inversedBy: 'hikes')]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    private ?Club $club = null;

    #[ORM\OneToMany(mappedBy: 'hike', targetEntity: Evaluation::class)]
    private Collection $evaluations;

    #[ORM\Column]
    #[Range(min: 1, max: 100, notInRangeMessage: 'La distance doit être comprise entre 1Km et 100Km')]
    private ?float $distance = null;

    #[ORM\Column(type: Types::TIME_MUTABLE)]
    private ?\DateTimeInterface $duration = null;

    #[ORM\OneToMany(mappedBy: 'hike', targetEntity: Picture::class)]
    private Collection $pictures;

    #[ORM\Column(length: 255)]
    #[Range(min: 1, max: 10, notInRangeMessage: 'L\'effort doit être compris entre 1 et 10')]
    private ?string $effort = null;

    #[ORM\OneToMany(mappedBy: 'hike', targetEntity: Booking::class)]
    private Collection $bookings;

    #[ORM\Column]
    #[Range(min: 1, max: 40, notInRangeMessage: 'Le nombre de participants doit être compris entre 1 et 40')]
    private ?int $max_users = null;

    #[ORM\Column(length: 255)]
    #[Slug(fields: ['name'])]
    private ?string $slug = null;

    #[ORM\OneToMany(mappedBy: 'hike', targetEntity: Comment::class)]
    private Collection $comments;

    #[ORM\ManyToOne(inversedBy: 'hike')]
    private ?Locality $locality = null;

    #[ORM\Column(type: 'decimal', precision: 10, scale: 7)]
    private $longitude;

    #[ORM\Column(type: 'decimal', precision: 10, scale: 7)]
    private $latitude;

    public function __construct()
    {
        $this->evaluations = new ArrayCollection();
        $this->pictures = new ArrayCollection();
        $this->bookings = new ArrayCollection();
        $this->comments = new ArrayCollection();
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

    public function getClub(): ?Club
    {
        return $this->club;
    }

    public function setClub(?Club $club): self
    {
        $this->club = $club;

        return $this;
    }

    /**
     * @return Collection<int, Evaluation>
     */
    public function getEvaluations(): Collection
    {
        return $this->evaluations;
    }

    public function addEvaluation(Evaluation $evaluation): self
    {
        if (!$this->evaluations->contains($evaluation)) {
            $this->evaluations->add($evaluation);
            $evaluation->setHike($this);
        }

        return $this;
    }

    public function removeEvaluation(Evaluation $evaluation): self
    {
        if ($this->evaluations->removeElement($evaluation)) {
            // set the owning side to null (unless already changed)
            if ($evaluation->getHike() === $this) {
                $evaluation->setHike(null);
            }
        }

        return $this;
    }

    public function getDistance(): ?float
    {
        return $this->distance;
    }

    public function setDistance(float $distance): self
    {
        $this->distance = $distance;

        return $this;
    }

    public function getDuration(): ?\DateTimeInterface
    {
        return $this->duration;
    }

    public function setDuration(\DateTimeInterface $duration): self
    {
        $this->duration = $duration;

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
            $picture->setHike($this);
        }

        return $this;
    }

    public function removePicture(Picture $picture): self
    {
        if ($this->pictures->removeElement($picture)) {
            // set the owning side to null (unless already changed)
            if ($picture->getHike() === $this) {
                $picture->setHike(null);
            }
        }

        return $this;
    }

    public function getEffort(): ?string
    {
        return $this->effort;
    }

    public function setEffort(string $effort): self
    {
        $this->effort = $effort;

        return $this;
    }

    /**
     * @return Collection<int, Booking>
     */
    public function getBookings(): Collection
    {
        return $this->bookings;
    }

    public function addBooking(Booking $booking): self
    {
        if (!$this->bookings->contains($booking)) {
            $this->bookings->add($booking);
            $booking->setHike($this);
        }

        return $this;
    }

    public function removeBooking(Booking $booking): self
    {
        if ($this->bookings->removeElement($booking)) {
            // set the owning side to null (unless already changed)
            if ($booking->getHike() === $this) {
                $booking->setHike(null);
            }
        }

        return $this;
    }

    public function getMaxUsers(): ?int
    {
        return $this->max_users;
    }

    public function setMaxUsers(int $max_users): self
    {
        $this->max_users = $max_users;

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

    /**
     * @return Collection<int, Comment>
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comment $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments->add($comment);
            $comment->setHike($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): self
    {
        if ($this->comments->removeElement($comment)) {
            // set the owning side to null (unless already changed)
            if ($comment->getHike() === $this) {
                $comment->setHike(null);
            }
        }

        return $this;
    }

    public function getLocality(): ?Locality
    {
        return $this->locality;
    }

    public function setLocality(?Locality $locality): self
    {
        $this->locality = $locality;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getLongitude()
    {
        return $this->longitude;
    }

    /**
     * @param mixed $longitude
     */
    public function setLongitude(float $longitude): self
    {
        $this->longitude = $longitude;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getLatitude()
    {
        return $this->latitude;
    }

    /**
     * @param mixed $latitude
     */
    public function setLatitude(float $latitude): self
    {
        $this->latitude = $latitude;

        return $this;
    }

}
