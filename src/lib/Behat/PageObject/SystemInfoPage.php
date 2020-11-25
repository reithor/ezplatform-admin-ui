<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\EzPlatformAdminUi\Behat\PageObject;

use Behat\Mink\Session;
use EzSystems\Behat\Browser\Page\Browser;
use EzSystems\Behat\Browser\Page\Page;
use EzSystems\Behat\Browser\Locator\VisibleCSSLocator;
use EzSystems\EzPlatformAdminUi\Behat\PageElement\AdminList;
use EzSystems\EzPlatformAdminUi\Behat\PageElement\TableNavigationTab;
use EzSystems\EzPlatformAdminUi\Behat\PageElement\Tables\SimpleTable;
use EzSystems\EzPlatformAdminUi\Behat\PageElement\Tables\SystemInfoTable;
use FriendsOfBehat\SymfonyExtension\Mink\MinkParameters;
use PHPUnit\Framework\Assert;

class SystemInfoPage extends Page
{
    /**
     * @var SystemInfoTable
     */
    protected $systemInfoTable;

    /**
     * @var TableNavigationTab
     */
    protected $tableNavigationTab;

    public function __construct(Browser $browser, TableNavigationTab $tableNavigationTab)
    {
        parent::__construct($browser);

        $this->tableNavigationTab = $tableNavigationTab;
    }

    public function verifySystemInfoTable(string $tabName): void
    {
//        $systemInfoTable->verifyHeader($tabName);
    }

    public function verifySystemInfoRecords(string $tableName, array $records): void
    {
        $tab = ElementFactory::createElement($this->context, AdminList::ELEMENT_NAME, $tableName, SimpleTable::ELEMENT_NAME, '.ez-main-container .tab-pane.active');
        $tab->verifyVisibility();
        $tableHash = $tab->table->getTableHash();

        foreach ($records as $desiredRecord) {
            $found = false;
            foreach ($tableHash as $actualRecord) {
                if ($desiredRecord['Name'] === $actualRecord['Name']) {
                    $found = true;
                    break;
                }
            }
            if (!$found) {
                Assert::fail(sprintf('Could not find requested record [%s] on the "%s" list.', $desiredRecord['Name'], $tableName));
            }
        }
    }

    public function goToTab(string $tabName)
    {
        $this->tableNavigationTab->goToTab($tabName);
    }

    protected function getRoute(): string
    {
        return '/systeminfo';
    }

    public function verifyIsLoaded(): void
    {
        $this->tableNavigationTab->verifyIsLoaded();
        $this->verifySystemInfoTable('Product');
    }

    public function getName(): string
    {
        return 'System Information';
    }

    protected function specifyLocators(): array
    {
        return [
            new VisibleCSSLocator('pageTitle', '.ez-header h1'),
        ];
    }
}
