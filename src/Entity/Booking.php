<?php

namespace App\Entity;

use App\Entity\Traits\TimestampableTrait;
use App\Repository\BookingRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BookingRepository::class)]
class Booking
{
    use TimestampableTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'bookings')]
    private ?User $user = null;

    #[ORM\ManyToOne(inversedBy: 'bookings')]
    private ?Hike $hike = null;

    #[ORM\OneToOne(inversedBy: 'booking', cascade: ['persist', 'remove'])]
    private ?Payment $payment = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $hike_date = null;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getHike(): ?Hike
    {
        return $this->hike;
    }

    public function setHike(?Hike $hike): self
    {
        $this->hike = $hike;

        return $this;
    }

    public function getPayment(): ?Payment
    {
        return $this->payment;
    }

    public function setPayment(?Payment $payment): self
    {
        $this->payment = $payment;

        return $this;
    }

    public function getHikeDate(): ?\DateTimeInterface
    {
        return $this->hike_date;
    }

    public function setHikeDate(\DateTimeInterface $hike_date): self
    {
        $this->hike_date = $hike_date;

        return $this;
    }
}
