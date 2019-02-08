<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RowRepository")
 */
class Row
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToMany(targetEntity="Seat", mappedBy="row")
     */
    private $seats;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Seans", inversedBy="rows")
     * @ORM\JoinColumn(nullable=false)
     */
    private $seans;

    public function __construct()
    {
        $this->seats = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getSeats(): Collection
    {
        return $this->seats;
    }

    public function getSeans(): ?Seans
    {
        return $this->seans;
    }

    public function setSeans(?Seans $seans): self
    {
        $this->seans = $seans;

        return $this;
    }

}
