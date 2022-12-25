<?php

namespace App\Entity;

use App\Repository\ValueRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=ValueRepository::class)
 *
 */
class Value
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     * @Groups("getStack:read")
     */
    private $value;

    /**
     * @ORM\ManyToOne(targetEntity=Stack::class, inversedBy="val")
     * @ORM\JoinColumn(nullable=false)
     */
    private $stack;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getValue(): ?int
    {
        return $this->value;
    }

    public function setValue(int $value): self
    {
        $this->value = $value;

        return $this;
    }

    public function getStack(): ?Stack
    {
        return $this->stack;
    }

    public function setStack(?Stack $stack): self
    {
        $this->stack = $stack;

        return $this;
    }
}
