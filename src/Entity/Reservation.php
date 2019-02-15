<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ReservationRepository")
 */
class Reservation
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Cinema")
     * @ORM\JoinColumn(nullable=false)
     */
    private $cinema;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Seans")
     * @ORM\JoinColumn(nullable=false)
     */
    private $session;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Seat", mappedBy="reservation")
     */
    private $seats;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\ReservationOwner", cascade={"persist"}, inversedBy="reservations")
     * @ORM\JoinColumn(nullable=false)
     */
    private $reservationOwner;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Payment", cascade={"persist"})
     */
    private $payment;

    /**
     * @ORM\Column(type="float")
     */
    private $summaryPrice;

    public function __construct()
    {
        $this->seats = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCinema(): ?Cinema
    {
        return $this->cinema;
    }

    public function setCinema(?Cinema $cinema): self
    {
        $this->cinema = $cinema;

        return $this;
    }

    public function getSession(): ?Seans
    {
        return $this->session;
    }

    public function setSession(?Seans $session): self
    {
        $this->session = $session;

        return $this;
    }

    /**
     * @return Collection|Seat[]
     */
    public function getSeats(): Collection
    {
        return $this->seats;
    }

    public function addSeat(Seat $seat): self
    {
        if (!$this->seats->contains($seat)) {
            $this->seats[] = $seat;
            $seat->setReservation($this);
        }

        return $this;
    }

    public function removeSeat(Seat $seat): self
    {
        if ($this->seats->contains($seat)) {
            $this->seats->removeElement($seat);
            // set the owning side to null (unless already changed)
            if ($seat->getReservation() === $this) {
                $seat->setReservation(null);
            }
        }

        return $this;
    }

    public function getReservationOwner(): ?ReservationOwner
    {
        return $this->reservationOwner;
    }

    public function setReservationOwner(?ReservationOwner $reservationOwner): self
    {
        $this->reservationOwner = $reservationOwner;

        return $this;
    }

    public function getPayment(): ?Payment
    {
        return $this->payment;
    }

    public function setPayment(Payment $payment): self
    {
        $this->payment = $payment;

        return $this;
    }

    public function getSummaryPrice(): ?float
    {
        return $this->summaryPrice;
    }

    public function setSummaryPrice(float $summaryPrice): self
    {
        $this->summaryPrice = $summaryPrice;

        return $this;
    }
}
