<?php

namespace App\Controller;

use App\Classes\Addition;
use App\Classes\Division;
use App\Classes\Multiplication;
use App\Classes\Subtraction;
use App\Entity\Stack;
use App\Repository\OperationsRepository;
use App\Service\OperationService;
use App\Service\StackService;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityNotFoundException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use OpenApi\Annotations as OA;
use Symfony\Component\Serializer\SerializerInterface;
/**
* @Route("/api")
 **/
class RPMCalculatorController extends AbstractController
{
   // private $op = ["+","-","*","/"];
    private  $em ;
    private $serializer ;
    private $stackService;
    private $operationService;

    /**
     * RPNCalculatorController constructor.
     * @param EntityManagerInterface $em
     * @param SerializerInterface $serializer
     * @param StackService $stackService
     * @param OperationService $operationService
     */
    public function __construct(EntityManagerInterface $em,
                                SerializerInterface $serializer,
                                StackService $stackService,
                                OperationService $operationService)
    {
        $this->em = $em;
        $this->serializer = $serializer ;
        $this->stackService = $stackService ;
        $this->operationService = $operationService;
    }

    // Create new Stack in dataBase
    /**
     * @Route("/stack", name="createStack",methods={"POST"})
     *      * @OA\Response(
     *     response=200,
     *     description="Stack Created with succees",
     * )
     * @OA\Tag(name="RPN")
     * @return JsonResponse
     */
    public function createStack(): JsonResponse
    {

        $stack = $this->stackService->save();

        if($stack){
            return $this->json([
                'stack_id' => $stack->getId(),
            ],Response::HTTP_OK,['Cache-Control' => 'max-age=3600']);
        }
        return $this->json([
            'error' => "error with server",
        ],Response::HTTP_INTERNAL_SERVER_ERROR,['Cache-Control' => 'max-age=3600']);
    }

    // push value to the stock

    /**
     * @Route("/stack/{stack_id}", name="add_value_to_stack",methods={"POST"})
     *      * @OA\Response(
     *     response=200,
     *     description="Value added to stock with succees",
     * )
     * @OA\Parameter(
     *     name="value",
     *     in="query",
     *     description="The field used to add new value to stack",
     *     @OA\Schema(type="integer")
     * )
     * @OA\Tag(name="RPN")
     * @param Request $request
     * @param $stack_id
     * @return JsonResponse
     */
    public function addValueToStack( Request $request,$stack_id): JsonResponse
    {
        $value = $request->query->get('value');
        $stack = $this->stackService->addValueToStack($stack_id,$value);
        $jsonStack = $this->serializer->serialize($stack,'json',['groups'=>'getStack:read']);
        if($jsonStack){
            return  $this->json(["stack"=>json_decode($jsonStack)],Response::HTTP_OK,['Cache-Control' => 'max-age=3600']);
        }
        return  $this->json(["message"=>"stock dos not exist "],Response::HTTP_NOT_FOUND,['Cache-Control' => 'max-age=3600']);

    }

    // get all stacks on database
    /**
     * @Route("/stack", name="all_stack",methods={"GET"})
     *      * @OA\Response(
     *     response=200,
     *     description="List the available stacks",
     * )
     * @OA\Tag(name="RPN")
     * @return JsonResponse
     */
    public function getAllStack(): JsonResponse
    {
        $stacks = $this->stackService->getAllStack() ;

        $stackJSON = $this->serializer->serialize($stacks,'json',['groups'=>'getStack:read']);
        return  $this->json(["stacks"=>json_decode($stackJSON)],Response::HTTP_OK,['Cache-Control' => 'max-age=3600']);

    }



    // get all operations on database

    /**
     * @Route("/op", name="get_operations",methods={"GET"})
     *      * @OA\Response(
     *     response=200,
     *     description="List the available operations",
     * )
     * @OA\Tag(name="RPN")
     * @return JsonResponse
     */
    public function getOperations(): JsonResponse
    {
        $operations = $this->operationService->getAllOperations();
        $operationsJSON = $this->serializer->serialize($operations,'json',['groups'=>'operation:read']);

        return  $this->json(["stacks"=>json_decode($operationsJSON)],Response::HTTP_OK,['Cache-Control' => 'max-age=3600']);

    }


