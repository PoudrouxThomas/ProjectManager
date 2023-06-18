<?php

namespace App\Entity\Traits;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\DBAL\Types\Types;

#[ORM\MappedSuperclass]
trait Timestampable
{

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE, options:["default" => "CURRENT_TIMESTAMP"])]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, options:["default" => "CURRENT_TIMESTAMP"])]
    private ?\DateTimeInterface $updatedAt = null;

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    #[ORM\PrePersist]
    #[ORM\PreUpdate]
    public function updateTimestamps()
    {
        if($this->getCreatedAt() === null)
            $this->setCreatedAt(new \DateTimeImmutable);

        $this->setUpdatedAt(new \DateTimeImmutable);
    }
}