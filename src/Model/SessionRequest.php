<?php

declare(strict_types=1);

namespace App\Model;

class SessionRequest
{
    /** @var \DateTime|null */
    private $date;

    /**
     * @return \DateTime|null
     */
    public function getDate():?\DateTime
    {
        return $this->date;
    }

    /**
     * @param \DateTime|null $date
     * @return $this
     */
    public function setDate(\DateTime $date): self
    {
        $this->date = $date;

        return $this;
    }
}