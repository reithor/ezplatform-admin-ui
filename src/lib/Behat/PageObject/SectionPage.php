<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\EzPlatformAdminUi\Behat\PageObject;

use EzSystems\Behat\Browser\Page\Page;
use EzSystems\Behat\Browser\Locator\VisibleCSSLocator;
use PHPUnit\Framework\Assert;

class SectionPage extends Page
{
    /** @var string locator for container of Content list */
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
     * @var \EzSystems\EzPlatformAdminUi\Behat\PageElement\Dialog[]
     */
    public $dialogs;

    //    public function __construct(OldBrowserContext $context, string $sectionName)
//    {
//        $this->adminLists['Section information'] = ElementFactory::createElement($this->context, AdminList::ELEMENT_NAME, 'Section information', SimpleTable::ELEMENT_NAME);
//        $this->adminLists['Content items'] = ElementFactory::createElement($this->context, AdminList::ELEMENT_NAME, 'Content items', SimpleTable::ELEMENT_NAME, $this->secondListContainerLocator);
//        $this->adminList = ElementFactory::createElement($this->context, AdminList::ELEMENT_NAME, '', SimpleTable::ELEMENT_NAME);
//        $this->dialogs['Section information'] = ElementFactory::createElement($this->context, Dialog::ELEMENT_NAME);
//
//    }
    /**
     * @var string
     */
    private $expectedSectionName;


    /**
     * Verifies if list of Content items is empty.
     *
     * @param string $tabName
     */
    public function verifyListIsEmpty(): void
    {
        $firstRowValue = $this->adminLists['Content items']->table->getCellValue(1, 1);

        if (
        !($this->adminLists['Content items']->table->getItemCount() === 1 &&
            strpos($firstRowValue, 'No Content items.') !== false)
        ) {
            throw new \Exception('The "Content items" list is not empty.');
        }
    }

    public function startAssigningToItem(string $sectionName): void
    {
        Assert::assertEquals(
            $sectionName,
            $this->sectionName,
            'Wrong role page'
        );

        $this->adminLists['Content items']->clickAssignButton();
    }

    public function startEditingSelf(string $itemName): void
    {
        $this->adminLists['Section information']->table->clickEditButton($itemName);
    }

    public function verifyItemAttribute(string $label, string $value): void
    {
        Assert::assertEquals(
            $value,
            $this->adminLists['Section information']->table->getTableCellValue($label),
            sprintf('Attribute "%s" has wrong value.', $label)
        );
    }

    public function verifyContentItem(string $name, string $contentType, string $path): void
    {
        if ($this->adminLists['Content items']->isElementOnTheList($name)) {
            Assert::assertEquals(
                $contentType,
                $this->adminLists['Content items']->table->getTableCellValue('Content Type', $name),
                sprintf('Content item "%s" has wrong "Content Type".', $name)
            );
            Assert::assertEquals(
                $path,
                $this->adminLists['Content items']->table->getTableCellValue('Path', $name),
                sprintf('Content item "%s" has wrong "Path".', $name)
            );
        } else {
            Assert::fail(sprintf('There is no "%s" Content item on the list.', $name));
        }
    }

    public function getAssignedContentCount(string $sectionName): int
    {
        return (int) $this->adminList->table->getTableCellValue($sectionName, 'Assigned content');
    }

    protected function getRoute(): string
    {
        return 'section/view'; // TODO: take section name into account
    }

    public function setExpectedSectionName(string $sectionName): void
    {
        $this->expectedSectionName = $sectionName;
    }

    public function verifyIsLoaded(): void
    {
        Assert::assertEquals(
            'Section: %s',
            $this->getHTMLPage()->find($this->getLocator('pageTitle'))->getText()
        );

        $this->adminLists['Section information']->verifyVisibility();
        $this->adminLists['Content items']->verifyVisibility();
    }

    public function getName(): string
    {
        return 'Section';
    }

    protected function specifyLocators(): array
    {
        return [
            new VisibleCSSLocator('pageTitle', '.ez-header h1'),
        ];
    }
}
