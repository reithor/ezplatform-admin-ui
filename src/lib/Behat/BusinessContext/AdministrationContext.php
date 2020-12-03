<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\EzPlatformAdminUi\Behat\BusinessContext;

use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\TableNode;
use EzSystems\Behat\Browser\Page\Page;
use EzSystems\EzPlatformAdminUi\Behat\PageElement\Dialog;
use EzSystems\EzPlatformAdminUi\Behat\PageObject\ObjectStateGroupPage;
use EzSystems\EzPlatformAdminUi\Behat\PageObject\ObjectStateGroupsPage;
use EzSystems\EzPlatformAdminUi\Behat\PageObject\RolePage;
use EzSystems\EzPlatformAdminUi\Behat\PageObject\RolesPage;
use EzSystems\EzPlatformAdminUi\Behat\PageObject\ContentTypeGroupPage;
use EzSystems\EzPlatformAdminUi\Behat\PageObject\ContentTypeGroupsPage;
use EzSystems\EzPlatformAdminUi\Behat\PageObject\LanguagesPage;
use EzSystems\EzPlatformAdminUi\Behat\PageObject\SectionPage;
use EzSystems\EzPlatformAdminUi\Behat\PageObject\SectionsPage;
use PHPUnit\Framework\Assert;

class AdministrationContext implements Context
{
    // private $itemCreateMapping = [
    //     'Content Type group' => ContentTypeGroupsPage::PAGE_NAME,
    //     'Content Type' => ContentTypeGroupPage::PAGE_NAME,
    //     'Language' => LanguagesPage::PAGE_NAME,
    //     'Role' => RolesPage::PAGE_NAME,
    //     'Limitation' => RolePage::PAGE_NAME,
    //     'Policy' => RolePage::PAGE_NAME,
    //     'Section' => SectionsPage::PAGE_NAME,
    //     'Object state group' => ObjectStateGroupsPage::PAGE_NAME,
    //     'Object state' => ObjectStateGroupPage::PAGE_NAME,
    //     'User' => '',
    // ];
    private $emptyHeaderMapping = [
        'Content Type groups' => 'Number of Content Types',
        'Sections' => 'Assigned content',
    ];

    /**
     * @Then I should see :pageName list
     * @Then I should see :pageName :parameter list
     *
     * @param string $pageName
     */
    public function iSeeList(string $pageName, string $parameter = null): void
    {
        throw new \Exception('refactor me ...');

        $contentTypeGroupsPage = PageObjectFactory::createPage($this->browserContext, $pageName, $parameter);
        $contentTypeGroupsPage->verifyElements();
    }

    /**
     * @When I delete :itemType from :containerName
     */
    public function iDeleteItemsFromContainer(string $itemType, ?string $containerName = null, TableNode $settings): void
    {
        throw new \Exception('refactor me ...');

        $hash = $settings->getHash();

        $page = PageObjectFactory::createPage($this->browserContext, $this->itemCreateMapping[$itemType], $containerName);
        foreach ($hash as $setting) {
            $page->adminList->table->selectListElement($setting['item']);
        }

        $this->performDeletion($page);
    }

    /**
     * @When I delete :itemType from details page
     */
    public function iDeleteItemsFromDetails(string $itemType, TableNode $settings): void
    {
        throw new \Exception('refactor me ...');

        $hash = $settings->getHash();

        $page = PageObjectFactory::createPage($this->browserContext, $itemType, $hash[0]['item']);
        $this->performDeletion($page);
    }

    /**
     * @param ContentTypeGroupsPage|LanguagesPage|RolePage|RolesPage|SectionPage|SectionsPage $page
     */
    private function performDeletion(Page $page)
    {
        throw new \Exception('refactor me ...');

        $page->adminList->clickTrashButton();
        $dialog = ElementFactory::createElement($this->browserContext, Dialog::ELEMENT_NAME);
        $dialog->verifyVisibility();
        $dialog->confirm();
    }

    /**
     * @Then :itemType :itemName has attribute :attributeName set to :value
     */
    public function itemHasProperAttribute(string $itemType, string $itemName, string $attributeName, string $value)
    {
        throw new \Exception('refactor me ...');

        $pageObject = PageObjectFactory::createPage($this->browserContext, $itemType, $itemName);

        $pageObject->verifyItemAttribute($attributeName, $value);
    }

    /**
     * @When :itemName on :pageName list has attribute :attributeName set to :value
     */
    public function linkItemHasProperAttribute(string $itemName, string $pageName, string $attributeName, string $value)
    {
        throw new \Exception('refactor me ...');

        $pageObject = PageObjectFactory::createPage($this->browserContext, $pageName);
        $pageObject->verifyItemAttribute($attributeName, $value, $itemName);
    }
}
