<?php

namespace App\Entity;

use App\Repository\StackRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=StackRepository::class)
 */
class Stack
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups("getStack:read")
     */
    private $id;

    /**
     * @ORM\OneToMany(targetEntity=Value::class, mappedBy="stack", orphanRemoval=true)
     * @Groups("getStack:read")
     */
    private $val;

    public function __construct()
    {
        $this->val = new ArrayCollection();
    }



    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection<int, Value>
     */
    public function getVal(): Collection
    {
        return $this->val;
    }

    public function addVal(Value $val): self
    {
        if (!$this->val->contains($val)) {
            $this->val[] = $val;
            $val->setStack($this);
        }

        return $this;
    }

    public function removeVal(Value $val): self
    {
        if ($this->val->removeElement($val)) {
            // set the owning side to null (unless already changed)
            if ($val->getStack() === $this) {
                $val->setStack(null);
            }
        }

        return $this;
    }


}
