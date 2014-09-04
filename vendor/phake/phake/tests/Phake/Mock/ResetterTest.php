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

require_once('Phake/Mock/Resetter.php');
require_once('Phake/MockReader.php');

class Phake_Mock_ResetterTest extends PHPUnit_Framework_TestCase
{
	/**
	 * @var Phake_Mock_Resetter
	 */
	private $resetter;
	
	/**
	 * @var Phake_MockReader
	 */
	private $mockReader;
	
	/**
	 * @var Phake_Stubber_StubMapper
	 */
	private $stubMapper;
	
	/**
	 * @var Phake_CallRecorder_Recorder
	 */
	private $callRecorder;
	
	public function setUp()
	{
		$this->mockReader = Phake::mock('Phake_MockReader');
		$this->stubMapper = Phake::mock('Phake_Stubber_StubMapper');
		$this->callRecorder = Phake::mock('Phake_CallRecorder_Recorder');
		
		$this->resetter = new Phake_Mock_Resetter($this->mockReader);
	}
	
	public function testReset()
	{
		$mock = $this->getMock('Phake_IMock');
		Phake::when($this->mockReader)->getStubMapper($mock)->thenReturn($this->stubMapper);
		Phake::when($this->mockReader)->getCallRecorder($mock)->thenReturn($this->callRecorder);

		$this->resetter->reset($mock);
		
		Phake::verify($this->mockReader)->setIsObjectFrozen($mock, FALSE);
		Phake::verify($this->stubMapper)->removeAllAnswers();
		Phake::verify($this->callRecorder)->removeAllCalls();
	}
}

?>
