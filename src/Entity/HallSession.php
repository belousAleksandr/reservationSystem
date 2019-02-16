<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\HallSessionRepository")
 */
class HallSession
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
     * @ORM\ManyToOne(targetEntity="App\Entity\Hall", inversedBy="hallSessions")
     * @ORM\JoinColumn(nullable=false)
     */
    private $hall;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Row", mappedBy="hallSession", cascade={"persist", "remove"}, orphanRemoval=true)
     */
    private $rows;

    /**
     * @ORM\Column(type="datetime")
     */
    private $datetime;

    public function __construct()
    {
        $this->rows = new ArrayCollection();
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

    public function getHall(): ?Hall
    {
        return $this->hall;
    }

    public function setHall(?Hall $hall): self
    {
        $this->hall = $hall;

        return $this;
    }

    /**
     * @return Collection|Row[]
     */
    public function getRows(): Collection
    {
        $this->removeRendundantRowsData();
        foreach (range(0, $this->getHall()->getRows() - 1) as $rowKey) {
            $row = $this->rows->get($rowKey);
            if ($row === null) {
                $row = new Row();
                $this->addRow($row);
            }

            foreach (range(0, $this->getHall()->getColumns() - 1) as $columnKey) {

                $seat = $row->getSeats()->get($columnKey);
                if ($seat === null) {

                    $seat = new Seat();
                    $row->addSeat($seat);
                }
            }
        }

        return $this->rows;
    }

    private function removeRendundantRowsData()
    {
        foreach (range($this->rows->count(), $this->getHall()->getRows()) as $key) {
            $this->rows->remove($key);
            unset($key);
        }
        /** @var Row $row */
        foreach ($this->rows as $row) {
            $seats = $row->getSeats();

            foreach (range($seats->count(), $this->getHall()->getColumns()) as $key) {
                $seats->remove($key);
                unset($key);
            }
        }
    }

    public function addRow(Row $row): self
    {
        if (!$this->rows->contains($row)) {
            $this->rows[] = $row;
            $row->setHallSession($this);
        }

        return $this;
    }

    public function removeRow(Row $row): self
    {
        if ($this->rows->contains($row)) {
            $this->rows->removeElement($row);
            // set the owning side to null (unless already changed)
            if ($row->getHallSession() === $this) {
                $row->setHallSession(null);
            }
        }

        return $this;
    }

    public function getDatetime(): ?\DateTimeInterface
    {
        return $this->datetime;
    }

    public function setDatetime(\DateTimeInterface $datetime): self
    {
        $this->datetime = $datetime;

        return $this;
    }
}
