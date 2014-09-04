<?php
/* 
 * Phake - Mocking Framework
 * 
 * Copyright (c) 2010-2012, Mike Lively <m@digitalsandwich.com>
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

require_once 'Phake/CallRecorder/Verifier.php';
require_once 'Phake/Stubber/StubMapper.php';

/**
 * A facade class providing functionality to interact with the Phake framework.
 *
 * @author Mike Lively <m@digitalsandwich.com>
 */
class Phake_Facade
{
	private $cachedClasses;
	
	public function __construct()
	{
	  $this->cachedClasses = array();
	}
	
	/**
	 * Creates a new mock class than can be stubbed and verified.
	 *
	 * @param string $mockedClass - The name of the class to mock
	 * @param Phake_ClassGenerator_MockClass $mockGenerator - The generator used to construct mock classes
	 * @param Phake_CallRecorder_Recorder $callRecorder
	 * @param Phake_Stubber_IAnswer $defaultAnswer
	 * @return mixed
	 */
	public function mock($mockedClass, Phake_ClassGenerator_MockClass $mockGenerator, Phake_CallRecorder_Recorder $callRecorder, Phake_Stubber_IAnswer $defaultAnswer, array $constructorArgs = null)
	{
		if (!class_exists($mockedClass, TRUE) && !interface_exists($mockedClass, TRUE))
		{
			throw new InvalidArgumentException("The class / interface [{$mockedClass}] does not exist. Check the spelling and make sure it is loadable.");
		}
		
    if(!isset($this->cachedClasses[$mockedClass]))
    {
      $newClassName = $this->generateUniqueClassName($mockedClass);
      $mockGenerator->generate($newClassName, $mockedClass);
 
      $this->cachedClasses[$mockedClass] = $newClassName;
    }
       
    return $mockGenerator->instantiate($this->cachedClasses[$mockedClass], $callRecorder, new Phake_Stubber_StubMapper(), $defaultAnswer, $constructorArgs);
	}

	/**
	 * Generates a unique class name based on a given name.
	 *
	 * The $base will be used as the prefix for the new class name.
	 *
	 * @param string $base
	 * @return string
	 */
	private function generateUniqueClassName($base)
	{
		$ns_parts = explode('\\', $base);
		$base = array_pop($ns_parts);
		$base_class_name = uniqid($base . '_PHAKE');
		$i = 1;

		while (class_exists($base_class_name . $i, FALSE))
		{
			$i++;
		}

		return $base_class_name;
	}
}

?>
