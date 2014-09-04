<?php

/*
 * Phake - Mocking Framework
 * 
 * Copyright (c) 2010, Mike Lively <mike.lively@sellingsource.com>
 * All rights reserved.
 * 
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions
 * are met:
 * 
 *  *  Redistributions of source code must retain the above copyright
 *     notice, this list of conditions and the following disclaimer.
 * 
 *  *  Redistributions in binary form must reproduce the above copyright
 *     notice, this list of conditions and the following disclaimer in
 *     the documentation and/or other materials provided with the
 *     distribution.
 * 
 *  *  Neither the name of Mike Lively nor the names of his
 *     contributors may be used to endorse or promote products derived
 *     from this software without specific prior written permission.
 * 
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS
 * FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE
 * COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT,
 * INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING,
 * BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
 * LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER
 * CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT
 * LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN
 * ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE
 * POSSIBILITY OF SUCH DAMAGE.
 * 
 * @category   Testing
 * @package    Phake
 * @author     Mike Lively <m@digitalsandwich.com>
 * @copyright  2010 Mike Lively <m@digitalsandwich.com>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link       http://www.digitalsandwich.com/
 */

require_once('Phake/ClassGenerator/InvocationHandler/StubCaller.php');
require_once('Phake/Stubber/StubMapper.php');
require_once('Phake/Stubber/AnswerCollection.php');
require_once('Phake/Stubber/IAnswer.php');

class Phake_ClassGenerator_InvocationHandler_StubCallerTest extends PHPUnit_Framework_TestCase
{
	/**
	 * @var Phake_ClassGenerator_InvocationHandler_StubCaller 
	 */
	private $handler;
	
	/**
	 * @var Phake_IMock 
	 */
	private $mock;
	
	/**
	 * @var Phake_Stubber_AnswerCollection
	 */
	private $answerCollection;
	
	/**
	 * @var Phake_MockReader
	 */
	private $mockReader;
	
	/**
	 * @var Phake_Stubber_StubMapper
	 */
	private $stubMapper;
	
	/**
	 * @var Phake_Stubber_IAnswer
	 */
	private $defaultAnswer;
	
	public function setUp()
	{
		$this->mock = $this->getMock('Phake_IMock');
		$this->stubMapper = Phake::mock('Phake_Stubber_StubMapper');
		$this->defaultAnswer = Phake::mock('Phake_Stubber_IAnswer');
		Phake::when($this->defaultAnswer)->getAnswer()->thenReturn('24');

		$this->answerCollection = Phake::mock('Phake_Stubber_AnswerCollection');
		$answer = Phake::mock('Phake_Stubber_IAnswer');
		Phake::when($this->answerCollection)->getAnswer()->thenReturn($answer);
		Phake::when($answer)->getAnswer()->thenReturn('42');
		Phake::when($this->stubMapper)->getStubByCall(Phake::anyParameters())->thenReturn($this->answerCollection);
		
		$this->mockReader = Phake::mock('Phake_MockReader');
		Phake::when($this->mockReader)->getStubMapper($this->anything())->thenReturn($this->stubMapper);
		Phake::when($this->mockReader)->getDefaultAnswer($this->anything())->thenReturn($this->defaultAnswer);

		$this->handler = new Phake_ClassGenerator_InvocationHandler_StubCaller($this->mockReader);
	}
	
	public function testImplementIInvocationHandler()
	{
		$this->assertInstanceOf('Phake_ClassGenerator_InvocationHandler_IInvocationHandler', $this->handler);
	}
	
	public function testStubIsPulled()
	{
		$ref = array('bar');
		$this->handler->invoke($this->mock, 'foo', $ref, $ref);
		
		Phake::verify($this->stubMapper)->getStubByCall('foo', array('bar'));
	}
	
	public function testNonDelegatedAnswerReturned()
	{
		$ref = array('bar');
		
		$this->assertEquals('42', $this->handler->invoke($this->mock, 'foo', $ref, $ref)->getAnswer());
	}

	public function testNonDelegatedDefaultAnswerReturned()
	{
		$ref = array('bar');
		Phake::when($this->stubMapper)->getStubByCall(Phake::anyParameters())->thenReturn(NULL);
		
		$this->assertEquals($this->defaultAnswer, $this->handler->invoke($this->mock, 'foo', $ref, $ref));
		Phake::verify($this->stubMapper, Phake::times(1))->getStubByCall(Phake::anyParameters());
	}
	
	public function testMagicCallMethodChecksForImplicitStubFirst()
	{
		$ref = array('foo', array('bar'));
		Phake::when($this->stubMapper)->getStubByCall(Phake::anyParameters())->thenReturn(NULL);
		
		$this->handler->invoke($this->mock, '__call', $ref, $ref);
		
		Phake::inOrder(
			Phake::verify($this->stubMapper)->getStubByCall('foo', array('bar')),
			Phake::verify($this->stubMapper)->getStubByCall('__call', array('foo', array('bar')))
		);
	}
	
	public function testMagicCallMethodBypassesExplicitStub()
	{
		$ref = array('foo', array('bar'));
		
		$this->handler->invoke($this->mock, '__call', $ref, $ref);
		
		Phake::verify($this->stubMapper, Phake::times(0))->getStubByCall('__call', array('foo', array('bar')));
	}
}
?>
