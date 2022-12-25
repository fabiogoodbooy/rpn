<?php


namespace App\Service;


use App\Repository\OperationsRepository;

class OperationService
{

    private $operationRepository ;

    /**
     * OperationService constructor.
     * @param $operationRepository
     */
    public function __construct(OperationsRepository $operationRepository)
    {
        $this->operationRepository = $operationRepository;
    }


    public function getAllOperations(){
        return   $this->operationRepository->findAll() ;

    }

}
