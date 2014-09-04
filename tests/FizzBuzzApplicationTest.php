<?php
namespace CodeIQ\tests;

require_once dirname(__FILE__).'/../vendor/autoload.php';
require_once dirname(__FILE__).'/../FizzBuzzApplication.php';
require_once dirname(__FILE__).'/../FizzBuzzSpecification.php';

use CodeIQ\FizzBuzzApplication;
use CodeIQ\FizzBuzzSpecification;

class FizzBuzzApplicationTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function 各入力に対してrun実行でcheckSpecAndGetMessage呼び出し()
    {
        $app = \Phake::partialMock('CodeIQ\FizzBuzzApplication');
        \Phake::when($app)->checkSpecAndGetMessage(\Phake::anyParameters())->thenReturn('');
        $data = [1,2,3];

        $app->run($data);

        \Phake::verify($app, \Phake::times(3))->checkSpecAndGetMessage(\Phake::anyParameters());
    }

    /**
     * @test
     */
    public function 仕様呼び出し()
    {
        $app = new FizzBuzzApplication();
        $number = 3;

        $spec = \Phake::mock('CodeIQ\FizzBuzzSpecification');
        \Phake::when($spec)->isSatisfiedBy($number)->thenReturn(true);
        $app->addSpecAndMessage($spec, 'spec1');

        $app->checkSpecAndGetMessage($number);

        \Phake::verify($spec)->isSatisfiedBy($number);
    }

    /**
     * @test
     */
    public function 複数仕様の場合にマッチした最初の仕様の結果が返る()
    {
        $app = new FizzBuzzApplication();
        $number = 3;

        $spec1 = \Phake::mock('CodeIQ\FizzBuzzSpecification');
        \Phake::when($spec1)->isSatisfiedBy($number)->thenReturn(false);
        $spec2 = \Phake::mock('CodeIQ\FizzBuzzSpecification');
        \Phake::when($spec2)->isSatisfiedBy($number)->thenReturn(true);
        $spec3 = \Phake::mock('CodeIQ\FizzBuzzSpecification');
        \Phake::when($spec3)->isSatisfiedBy($number)->thenReturn(true);

        $app->addSpecAndMessage($spec1, 'spec1');
        $app->addSpecAndMessage($spec2, 'spec2');
        $app->addSpecAndMessage($spec3, 'spec3');

        $ret = $app->checkSpecAndGetMessage($number);

        $this->assertEquals('spec2', $ret);
        \Phake::verify($spec1, \Phake::times(1))->isSatisfiedBy($number);
        \Phake::verify($spec2, \Phake::times(1))->isSatisfiedBy($number);
        \Phake::verify($spec3, \Phake::times(0))->isSatisfiedBy($number);
    }

    /**
     * @test
     */
    public function 仕様にマッチしない場合は値がそのままが返る()
    {
        $app = new FizzBuzzApplication();
        $number = 3;

        $spec1 = \Phake::mock('CodeIQ\FizzBuzzSpecification');
        \Phake::when($spec1)->isSatisfiedBy($number)->thenReturn(false);
        $spec2 = \Phake::mock('CodeIQ\FizzBuzzSpecification');
        \Phake::when($spec2)->isSatisfiedBy($number)->thenReturn(false);

        $app->addSpecAndMessage($spec1, 'spec1');
        $app->addSpecAndMessage($spec2, 'spec2');

        $ret = $app->checkSpecAndGetMessage($number);

        $this->assertEquals($number, $ret);
    }
}