    //Get stack with the ID

    /**
     * @Route("/stack/{id_stack}", name="get_stack",methods={"GET"})
     *      * @OA\Response(
     *     response=200,
     *     description="List the available operations",
     * )
     * @OA\Tag(name="RPN")
     * @param $id_stack
     * @return JsonResponse
     */
    public function getStack($id_stack): JsonResponse
    {
        $stack = $this->stackService->findStack($id_stack);
        if($stack instanceof Stack){
            $stackJSON = $this->serializer->serialize($stack,'json',['groups'=>'operation:read']);
            return  $this->json(["stack"=>json_decode($stackJSON)],Response::HTTP_OK,['Cache-Control' => 'max-age=3600']);
        }
        return  $this->json(["message"=>"stock dos not exist "],Response::HTTP_NOT_FOUND,['Cache-Control' => 'max-age=3600']);


    }


    // Delete stack with the ID
    /**
     * @Route("/stack/{id_stack}", name="delet_stack",methods={"DELETE"})
     *      * @OA\Response(
     *     response=200,
     *     description="Stack deleted with succees",
     * )
     * @OA\Tag(name="RPN")
     * @param $id_stack
     * @return JsonResponse
     */
    public function deleteStack($id_stack): JsonResponse
    {
      if($this->stackService->deleteStack($id_stack)){

         return  $this->json(['message'=>'stack deleted with success'],Response::HTTP_OK,['Cache-Control' => 'max-age=3600']) ;
      }
        return  $this->json(['error'=>'stack dos not exist '],Response::HTTP_NOT_FOUND,['Cache-Control' => 'max-age=3600']) ;

    }



    // calculate value with operation id

    /**
     * @Route("/op/{op_id}/stack/{stack_id}", name="op",methods={"POST"})
     *      * @OA\Response(
     *     response=200,
     *     description="operation with success",
     * )
     * @OA\Tag(name="RPN")
     * @param $stack_id
     * @param $op_id
     * @return JsonResponse
     */
    public function operation( $stack_id, $op_id): JsonResponse
    {
        $stack = $this->stackService->findStack($stack_id);
        $stackValue = $stack->getVal()->toArray() ;

        if(count($stackValue) < 2){
            return $this->json([
                'error' => "You have 1 item in your stack ",
            ],Response::HTTP_BAD_REQUEST,['Cache-Control' => 'max-age=3600']);
        }
        $val2 = array_pop($stackValue);
        $val1 = array_pop($stackValue);
        switch ($op_id) {
            case '1':
                 $addition = new Addition($val1->getValue(),$val2->getValue());
                 $result = $addition->calculate();
                 
                break;
            case '2':
                $subtraction = new Subtraction($val1->getValue(),$val2->getValue());
                $result = $subtraction->calculate();
               break;
            case '3':
                $multiplication = new Multiplication($val1->getValue(),$val2->getValue());
                $result = $multiplication->calculate();

                break;
            case '4':
                if($val2->getValue() == 0){
                    return $this->json([
                        'error' => "you can not div with zero (0)",
                    ],Response::HTTP_BAD_REQUEST,['Cache-Control' => 'max-age=3600']);
                }
                $division = new Division($val1->getValue(),$val2->getValue());
                $result = $division->calculate();
                break;
            default:
                $operations = $this->operationService->getAllOperations();
                $operationsJSON = $this->serializer->serialize($operations,'json',['groups'=>'operation:read']);
                return $this->json([
                    'error' => "you need to send a id of validate operation , you can check list of operation id",
                    'operations' => json_decode($operationsJSON),
                ],Response::HTTP_BAD_REQUEST,['Cache-Control' => 'max-age=3600']);
        }
        $stack->removeVal($val1);
        $val2->setValue($result);
        $this->em->flush();
        $stackJSON = $this->serializer->serialize($stack,'json',['groups'=>'getStack:read']);

        return  $this->json(['message'=>'calculated with success','stack'=>json_decode($stackJSON)],Response::HTTP_OK,['Cache-Control' => 'max-age=3600']) ;

    }

}
