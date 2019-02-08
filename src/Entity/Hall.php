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
     * @ORM\OneToMany(targetEntity="App\Entity\Seans", mappedBy="hall", orphanRemoval=true)
     */
    private $seans;

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
        $this->seans = new ArrayCollection();
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
     * @return Collection|Seans[]
     */
    public function getSeans(): Collection
    {
        return $this->seans;
    }

    /**
     * @param Seans $sean
     * @return Hall
     */
    public function addSean(Seans $sean): self
    {
        if (!$this->seans->contains($sean)) {
            $this->seans[] = $sean;
            $sean->setHall($this);
        }

        return $this;
    }

    /**
     * @param Seans $sean
     * @return Hall
     */
    public function removeSean(Seans $sean): self
    {
        if ($this->seans->contains($sean)) {
            $this->seans->removeElement($sean);
            // set the owning side to null (unless already changed)
            if ($sean->getHall() === $this) {
                $sean->setHall(null);
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
}
