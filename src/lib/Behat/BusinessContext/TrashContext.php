<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\EzPlatformAdminUi\Behat\BusinessContext;

use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\TableNode;
use EzSystems\EzPlatformAdminUi\Behat\PageElement\Dialog;
use EzSystems\EzPlatformAdminUi\Behat\PageElement\LeftMenu;
use EzSystems\EzPlatformAdminUi\Behat\PageElement\RightMenu;
use EzSystems\EzPlatformAdminUi\Behat\PageElement\UniversalDiscoveryWidget;
use EzSystems\Behat\Core\Environment\EnvironmentConstants;
use EzSystems\EzPlatformAdminUi\Behat\PageElement\UpperMenu;
use EzSystems\EzPlatformAdminUi\Behat\PageObject\TrashPage;
use EzSystems\EzPlatformAdminUi\Behat\PageObject\ContentViewPage;
use PHPUnit\Framework\Assert;

class TrashContext implements Context
{
    /**
     * @var TrashPage
     */
    private $trashPage;
    /**
     * @var RightMenu
     */
    private $rightMenu;
    /**
     * @var Dialog
     */
    private $dialog;

    public function __construct(TrashPage $trashPage)
    {
        $this->trashPage = $trashPage;
    }

    /**
     * @Then trash is empty
     */
    public function trashIsEmpty(): void
    {
        Assert::assertTrue(
            $this->trashPage->isEmpty(),
            'Trash is not empty.'
        );
    }

    /**
     * @When trash is not empty
     */
    public function trashIsNotEmpty(): void
    {
        Assert::assertFalse(
            $this->trashPage->isEmpty(),
            'Trash is empty.'
        );
    }

    /**
     * @When I empty the trash
     */
    public function iEmptyTrash(): void
    {
        $this->trashPage->emptyTrash();
    }

    /**
     * @When I delete item from trash list
     */
    public function iDeleteItemFromTrash(TableNode $itemsTable): void
    {
        $trashPage = PageObjectFactory::createPage($this->browserContext, TrashPage::PAGE_NAME);

        foreach ($itemsTable->getHash() as $itemTable) {
            $trashPage->trashTable->selectListElement($itemTable['item']);
        }

        $trashPage->trashTable->clickTrashButton();
        $trashPage->dialog->verifyVisibility();
        $trashPage->dialog->confirm();
    }

    /**
     * @When I restore item from trash
     */
    public function iRestoreItemFromTrash(TableNode $itemsTable): void
    {
        $trashPage = PageObjectFactory::createPage($this->browserContext, TrashPage::PAGE_NAME);

        foreach ($itemsTable->getHash() as $itemTable) {
            $trashPage->trashTable->selectListElement($itemTable['item']);
        }

        $trashPage->trashTable->clickRestoreButton();
    }

    /**
     * @When I restore item from trash under new location :pathToContent
     */
    public function iRestoreItemFromTrashUnderNewLocation(TableNode $itemsTable, string $pathToContent): void
    {
        foreach ($itemsTable->getHash() as $itemTable) {
            $this->trashPage->trashTable->selectListElement(['Name' => $itemTable['item']]);
        }

        $this->trashPage->restoreUnderNewLocation($pathToContent);
    }

    /**
     * @Then there is :itemType :itemName on trash list
     */
    public function thereIsItemOnTrashList(string $itemType, string $itemName): void
    {
        Assert::assertTrue($this->trashPage->hasElement($itemType, $itemName));
    }

    /**
     * @Then there is no :itemType :itemName on trash list
     */
    public function thereIsNoItemOnTrashList(string $itemType, string $itemName): void
    {
        Assert::assertFalse($this->trashPage->hasElement($itemType, $itemName));
    }
}
