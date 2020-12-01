<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\EzPlatformAdminUi\Behat\BusinessContext;

use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\TableNode;
use EzSystems\EzPlatformAdminUi\Behat\PageObject\ContentTypeGroupPage;
use EzSystems\EzPlatformAdminUi\Behat\PageObject\ContentTypePage;
use EzSystems\EzPlatformAdminUi\Behat\PageObject\ContentTypeUpdatePage;
use PHPUnit\Framework\Assert;

class ContentTypeContext implements Context
{
    /**
     * @var ContentTypePage
     */
    private $contentTypePage;
    /**
     * @var ContentTypeUpdatePage
     */
    private $contentTypeUpdatePage;
    /**
     * @var ContentTypeGroupPage
     */
    private $contentTypeGroupPage;

    public function __construct(ContentTypePage $contentTypePage, ContentTypeUpdatePage $contentTypeUpdatePage, ContentTypeGroupPage $contentTypeGroupPage)
    {
        $this->contentTypePage = $contentTypePage;
        $this->contentTypeUpdatePage = $contentTypeUpdatePage;
        $this->contentTypeGroupPage = $contentTypeGroupPage;
    }

    /**
     * @Then Content Type has proper Global properties
     */
    public function contentTypeHasProperGlobalProperties(TableNode $table): void
    {
        foreach ($table->getHash() as $row) {
            Assert::assertTrue($this->contentTypePage->hasProperty($row['label'], $row['value']));
        }
    }

    /**
     * @When I create a new Content Type
     */
    public function createNewContentType(): void
    {
        $this->contentTypeGroupPage->createNew();
    }

    /**
     * @Then Content Type :contentTypeName has proper fields
     */
    public function contentTypeHasProperFields(TableNode $table): void
    {
        foreach ($table->getHash() as $row)
        {
            Assert::assertTrue($this->contentTypePage->hasFieldType(
                ['Name' => $row['fieldName'], 'Type' => $row['fieldType']]
            ));
        }
    }

    /**
     * @Given there's no :contentTypeName on Content Types list
     */
    public function thereSNoOnContentTypesList($contentTypeName)
    {
        Assert::assertFalse($this->contentTypeGroupPage->isContentTypeOnTheList($contentTypeName));
    }

    /**
     * @Given there's a :contentTypeName on Content Types list
     */
    public function thereAContentTypeOnContentTypesList($contentTypeName)
    {
        Assert::assertTrue($this->contentTypeGroupPage->isContentTypeOnTheList($contentTypeName));
    }

    /**
     * @When I add field :fieldName to Content Type definition
     */
    public function iAddField(string $fieldName): void
    {
         $this->contentTypeUpdatePage->addFieldDefinition($fieldName);
    }

    /**
     * @When I set :field to :value for :fieldName field
     */
    public function iSetFieldDefinitionData(string $label, string $value, string $fieldName): void
    {
        $this->contentTypeUpdatePage->fillFieldDefinitionFieldWithValue($fieldName, $label, $value);
    }

    /**
     * @When I start editing Content Type :contentTypeName
     */
    public function iStartEditingItem(string $contentTypeName): void
    {
        $this->contentTypeGroupPage->edit($contentTypeName);
    }

    /**
     * @When I delete :contentTypeName Content Type
     */
    public function iDeleteContentType(string $contentTypeName)
    {
        $this->contentTypeGroupPage->delete($contentTypeName);
    }

    /**
     * @Given I'm on Content Type Page for :contentTypeGroup group
     */
    public function iMOnContentTypePageFor(string $contentTypeGroup)
    {
        $this->contentTypeGroupPage->setExpectedContentTypeGroupName($contentTypeGroup);
        $this->contentTypeGroupPage->open('admin');
        $this->contentTypeGroupPage->verifyIsLoaded();
    }

    /**
     * @Then I should be on Content Type group page for :contentTypeGroup group
     */
    public function iShouldBeOnContentTypeGroupPage($contentTypeGroup)
    {
        $this->contentTypeGroupPage->setExpectedContentTypeGroupName($contentTypeGroup);
        $this->contentTypeGroupPage->verifyIsLoaded();
    }

    /**
     * @Then I should be on Content Type page for :contentTypeName
     */
    public function iShouldBeOnContentTypePage(string $contentTypeName)
    {
        $this->contentTypePage->setExpectedContentTypeName($contentTypeName);
        $this->contentTypePage->verifyIsLoaded();
    }
}
