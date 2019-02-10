<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\SeatRepository")
 */
class Seat
{
    const STATUS_ENABLED = 'enabled';
    const STATUS_BOOKED = 'booked';
    const STATUS_DISABLED = 'disabled';

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $price;

    /**
     * @ORM\Column(type="string", length=10)
     */
    private $status = self::STATUS_ENABLED;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Row", inversedBy="seats")
     * @ORM\JoinColumn(nullable=false)
     */
    private $row;

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

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getRow(): ?Row
    {
        return $this->row;
    }

    public function setRow(?Row $row): self
    {
        $this->row = $row;

        return $this;
    }
}
