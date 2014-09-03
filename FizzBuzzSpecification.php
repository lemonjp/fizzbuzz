<?php
namespace CodeIQ;

class FizzBuzzSpecification
{
    protected $divisor;

    public function __construct($divisor)
    {
        $this->divisor = $divisor;
    }

    public function isSatisfiedBy($number)
    {
        return ($number % $this->divisor == 0);
    }
}

