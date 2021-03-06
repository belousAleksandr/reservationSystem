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
    use TimestampableTrait;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="HallSession", inversedBy="rows")
     * @ORM\JoinColumn(nullable=false)
     */
    private $hallSession;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Seat", mappedBy="row",cascade={"persist", "remove"}, orphanRemoval=true)
     */
    private $seats;

    public function __construct()
    {
        $this->seats = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getHallSession(): ?HallSession
    {
        return $this->hallSession;
    }

    public function setHallSession(?HallSession $hallSession): self
    {
        $this->hallSession = $hallSession;

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
            $seat->setRow($this);
        }

        return $this;
    }

    public function removeSeat(Seat $seat): self
    {
        if ($this->seats->contains($seat)) {
            $this->seats->removeElement($seat);
            // set the owning side to null (unless already changed)
            if ($seat->getRow() === $this) {
                $seat->setRow(null);
            }
        }

        return $this;
    }

}
