<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\PaymentRepository;
use App\Entity\Traits\TimestampableTrait;

#[ORM\Entity(repositoryClass: PaymentRepository::class)]
class Payment
{
    use TimestampableTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?float $price = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $filename = null;

    #[ORM\OneToOne(mappedBy: 'payment', cascade: ['persist', 'remove'])]
    private ?Booking $booking = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getFilename(): ?string
    {
        return $this->filename;
    }

    public function setFilename(?string $filename): self
    {
        $this->filename = $filename;

        return $this;
    }

    public function getBooking(): ?Booking
    {
        return $this->booking;
    }

    public function setBooking(?Booking $booking): self
    {
        // unset the owning side of the relation if necessary
        if ($booking === null && $this->booking !== null) {
            $this->booking->setPayment(null);
        }

        // set the owning side of the relation if necessary
        if ($booking !== null && $booking->getPayment() !== $this) {
            $booking->setPayment($this);
        }

        $this->booking = $booking;

        return $this;
    }
}
