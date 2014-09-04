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

require_once('Phake/ClassGenerator/InvocationHandler/FrozenObjectCheck.php');
require_once('Phake/Exception/VerificationException.php');

class Phake_ClassGenerator_InvocationHandler_FrozenObjectCheckTest extends PHPUnit_Framework_TestCase
{
	/**
	 * @var Phake_ClassGenerator_InvocationHandler_FrozenObjectCheck 
	 */
	private $handler;
	
	/**
	 * @var Phake_MockReader
	 */
	private $mockReader;
	
	public function setUp()
	{
		$this->mockReader = Phake::mock('Phake_MockReader');
		$this->handler = new Phake_ClassGenerator_InvocationHandler_FrozenObjectCheck($this->mockReader);
	}
	
	public function testImplementIInvocationHandler()
	{
		$this->assertInstanceOf('Phake_ClassGenerator_InvocationHandler_IInvocationHandler', $this->handler);
	}
	
	public function testReturnsWithNoIssuesIfObjectIsNotFrozen()
	{
		$mock = $this->getMock('Phake_IMock');
		Phake::when($this->mockReader)->isObjectFrozen($this->anything())->thenReturn(FALSE);
		
		try
		{
			$ref = array();
			$this->handler->invoke($mock, 'foo', array(), $ref);
		}
		catch (Exception $e)
		{
			$this->fail('There should not have been an exception:' . $e->getMessage());
		}
	}
	
	public function testThrowsWhenObjectIsFrozen()
	{
		$mock = $this->getMock('Phake_IMock');
		Phake::when($this->mockReader)->isObjectFrozen($this->anything())->thenReturn(TRUE);
		
		$this->setExpectedException('Phake_Exception_VerificationException', 'This object has been frozen.');
		$ref = array();
		$this->handler->invoke($mock, 'foo', array(), $ref);
	}
}
?>
