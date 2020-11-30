<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\EzPlatformAdminUi\Behat\PageObject;

use EzSystems\Behat\Browser\Context\OldBrowserContext;
use EzSystems\Behat\Browser\Page\Browser;
use EzSystems\Behat\Browser\Page\Page;
use EzSystems\Behat\Browser\Locator\VisibleCSSLocator;
use EzSystems\EzPlatformAdminUi\Behat\PageElement\Dialog;
use EzSystems\EzPlatformAdminUi\Behat\PageElement\Notification;
use EzSystems\EzPlatformAdminUi\Behat\PageElement\Table\Table;
use EzSystems\EzPlatformAdminUi\Behat\PageElement\Table\TableInterface;
use PHPUnit\Framework\Assert;

class SectionsPage extends Page
{
    /**
     * @var TableInterface
     */
    private $table;
    /**
     * @var Notification
     */
    private $notification;
    /**
     * @var Dialog
     */
    private $dialog;

    public function __construct(Browser $browser, Table $table, Dialog $dialog)
    {
        parent::__construct($browser);
        $this->table = $table->withParentLocator($this->getLocator('tableContainer'))
            ->endConfiguration();
        $this->dialog = $dialog;
    }

    public function createNew(): void
    {
        $this->getHTMLPage()->find($this->getLocator('createButton'))->click();
    }

    public function isSectionOnTheList(string $sectionName): bool
    {
        return $this->table->hasElement(['Name' => $sectionName]);
    }

    public function assignContentItems(string $sectionName)
    {
        $this->table->getTableRow(['Name' => $sectionName])->assign();
    }

    public function getAssignedContentItemsCount(string $sectionName): int
    {
        return (int) $this->table->getTableRow(['Name' => $sectionName])->getCellValue('Assigned content');
    }

    public function editSection(string $sectionName)
    {
        $this->table->getTableRow(['Name' => $sectionName])->edit();
    }

    public function canBeSelected(string $sectionName): bool
    {
        return $this->table->getTableRow(['Name' => $sectionName])->canBeSelected();
    }

    public function deleteSection(string $sectionName)
    {
        $this->table->getTableRow(['Name' => $sectionName])->select();
        $this->getHTMLPage()->find($this->getLocator('deleteButton'))->click();
        $this->dialog->verifyIsLoaded();
        $this->dialog->confirm();
    }

    protected function getRoute(): string
    {
        return '/section/list';
    }

    public function getName(): string
    {
        return 'Sections';
    }

    public function verifyIsLoaded(): void
    {
        Assert::assertEquals(
            'Sections',
            $this->getHTMLPage()->find($this->getLocator('pageTitle'))->getText()
        );
    }

    protected function specifyLocators(): array
    {
        return [
            new VisibleCSSLocator('pageTitle', '.ez-header h1'),
            new VisibleCSSLocator('createButton', '.ez-icon-create'),
            new VisibleCSSLocator('deleteButton', '.ez-icon-trash,button[data-original-title^="Delete"]'),
            new VisibleCSSLocator('tableContainer', '.ez-container'),
        ];
    }
}
