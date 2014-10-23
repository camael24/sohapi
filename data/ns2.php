<?php

use Behat\Behat\Context\ClosuredContextInterface,
Behat\Behat\Context\TranslatedContextInterface,
Behat\Behat\Context\BehatContext,
Behat\Behat\Context\Step\Given,
Behat\Behat\Context\Step\When,
Behat\Behat\Context\Step\Then,
Behat\Behat\Exception\PendingException,
Behat\Behat\Exception\ErrorException;

use Behat\Gherkin\Node\PyStringNode,
Behat\Gherkin\Node\TableNode;

use Behat\MinkExtension\Context\MinkContext;

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
