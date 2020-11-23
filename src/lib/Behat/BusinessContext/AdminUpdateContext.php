<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\EzPlatformAdminUi\Behat\BusinessContext;

use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\TableNode;
use EzSystems\EzPlatformAdminUi\Behat\PageElement\Notification;
use EzSystems\EzPlatformAdminUi\Behat\PageObject\AdminUpdateItemPage;
use PHPUnit\Framework\Assert;

/** Context for common actions for creating and updating */
class AdminUpdateContext implements Context
{
    /**
     * @var AdminUpdateItemPage
     */
    private $adminUpdateItemPage;

    public function __construct(AdminUpdateItemPage $adminUpdateItemPage)
    {
        $this->adminUpdateItemPage = $adminUpdateItemPage;
    }

    /**
     * @When I set fields
     */
    public function iSetFields(TableNode $table): void
    {
        foreach ($table->getHash() as $row) {
            $this->adminUpdateItemPage->fillFieldWithValue($row['label'], $row['value']);
        }
    }

    /**
     * @Then fields are set
     */
    public function verifyFieldsAreSet(TableNode $table): void
    {
        foreach ($table->getHash() as $row) {
            Assert::assertEquals($row['value'], $this->adminUpdateItemPage->getFieldValue($row['label']));
        }
    }

    /**
     * @When I add field :fieldName to Content Type definition
     */
    public function iAddField(string $fieldName): void
    {

        throw new \Exception('refactor me ...');
        // $updateItemPage = PageObjectFactory::createPage($this->browserContext, AdminUpdateItemPage::PAGE_NAME);
        // $updateItemPage->adminUpdateForm->selectFieldDefinition($fieldName);
        // $updateItemPage->adminUpdateForm->clickAddFieldDefinition();
        // $updateItemPage->adminUpdateForm->verifyNewFieldDefinitionFormExists($fieldName);
        // $notification = ElementFactory::createElement($this->browserContext, Notification::ELEMENT_NAME);
        // $notification->verifyVisibility();
        // $notification->verifyAlertSuccess();
        // $notification->closeAlert();
    }

    /**
     * @When I set :field in :containerName to :value
     */
    public function iSetFieldInContainer(string $field, string $containerName, string $value): void
    {
        throw new \Exception('refactor me ...');

        // $updateItemPage = PageObjectFactory::createPage($this->browserContext, AdminUpdateItemPage::PAGE_NAME);
        // $updateItemPage->adminUpdateForm->expandFieldDefinition($containerName);
        // $updateItemPage->adminUpdateForm->fillFieldWithValue($field, $value, $containerName);
    }
}
