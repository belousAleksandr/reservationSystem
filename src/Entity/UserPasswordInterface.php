<?php

declare(strict_types=1);

namespace App\Entity;


interface UserPasswordInterface
{
    public function getPlainPassword(): ?string;
    public function setSalt(string $salt = null);
    public function getSalt(): ?string;
    public function setPassword(string $password);
    public function getPassword(): ?string;
    public function eraseCredentials();

}