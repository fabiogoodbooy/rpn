<?php

namespace App\Entity;

use App\Repository\OperationsRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=OperationsRepository::class)
 */
class Operations
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups("operation:read")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups("operation:read")
     */
    private $value;

    public function getId(): ?int
    {
        return $this->id;
    }


    public function getValue(): ?string
    {
        return $this->value;
    }

    public function setValue(string $value): self
    {
        $this->value = $value;

        return $this;
    }
}
