<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\HallRepository")
 */
class Hall
{
    use TimestampableTrait;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\ManyToOne(targetEntity="Cinema")
     */
    private $cinema;

    /**
     * @ORM\OneToMany(targetEntity="HallSession", mappedBy="hall", orphanRemoval=true)
     */
    private $hallSessions;

    /**
     * @ORM\Column(type="integer")
     */
    private $rows;

    /**
     * @ORM\Column(type="integer")
     */
    private $columns;

    public function __construct()
    {
        $this->hallSessions = new ArrayCollection();
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

    /**
     * @return Cinema|null
     */
    public function getCinema(): ?Cinema
    {
        return $this->cinema;
    }

    /**
     * @param Cinema $cinema
     * @return $this
     */
    public function setCinema(Cinema $cinema): self
    {
        $this->cinema = $cinema;

        return $this;
    }

    /**
     * @return Collection|HallSession[]
     */
    public function getHallSessions(): Collection
    {
        return $this->hallSessions;
    }

    /**
     * @param HallSession $hallSession
     * @return Hall
     */
    public function addSean(HallSession $hallSession): self
    {
        if (!$this->hallSessions->contains($hallSession)) {
            $this->hallSessions[] = $hallSession;
            $hallSession->setHall($this);
        }

        return $this;
    }

    /**
     * @param HallSession $hallSession
     * @return Hall
     */
    public function removeSean(HallSession $hallSession): self
    {
        if ($this->hallSessions->contains($hallSession)) {
            $this->hallSessions->removeElement($hallSession);
            // set the owning side to null (unless already changed)
            if ($hallSession->getHall() === $this) {
                $hallSession->setHall(null);
            }
        }

        return $this;
    }

    public function getRows(): ?int
    {
        return $this->rows;
    }

    public function setRows(int $rows): self
    {
        $this->rows = $rows;

        return $this;
    }

    public function getColumns(): ?int
    {
        return $this->columns;
    }

    public function setColumns(int $columns): self
    {
        $this->columns = $columns;

        return $this;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->getId() ? $this->getName() : '';
    }
}
