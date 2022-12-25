<?php


namespace App\Service;

use App\Entity\Stack;
use App\Entity\Value;
use App\Repository\StackRepository;
use App\Repository\ValueRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityNotFoundException;

/**
 * Class StackService
 * @package App\Service
 */
final class StackService
{

    private $stackRepository;
    private $valueRepository;
    private $em;

    /**
     * StackService constructor.
     * @param StackRepository $stackRepository
     * @param ValueRepository $valueRepository
     * @param EntityManagerInterface $em
     */
    public function __construct(StackRepository $stackRepository,ValueRepository $valueRepository, EntityManagerInterface $em)
    {
        $this->stackRepository = $stackRepository;
        $this->valueRepository = $valueRepository;
        $this->em = $em;

    }

    /**
     * @return array|null
     */

    public function getAllStack(): ?array
    {
        return $this->stackRepository->findAll();
    }

    /**
     * @param int $stackId
     * @return Stack
     */
    public function findStack(int $stackId): Stack
    {
        return $this->stackRepository->find($stackId);;
    }

    public function deleteStack(int $stackId){
        try {
         $stack =   $this->findStack($stackId);
        } catch (EntityNotFoundException $e) {
            return false;
        }
        $this->stackRepository->remove($stack);
        return true ;
    }

    public function addValueToStack($stack_id,$value){
        try {
            $stack = $this->findStack($stack_id);
        } catch (EntityNotFoundException $e) {
            return false ;
        }

        $objValue = new Value();
        $objValue->setValue($value);
        $objValue->setStack($stack);
        $this->valueRepository->add($objValue,true);
        return $stack ;
    }
    public function save()
    {
        $stack = new Stack();
        $this->em->persist($stack);
        $this->em->flush();
        if ($this->em->contains($stack)) {
            return $stack;
        }
        return false;
    }
}
