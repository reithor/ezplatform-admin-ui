<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\EzPlatformAdminUi\Behat\BusinessContext;

use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\TableNode;
use EzSystems\EzPlatformAdminUi\Behat\PageObject\SystemInfoPage;

class SystemInfoContext implements Context
{
    private $systemInfoTableMapping = [
        'Bundles' => 'Symfony Kernel',
        'Packages' => 'Composer',
    ];

    /**
     * @var SystemInfoPage
     */
    private $systemInfoPage;

    public function __construct(SystemInfoPage $systemInfoPage)
    {
        $this->systemInfoPage = $systemInfoPage;
    }

    /**
     * @When I go to :tabName tab in System Information
     */
    public function iGoToTabInSystemInfo(string $tabName): void
    {
        $this->systemInfoPage->verifyIsLoaded();
        $this->systemInfoPage->goToTab($tabName);
    }

    /**
     * @Then I see :tabName system information table
     */
    public function iSeeSystemInformationTable(string $tabName): void
    {
        $this->systemInfoPage->verifyCurrentTableHeader($tabName);
    }

    /**
     * @Then I see :tableName table with given records
     */
    public function iSeeRecordsInSystemInformation(string $tableName, TableNode $records): void
    {
        $this->systemInfoPage->verifyPackages();
    }

    /**
     * @Then I see listed packages
     */
    public function iSeeListedPackages(TableNode $packages): void
    {
        $packageNames = array_map(function (array $row) {
            return $row['Name'];
        }, $packages->getHash());

        $this->systemInfoPage->verifyPackages($packageNames);
    }

    /**
     * @Then I see listed bundles
     */
    public function iSeeListedBundles(TableNode $bundles): void
    {
        $bundleNames = array_map(function (array $row) {
            return $row['Name'];
        }, $bundles->getHash());

        $this->systemInfoPage->verifyBundles($bundleNames);
    }
}
