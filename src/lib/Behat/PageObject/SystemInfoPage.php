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
use EzSystems\EzPlatformAdminUi\Behat\PageElement\Table\TableInterface;
use EzSystems\EzPlatformAdminUi\Behat\PageElement\TableNavigationTab;
use PHPUnit\Framework\Assert;

class SystemInfoPage extends Page
{
    /**
     * @var TableNavigationTab
     */
    protected $tableNavigationTab;

    /**
     * @var TableInterface
     */
    private $table;

    public function __construct(Browser $browser, TableNavigationTab $tableNavigationTab, Table $table)
    {
        parent::__construct($browser);

        $this->tableNavigationTab = $tableNavigationTab;
        $this->table = $table
            ->withParentLocator($this->getLocator('packagesTable'))
            ->endConfiguration();

    }

    public function goToTab(string $tabName)
    {
        $this->tableNavigationTab->goToTab($tabName);
    }

    public function verifyCurrentTableHeader(string $header)
    {
        $this->getHTMLPage()->find($this->getLocator('tableTitle'))->assert()->textEquals($header);
    }

    public function verifyPackages(array $packages)
    {
        $actualPackageData = $this->table->getColumnValues(['Name']);

        foreach ($packages as $package) {
            Assert::assertContains($package, $actualPackageData['Name']);
        }
    }

    public function verifyBundles(array $bundleNames)
    {
        $this->verifyPackages($bundleNames);
    }

    protected function getRoute(): string
    {
        return '/systeminfo';
    }

    public function verifyIsLoaded(): void
    {
        $this->tableNavigationTab->verifyIsLoaded();
        $this->verifyCurrentTableHeader('Product');
    }

    public function getName(): string
    {
        return 'System Information';
    }

    protected function specifyLocators(): array
    {
        return [
            new VisibleCSSLocator('pageTitle', '.ez-header h1'),
            new VisibleCSSLocator('tableTitle', '.tab-pane.active .ez-fieldgroup__name'),
            new VisibleCSSLocator('packagesTable', '.tab-pane.active .ez-fieldgroup:nth-of-type(2)'),
        ];
    }
}
