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

require_once 'Phake/Matchers/IArgumentMatcher.php';
require_once 'Phake/Matchers/Factory.php';

/**
 * Used to capture the value passed as a parameter to a verified method for further assertions.
 *
 * The constructor takes a parameter by reference that is populated when the matching runs.
 */
class Phake_Matchers_ArgumentCaptor implements Phake_Matchers_IArgumentMatcher
{
	/**
	 * @var mixed
	 */
	private $boundVariable;

	/**
	 * @var Phake_Matchers_IArgumentMatcher
	 */
	private $matcher;

	/**
	 * @param mixed $boundVariable - reference parameter
	 */
	public function __construct(&$boundVariable)
	{
		$this->boundVariable =& $boundVariable;
	}

	/**
	 * Executes the matcher on a given argument value. Returns TRUE on a match, FALSE otherwise.
	 *
	 * Will bind the argument to the variable passed to the constructor.
	 * @param mixed $argument
	 * @return boolean
	 */
	public function matches(&$argument)
	{
		if ($this->matcher === NULL || $this->matcher->matches($argument))
		{
			$this->boundVariable = $argument;
			return TRUE;
		}
		else
		{
			return FALSE;
		}
	}

	/**
	 * Assigns a matcher to the captor.
	 *
	 * This allows an argument to only be captured if the argument meets a specific criteria. This
	 * is useful if one method is called multiple times.
	 *
	 * The same matcher factory used by the verifier and stubber is used here.
	 *
	 * @param mixed $matcher
	 * @return Phake_Matchers_ArgumentCaptor the current instance
	 */
	public function when($matcher)
	{
		$factory = new Phake_Matchers_Factory();

		$this->matcher = $factory->createMatcher($matcher);

		return $this;
	}

	public function __toString()
	{
		return sprintf('<captured parameter%s>', isset($this->matcher) ? " that is {$this->matcher->__toString()}" : '');
	}
}

?>
