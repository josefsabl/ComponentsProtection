<?php

namespace Tests\Integration;

use Codeception\TestCase\Test;

/**
 * @author Jáchym Toušek <enumag@gmail.com>
 */
class ActionsRuleHandlerTest extends Test
{

	public function testActionDefault()
	{
		$this->guy->amOnPage('/article/');
		$this->guy->seeResponseCodeIs(200);
		$this->guy->see('header');
		$this->guy->see('footer');
	}

	/**
	 * @expectedException Arachne\Verifier\Exception\VerificationException
	 * @expectedExceptionMessage Component is inaccessible for the given action.
	 */
	public function testActionDetail()
	{
		$this->guy->amOnPage('/article/detail/1');
	}

	public function testActionEdit()
	{
		$this->guy->amOnPage('/article/edit/1');
		$this->guy->seeResponseCodeIs(200);
		$this->guy->see('header');
		$this->guy->dontSee('footer');
	}

	/**
	 * @expectedException Arachne\ComponentsProtection\Exception\MissingAnnotationException
	 * @expectedExceptionMessage Missing annotation @Arachne\ComponentsProtection\Rules\Actions for component 'unprotected'.
	 */
	public function testComponentNotAllowed()
	{
		$this->guy->amOnPage('/article/?do=unprotected-signal');
	}

}