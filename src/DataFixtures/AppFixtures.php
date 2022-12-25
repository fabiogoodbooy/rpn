<?php

namespace App\DataFixtures;

use App\Entity\Operations;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $allOperations = ["+","-","*","/"];
        foreach ($allOperations as $item){
            $checkOperation = $manager->getRepository(Operations::class)->findOneBy(["value"=>$item])  ;
            if(!$checkOperation instanceof Operations)   {
                $operation = new Operations();
                $operation->setValue($item);
                $manager->persist($operation);
                $manager->flush();
            }
        }
    }

}
