<?php


namespace App\Classes;


use Arithmetic;

class Subtraction implements Arithmetic
{
    private $val1 ;
    private  $val2;

    /**
     * Addition constructor.
     * @param int $val1
     * @param int $val2
     */
    public function __construct(int $val1,int $val2)
    {
        $this->val1 = $val1;
        $this->val2 = $val2;

    }
    public function calculate()
    {
        return  $this->val1 -  $this->val2 ;
    }
}
