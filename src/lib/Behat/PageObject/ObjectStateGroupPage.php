<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\EzPlatformAdminUi\Behat\PageObject;

use Behat\Mink\Session;
use EzSystems\Behat\Browser\Context\OldBrowserContext;
use EzSystems\Behat\Browser\Page\Browser;
use EzSystems\Behat\Browser\Page\Page;
use EzSystems\Behat\Browser\Locator\CSSLocator;
use EzSystems\EzPlatformAdminUi\Behat\PageElement\AdminList;
use EzSystems\Behat\Browser\Factory\ElementFactory;
use EzSystems\EzPlatformAdminUi\Behat\PageElement\Dialog;
use EzSystems\EzPlatformAdminUi\Behat\PageElement\Tables\LinkedListTable;
use EzSystems\EzPlatformAdminUi\Behat\PageElement\Tables\SimpleTable;
use FriendsOfBehat\SymfonyExtension\Mink\MinkParameters;
use PHPUnit\Framework\Assert;

class ObjectStateGroupPage extends Page
{
    /** @var string locator for container of Object States list */
    public $secondListContainerLocator = 'section:nth-of-type(2)';

    /**
     * @var \EzSystems\EzPlatformAdminUi\Behat\PageElement\AdminList[]
     */
    public $adminLists;

    /**
     * @var \EzSystems\EzPlatformAdminUi\Behat\PageElement\AdminList
     */
    public $adminList;
    /**
     * @var string
     */
    protected $expectedObjectStateGroupname;
    /**
     * @var Dialog
     */
    private $dialog;

    public function __construct(Browser $browser, Dialog $dialog)
    {
        parent::__construct($browser);
        $this->dialog = $dialog;
    }

//    public function qwe(OldBrowserContext $context, string $objectStateGroupName)
//    {
//        $this->objectStateGroupName = $objectStateGroupName;
//        $this->adminLists['Object state group information'] = ElementFactory::createElement($this->context, AdminList::ELEMENT_NAME, 'Object state group information', SimpleTable::ELEMENT_NAME);
//        $this->adminLists['Object states'] = ElementFactory::createElement($this->context, AdminList::ELEMENT_NAME, 'Object states', LinkedListTable::ELEMENT_NAME, $this->secondListContainerLocator);
//        $this->adminList = ElementFactory::createElement($this->context, AdminList::ELEMENT_NAME, '', SimpleTable::ELEMENT_NAME);
//    }

    /**
     * Verifies if list of Object States is empty.
     *
     * @param string $listName
     */
    public function verifyListIsEmpty($listName): void
    {
        Assert::assertTrue(
            $this->isListEmpty($listName),
            '"Object States" list is not empty.'
        );
    }

    public function isListEmpty(string $listName): bool
    {
        $firstRowValue = $this->adminLists[$listName]->table->getCellValue(1, 1);

        return $this->adminLists[$listName]->table->getItemCount() === 1 &&
            strpos($firstRowValue, 'There are no Object states yet.') !== false;
    }

    public function editObjectState(string $itemName): void
    {
        $this->adminLists['Object states']->table->clickEditButton($itemName);
    }

    public function editCurrentGroup(string $itemName): void
    {
        $this->adminLists['Object state group information']->table->clickEditButton($itemName);
    }

    public function createObjectState(): void
    {
        $this->adminLists['Object states']->clickPlusButton();
    }

    public function verifyItemAttribute(string $label, string $value): void
    {
        Assert::assertEquals(
            $value,
            $this->adminLists['Object state group information']->table->getTableCellValue($label),
            sprintf('Attribute "%s" has wrong value.', $label)
        );
    }

    public function setExpectedObjectStateGroupName(string $objectStateGroupName): void
    {
        $this->expectedObjectStateGroupname = $objectStateGroupName;
    }

    public function select(array $array)
    {
    }

    public function deleteSelected()
    {
        $objectStateGroupPage->adminLists['Object states']->clickTrashButton();
        $this->dialog->verifyVisibility();
        $this->dialog->confirm();
    }

    protected function getRoute(): string
    {
        return '/state/group/'; //TODO: get ID from name
    }

    public function verifyIsLoaded(): void
    {
        Assert::assertEquals(
            sprintf('Object state group: %s', $this->expectedObjectStateGroupname),
            $this->getHTMLPage()->find($this->getLocator('pageTitle'))->getText()
        );

        $this->adminLists['Object state group information']->verifyVisibility();
        $this->adminLists['Object states']->verifyVisibility();
    }

    public function getName(): string
    {
        return 'Object state group';
    }

    protected function specifyLocators(): array
    {
        return [
            new CSSLocator('pageTitle', '.ez-header h1'),
        ];
    }
}
