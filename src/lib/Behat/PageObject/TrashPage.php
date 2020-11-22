<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\EzPlatformAdminUi\Behat\PageObject;

use Behat\Mink\Session;
use EzSystems\Behat\Browser\Context\OldBrowserContext;
use EzSystems\Behat\Browser\Page\Page;
use EzSystems\Behat\Browser\Selector\CSSSelector;
use EzSystems\EzPlatformAdminUi\Behat\PageElement\Dialog;
use EzSystems\Behat\Browser\Factory\ElementFactory;
use EzSystems\EzPlatformAdminUi\Behat\PageElement\Tables\TrashTable;
use EzSystems\EzPlatformAdminUi\Behat\PageElement\UniversalDiscoveryWidget;
use FriendsOfBehat\SymfonyExtension\Mink\MinkParameters;
use PHPUnit\Framework\Assert;

class TrashPage extends Page
{

    public const ITEM_RESTORE_LIST_CONTAINER = '';

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
        Session $session,
        MinkParameters $minkParameters,
        UniversalDiscoveryWidget $universalDiscoveryWidget,
        Dialog $dialog,
        RightMenu $rightMenu)
    {
        parent::__construct($session, $minkParameters);
        $this->universalDiscoveryWidget = $universalDiscoveryWidget;
        $this->dialog = $dialog;
        $this->rightMenu = $rightMenu;
    }

    public function hasElement(string $itemType, string $itemName): bool
    {
        return !$this->isTrashEmpty() &&
            ($this->trashTable->isElementInTable($itemName) &&
            $this->trashTable->getTableCellValue('Content type', $itemName) == $itemType);
    }

    public function restore()
    {
//        [name=trash_item_restore]
    }

    public function isEmpty(): bool
    {
        $firstRowValue = $this->trashTable->getCellValue(1, 1);

        return $this->trashTable->getItemCount() === 1 && strpos($firstRowValue, 'Trash is empty.') !== false;
    }

    public function restoreUnderNewLocation(string $pathToContent)
    {
        $this->context->findElement($this->fields['restoreUnderNewLocationButton'], $this->defaultTimeout)->click();


        $this->trashTable->clickRestoreUnderNewLocationButton();

        $this->universalDiscoveryWidget->verifyIsLoaded();
        $this->universalDiscoveryWidget->selectContent($pathToContent);
        $this->universalDiscoveryWidget->confirm();
    }

    public function emptyTrash()
    {
        $this->rightMenu->clickButton('Empty Trash');
        $this->dialog->confirm();
    }

    protected function getRoute(): string
    {
        return 'trash/list';
    }

    public function verifyIsLoaded(): void
    {
        $this->trashTable->verifyIsLoaded();

        Assert::assertEquals(
            'Trash',
            $this->getHTMLPage()->find($this->getSelector('pageTitle'))->getText()
        );
    }

    public function getName(): string
    {
        return 'Trash';
    }

    protected function specifySelectors(): array
    {
        return [
            new CSSSelector('pageTitle', '.ez-page-title h1'),
        ];
    }
}
