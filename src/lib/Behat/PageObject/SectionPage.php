<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\EzPlatformAdminUi\Behat\PageObject;

use eZ\Publish\API\Repository\SectionService;
use EzSystems\Behat\Browser\Page\Browser;
use EzSystems\Behat\Browser\Page\Page;
use EzSystems\Behat\Browser\Locator\VisibleCSSLocator;
use EzSystems\EzPlatformAdminUi\Behat\PageElement\Dialog;
use EzSystems\EzPlatformAdminUi\Behat\PageElement\Table\Table;
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

    /**
     * @var string
     */
    private $expectedSectionName;

    /** @var int */
    private $expectedSectionId;
    /**
     * @var SectionService
     */
    private $sectionService;

    /**
     * @var \EzSystems\EzPlatformAdminUi\Behat\PageElement\Table\TableInterface
     */
    private $contentItemsTable;
    /**
     * @var \EzSystems\EzPlatformAdminUi\Behat\PageElement\Table\TableInterface
     */
    private $sectionInformationTable;
    /**
     * @var Dialog
     */
    private $dialog;

    public function __construct(Browser $browser, SectionService $sectionService, Table $contentItemsTable, Table $sectionInformationTable, Dialog $dialog)
    {
        parent::__construct($browser);
        $this->sectionService = $sectionService;
        $this->contentItemsTable = $contentItemsTable->withParentLocator($this->getLocator('contentItemsTable'))->endConfiguration();
        $this->sectionInformationTable = $sectionInformationTable->withParentLocator($this->getLocator('sectionInfoTable'))->endConfiguration();
        $this->dialog = $dialog;
    }

    public function isContentListEmpty(): bool
    {
        return $this->contentItemsTable->isEmpty();
    }

    public function hasProperties(array $sectionProperties): bool
    {
        return $this->sectionInformationTable->hasElement($sectionProperties);
    }

    public function hasAssignedItem(array $elementData): bool
    {
        return $this->contentItemsTable->hasElement($elementData);
    }

    public function edit()
    {
        $this->sectionInformationTable->getTableRow(['Name' => $this->expectedSectionName])->edit();
    }

    public function assignContentItems()
    {
        $this->getHTMLPage()->find($this->getLocator('assignButton'))->click();
    }

    public function hasAssignedItems(): bool
    {
        return !$this->contentItemsTable->isEmpty();
    }

    public function delete()
    {
        $this->getHTMLPage()->find($this->getLocator('deleteButton'))->click();
        $this->dialog->verifyIsLoaded();
        $this->dialog->confirm();
    }

    protected function getRoute(): string
    {
        return sprintf(
            '/section/view/%d', $this->expectedSectionId
        );
    }

    public function setExpectedSectionName(string $sectionName): void
    {
        $this->expectedSectionName = $sectionName;

        foreach ($this->sectionService->loadSections() as $section) {
            if ($section->name === $sectionName) {
                $this->expectedSectionId = $section->id;
                return;
            }
        }

    }

    public function verifyIsLoaded(): void
    {
        Assert::assertEquals(
            sprintf('Section: %s', $this->expectedSectionName),
            $this->getHTMLPage()->find($this->getLocator('pageTitle'))->getText()
        );
    }

    public function getName(): string
    {
        return 'Section';
    }

    protected function specifyLocators(): array
    {
        return [
            new VisibleCSSLocator('pageTitle', '.ez-header h1'),
            new VisibleCSSLocator('contentItemsTable', '.ez-container:nth-of-type(2)'),
            new VisibleCSSLocator('assignButton', '#section_content_assign_locations_select_content'),
            new VisibleCSSLocator('sectionInfoTable', '.ez-container:nth-of-type(1)'),
            new VisibleCSSLocator('deleteButton', 'button[data-original-title="Delete Section"]'),
        ];
    }
}
