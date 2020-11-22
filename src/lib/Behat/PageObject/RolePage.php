<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\EzPlatformAdminUi\Behat\PageObject;

use Behat\Mink\Session;
use EzSystems\Behat\Browser\Page\Page;
use EzSystems\EzPlatformAdminUi\Behat\PageElement\AdminList;
use EzSystems\EzPlatformAdminUi\Behat\PageElement\Dialog;
use EzSystems\EzPlatformAdminUi\Behat\PageElement\TableNavigationTab;
use FriendsOfBehat\SymfonyExtension\Mink\MinkParameters;

class RolePage extends Page
{
    /** @var string Name by which Page is recognised */
    public const PAGE_NAME = 'Role';
    /** @var string Name of actual group */
    public $roleName;

    private $activeAdminListContainerLocator = '.ez-main-container .tab-pane.active';

    /**
     * @var \EzSystems\EzPlatformAdminUi\Behat\PageElement\AdminList[]
     */
    public $adminLists;

    /**
     * @var \EzSystems\EzPlatformAdminUi\Behat\PageElement\AdminList
     */
    public $adminList;

    /**
     * @var \EzSystems\EzPlatformAdminUi\Behat\PageElement\Dialog
     */
    public $dialog;

    /**
     * @var AdminList|\EzSystems\EzPlatformAdminUi\Behat\PageElement\TableNavigationTab
     */
    public $navLinkTabs;
    /**
     * @var string
     */
    private $expectedRoleName;
    /**
     * @var TableNavigationTab
     */
    private $tableNavigationTab;

    public function __construct(Session $session, MinkParameters $minkParameters, TableNavigationTab $tableNavigationTab, Dialog $dialog)
    {
        parent::__construct($session, $minkParameters);
        $this->tableNavigationTab = $tableNavigationTab;
        $this->dialog = $dialog;
    }

//    public function qwe(OldBrowserContext $context, string $roleName)
//    {
//        $this->roleName = $roleName;
//        $this->adminLists['Policies'] = ElementFactory::createElement($this->context, AdminList::ELEMENT_NAME, 'Policies', SimpleListTable::ELEMENT_NAME, $this->activeAdminListContainerLocator);
//        $this->adminLists['Assignments'] = ElementFactory::createElement($this->context, AdminList::ELEMENT_NAME, 'Users and Groups', SimpleListTable::ELEMENT_NAME, $this->activeAdminListContainerLocator);
//        $this->adminList = ElementFactory::createElement($this->context, AdminList::ELEMENT_NAME, '', SimpleListTable::ELEMENT_NAME, $this->activeAdminListContainerLocator);
//        $this->navLinkTabs = ElementFactory::createElement($this->context, TableNavigationTab::ELEMENT_NAME);
//        $this->dialog = ElementFactory::createElement($this->context, Dialog::ELEMENT_NAME);
//        $this->pageTitle = sprintf('Role "%s"', $roleName);
//        $this->pageTitleLocator = '.ez-header h1';
//        $this->fields = [
//            'assignButton' => '.btn-secondary',
//        ];
//    }

    /**
     * Verifies if list from given tab is empty.
     *
     * @param string $tabName
     */
    public function verifyListIsEmpty(string $tabName): void
    {
        $this->tableNavigationTab->goToTab($tabName);
        if ($this->adminLists[$tabName]->table->getItemCount() > 0) {
            throw new \Exception(sprintf('"%s" list is not empty.', $tabName));
        }
    }

    public function startEditingItem(string $itemName): void
    {
        $this->tableNavigationTab->goToTab('Policies');
        $this->adminLists['Policies']->table->clickEditButton($itemName);
    }

    public function startCreatingItem(): void
    {
        $this->tableNavigationTab->goToTab('Policies');
        $this->adminLists['Policies']->clickPlusButton();
    }

    /**
     * Verifies if Role with Limitation from given list is present.
     *
     * @param string $listName
     * @param string $moduleAndFunction
     * @param string $limitation
     *
     * @return bool
     */
    public function isRoleWithLimitationPresent(string $listName, string $moduleAndFunction, string $limitation): bool
    {
        $this->tableNavigationTab->goToTab($listName);
        $adminList = $this->adminLists[$listName];
        $actualPoliciesList = $adminList->table->getTableHash();

        $expectedModule = explode('/', $moduleAndFunction)[0];
        $expectedFunction = explode('/', $moduleAndFunction)[1];

        foreach ($actualPoliciesList as $policy) {
            if (
                $policy['Module'] === $expectedModule &&
                $policy['Function'] === $expectedFunction &&
                $this->isLimitationCorrect($limitation, $policy['Limitations'])
            ) {
                return true;
            }
        }

        return false;
    }

    private function isLimitationCorrect(string $expectedLimitation, string $actualLimitations): bool
    {
        if ($expectedLimitation === 'None') {
            return $actualLimitations === 'None';
        }

        [$expectedLimitationType, $expectedLimitationValue] = explode(':', $expectedLimitation);
        $expectedLimitationValues = array_map(function (string $value) {
            return trim($value);
        }, explode(',', $expectedLimitationValue));

        $limitationTypePos = strpos($actualLimitations, $expectedLimitationType);
        $actualLimitationsStartingFromExpectedType = substr($actualLimitations, $limitationTypePos);

        $valuePositionsDictionary = [];

        foreach ($expectedLimitationValues as $value) {
            $position = strpos($actualLimitationsStartingFromExpectedType, $value);
            if ($position === false) {
                return false;
            }

            $valuePositionsDictionary[$position] = $value;
        }

        ksort($valuePositionsDictionary);
        $combinedExpectedLimitation = sprintf('%s: %s', $expectedLimitationType, implode(', ', $valuePositionsDictionary));

        return strpos($actualLimitations, $combinedExpectedLimitation) !== false;
    }

    public function setExpectedRoleName(string $roleName)
    {
        $this->expectedRoleName = $roleName;
    }

    public function goToTab(string $tabName)
    {
        $this->tableNavigationTab->goToTab($tabName);
    }

    public function deleteSelectedItems()
    {
        $this->adminList->clickTrashButton(); //fix me
        $this->dialog->verifyIsLoaded();
        $this->dialog->confirm();
    }

    public function selectElement(array $array)
    {
    }

    public function getRoute(): string
    {
        return '/role/'; // TODO: get role ID from RoleService
    }

    public function getName(): string
    {
        return 'Role';
    }

    public function specifySelectors(): array
    {
        // TODO: Implement specifySelectors() method.
    }

    public function verifyIsLoaded(): void
    {
        $this->tableNavigationTab->verifyIsLoaded();
        $this->adminLists['Policies']->verifyIsLoaded();
        $this->tableNavigationTab->goToTab('Assignments');
        $this->adminLists['Assignments']->verifyIsLoaded();
    }
}
