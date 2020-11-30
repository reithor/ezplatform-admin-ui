<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\EzPlatformAdminUi\Behat\PageObject;

use EzSystems\Behat\Browser\Page\Browser;
use EzSystems\Behat\Browser\Page\Page;
use EzSystems\Behat\Browser\Locator\VisibleCSSLocator;
use EzSystems\EzPlatformAdminUi\Behat\PageElement\Table\Table;

class DashboardPage extends Page
{
    /**
     * @var Table
     */
    private $table;

    public function __construct(Browser $browser, Table $table)
    {
        parent::__construct($browser);
        $this->table = $table;
    }

    public function switchTab(string $tableName, string $tabName)
    {
        $this->getHTMLPage()
            ->findAll($this->getLocator('tableTitle'))->getByText($tableName)
            ->findAll($this->getLocator('tableTab'))->getByText($tabName)
            ->click();
    }

    public function isListEmpty(): bool
    {
        return $this->table->isEmpty();
    }

    public function editDraft(string $contentDraftName)
    {
        $this->table->getTableRow(['Name' => $contentDraftName])->edit();
    }

    protected function getRoute(): string
    {
        return 'dashboard';
    }

    public function verifyIsLoaded(): void
    {
        $this->getHTMLPage()->find($this->getLocator('pageTitle'))->assert()->textEquals('My dashboard');
        $this->getHTMLPage()->find($this->getLocator('table'))->assert()->isVisible();
    }

    public function getName(): string
    {
        return 'Dashboard';
    }

    protected function specifyLocators(): array
    {
        return [
                new VisibleCSSLocator('tableSelector', '.ez-card'),
                new VisibleCSSLocator('tableTitle', '.ez-card__title'),
                new VisibleCSSLocator('tableTab', '.ez-tabs .nav-item'),
                new VisibleCSSLocator('pageTitle', '.ez-header h1'),
                new VisibleCSSLocator('table', '#ez-tab-list-content-dashboard-my .tab-pane.active'),
        ];
    }
}
