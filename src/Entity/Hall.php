<?php

namespace App\Entity;

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


}
