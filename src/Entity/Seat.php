<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\SeatRepository")
 */
class Seat
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="Row")
     */
    private $row;

    /**
     * @ORM\Column(type="smallint")
     */
    private $position;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPosition(): ?int
    {
        return $this->position;
    }

    public function setPosition(int $position): self
    {
        $this->position = $position;

        return $this;
    }

    /**
     * @return Row|null
     */
    public function getRow(): ?Row
    {
        return $this->row;
    }

    /**
     * @param Row $row
     * @return Seat
     */
    public function setRow(Row $row): self
    {
        $this->row = $row;

        return $this;
    }
}
