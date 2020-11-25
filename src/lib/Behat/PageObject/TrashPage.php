<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\EzPlatformAdminUi\Behat\PageObject;

use Behat\Mink\Session;
use EzSystems\Behat\Browser\Page\Browser;
use EzSystems\Behat\Browser\Page\Page;
use EzSystems\Behat\Browser\Locator\CSSLocator;
use EzSystems\EzPlatformAdminUi\Behat\PageElement\Dialog;
use EzSystems\EzPlatformAdminUi\Behat\PageElement\RightMenu;
use EzSystems\EzPlatformAdminUi\Behat\PageElement\UniversalDiscoveryWidget;
use FriendsOfBehat\SymfonyExtension\Mink\MinkParameters;
use PHPUnit\Framework\Assert;

class TrashPage extends Page
{
    /**
     * @var \EzSystems\EzPlatformAdminUi\Behat\PageElement\Dialog
     */
    public $dialog;

    /**
     * @var \EzSystems\EzPlatformAdminUi\Behat\PageElement\Tables\TrashTable
     */
    public $trashTable;
    /**
     * @var UniversalDiscoveryWidget
     */
    private $universalDiscoveryWidget;
    /**
     * @var RightMenu
     */
    private $rightMenu;

    public function __construct(
        Browser $browser,
        UniversalDiscoveryWidget $universalDiscoveryWidget,
        Dialog $dialog,
        RightMenu $rightMenu)
    {
        parent::__construct($browser);
        $this->universalDiscoveryWidget = $universalDiscoveryWidget;
        $this->dialog = $dialog;
        $this->rightMenu = $rightMenu;
    }

    public function hasElement(string $itemType, string $itemName): bool
    {
        return !$this->isEmpty() &&
            ($this->trashTable->isElementInTable($itemName) &&
            $this->trashTable->getTableCellValue('Content type', $itemName) == $itemType);
    }

    public function isEmpty(): bool
    {
        $firstRowValue = $this->trashTable->getCellValue(1, 1);

        return $this->trashTable->getItemCount() === 1 && strpos($firstRowValue, 'Trash is empty.') !== false;
    }

    public function restoreSelectedNewLocation(string $pathToContent)
    {
        $this->getHTMLPage()->find($this->getLocator('restoreUnderNewLocationButton'))->click();
        $this->universalDiscoveryWidget->verifyIsLoaded();
        $this->universalDiscoveryWidget->selectContent($pathToContent);
        $this->universalDiscoveryWidget->confirm();
    }

    public function emptyTrash()
    {
        $this->rightMenu->clickButton('Empty Trash');
        $this->dialog->confirm();
    }

    public function deleteSelectedItems()
    {
        $this->getHTMLPage()->find($this->getLocator('trashButton'))->click();


        $this->trashTable->clickTrashButton();
        $this->dialog->verifyVisibility();
        $this->dialog->confirm();
    }

    public function select(array $parameters)
    {
        throw new \Exception('implement');
    }

    public function restoreSelectedItems()
    {
        $this->getHTMLPage()->find($this->getLocator('restoreButton'))->click();
    }

    protected function getRoute(): string
    {
        return 'trash/list';
    }

    public function verifyIsLoaded(): void
    {
        Assert::assertEquals(
            'Trash',
            $this->getHTMLPage()->find($this->getLocator('pageTitle'))->getText()
        );
    }

    public function getName(): string
    {
        return 'Trash';
    }

    protected function specifyLocators(): array
    {
        return [
            new CSSLocator('pageTitle', '.ez-page-title h1'),
            new CSSLocator('restoreButton', '[name=trash_item_restore]'),
            new CSSLocator('trashButton', '[id=delete-trash-items]'),
            new CSSLocator('restoreUnderNewLocationButton', '[id=trash_item_restore_location_select_content]'),
            ];
    }
}
