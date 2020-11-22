<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\EzPlatformAdminUi\Behat\BusinessContext;

use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\TableNode;
use EzSystems\EzPlatformAdminUi\Behat\PageElement\Dialog;
use EzSystems\Behat\Browser\Factory\ElementFactory;
use EzSystems\EzPlatformAdminUi\Behat\PageObject\ObjectStateGroupPage;
use EzSystems\Behat\Browser\Factory\PageObjectFactory;
use PHPUnit\Framework\Assert;

class ObjectStatesContext implements Context
{
    /**
     * @var ObjectStateGroupPage
     */
    private $objectStateGroupPage;

    public function __construct(ObjectStateGroupPage $objectStateGroupPage)
    {
        $this->objectStateGroupPage = $objectStateGroupPage;
    }

    /**
     * @Then there's :objectStateName on :objectStateGroupName Object States list
     */
    public function verfyObjectStateIsOnList(string $objectStateName, string $objectStateGroupName): void
    {
        $this->objectStateGroupPage->setExpectedObjectStateGroupName($objectStateGroupName);
        $this->objectStateGroupPage->verifyIsLoaded();

        // refactor
        Assert::assertTrue(
            $this->objectStateGroupPage->adminLists['Object states']->table->isElementOnCurrentPage($objectStateName)
        );
    }

    /**
     * @Then there's no :objectStateName on :objectStateGroupName Object States list
     */
    public function verifyObjectStateIsNotOnList(string $objectStateName, string $objectStateGroupName): void
    {
        $this->objectStateGroupPage->setExpectedObjectStateGroupName($objectStateGroupName);
        $this->objectStateGroupPage->verifyIsLoaded();


        if ($this->objectStateGroupPage->isListEmpty('Object states')) {
            return;
        }

        Assert::assertFalse(
            $this->objectStateGroupPage->adminLists['Object states']->table->isElementOnCurrentPage($objectStateName),
        );
    }

    /**
     * @Given I go to :objectStateName Object State page from :objectStateGroupName
     */
    public function iGoToObjectState(string $objectStateName, string $objectStateGroupName): void
    {
        $this->objectStateGroupPage->setExpectedObjectStateGroupName($objectStateGroupName);
        $this->objectStateGroupPage->verifyIsLoaded();

        // refactor
        $thisobjectStateGroupPage->adminLists['Object states']->table->clickListElement($objectStateName);
    }

    /**
     * @When I delete Object State from :objectStateGroupName
     */
    public function iDeleteObjecStatesFromGroup(string $objectStateGroupName, TableNode $settings): void
    {
        foreach($settings->getHash() as $setting)
        {
            $this->objectStateGroupPage->select(['Object state name' => $settings['item']]);
        }

        $this->objectStateGroupPage->deleteSelected();
    }
}
