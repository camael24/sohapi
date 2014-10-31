<?php

use Behat\Behat\Context\ClosuredContextInterface;
use Behat\MinkExtension\Context\MinkContext As Foo;

/**
* Features context.
*/
class FeatureContext extends MinkContext
{

    /**
     * @When /^I am login with user "([^"]*)" password "([^"]*)"$/
     */
    public function iAmLoginWithUserPassword(\Foo\Bar\Qux $arg1 = null, $arg2)
    {

    }

/**
 * @When /^I follow the redirection$/
 * @Then /^I should be redirected$/
 */
public function iFollowTheRedirection()
{

}

}
