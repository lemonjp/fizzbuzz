<?php
namespace CodeIQ;

class FizzBuzzApplication
{
    private $specAndMessages;

    public function __construct()
    {
        $this->specAndMessages = [];
    }

    public function addSpecAndMessage(FizzBuzzSpecification $spec, $message)
    {
        $this->specAndMessages[] = ['spec'=>$spec, 'message'=>$message];
    }

    //PHP Callback function not working on object functions
    public function getNumber($number)
    {
        echo $this->checkSpecAndGetMessage($number).PHP_EOL;
    }

    public function run($data)
    {
        //array_walk($data, 'getNumber'); // this array_walk does not work!
        array_walk($data, array($this, 'getNumber'));
    }

    /* http://codeiq.hatenablog.com/entry/2013/08/07/162935
    public function run($data)
    {
        array_walk($data, function($number) {
            echo $this->checkSpecAndGetMessage($number).PHP_EOL;
        });
    }
     */

    public function checkSpecAndGetMessage($number)
    {
        foreach ($this->specAndMessages as $specAndMessage) {
            if ($specAndMessage['spec']->isSatisfiedBy($number)) {

                return $specAndMessage['message'];
            }
        }

        return $number;
    }
}
