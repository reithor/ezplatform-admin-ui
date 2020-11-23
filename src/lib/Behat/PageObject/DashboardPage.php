<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\EzPlatformAdminUi\Behat\PageObject;

use Behat\Mink\Session;
use eZ\Publish\Core\MVC\Symfony\SiteAccess\Router;
use EzSystems\Behat\Browser\Page\Browser;
use EzSystems\Behat\Browser\Page\Page;
use EzSystems\Behat\Browser\Selector\CSSSelector;
use EzSystems\EzPlatformAdminUi\Behat\PageElement\TableNavigationTab;
use FriendsOfBehat\SymfonyExtension\Mink\MinkParameters;
use PHPUnit\Framework\Assert;

class DashboardPage extends Page
{

    public const TABLE_CONTAINER = '#ez-tab-list-content-dashboard-my .tab-pane.active';

    public $dashboardTable;
    /**
     * @var TableNavigationTab
     */
    private $tableNavigationTab;

    public function __construct(Browser $browser, TableNavigationTab $tableNavigationTab)
    {
        parent::__construct($browser);
        $this->tableNavigationTab = $tableNavigationTab;
    }

    public function switchTab(string $tableName, string $tabName)
    {
        $table = $this->context->getElementByText('My content', $this->fields['tableSelector'], $this->fields['tableTitle']);
        $this->context->getElementByText($tabName, $this->fields['tableTabSelector'], null, $table)->click();
    }

    public function isListEmpty(): bool
    {
        $tableValue = $this->context->findElement($this::TABLE_CONTAINER)->getText();

        return strpos($tableValue, 'No content.') !== false;
    }

    public function editDraft(string $contentDraftName)
    {
        $this->dashboardTable->clickEditButton($contentDraftName);
    }

    protected function getRoute(): string
    {
        return 'dashboard';
    }

    public function verifyIsLoaded(): void
    {
        Assert::assertEquals('My dashboard', $this->getHTMLPage()->find($this->getSelector('pageTitle'))->getText());

        Assert::assertNotNull($this->context->getElementByText('My content', $this->fields['tableSelector'], $this->fields['tableTitle']));
        $this->tableNavigationTab->verifyIsLoaded();
        $this->dashboardTable->verifyVisibility();
    }

    public function getName(): string
    {
        return 'Dashboard';
    }

    protected function specifySelectors(): array
    {
        return [
                new CSSSelector('tableSelector', '.ez-card'),
                new CSSSelector('tableTitle', '.ez-card__title'),
                new CSSSelector('tableTabSelector', '.ez-tabs .nav-item'),
                new CSSSelector('pageTitle', '.ez-header h1'),
        ];
    }
}
