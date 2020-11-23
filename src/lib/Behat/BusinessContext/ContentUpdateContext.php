<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\EzPlatformAdminUi\Behat\BusinessContext;

use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\TableNode;
use EzSystems\EzPlatformAdminUi\Behat\PageElement\Fields\NonEditableField;
use EzSystems\EzPlatformAdminUi\Behat\PageObject\ContentUpdateItemPage;
use PHPUnit\Framework\Assert;

class ContentUpdateContext implements Context
{
    /**
     * @var ContentUpdateItemPage
     */
    private $contentUpdateItemPage;

    public function __construct(ContentUpdateItemPage $contentUpdateItemPage)
    {
        $this->contentUpdateItemPage = $contentUpdateItemPage;
    }

    /**
     * @When I set content fields
     */
    public function iSetFields(TableNode $table): void
    {
        foreach ($table->getHash() as $row) {
            $values = $this->filterOutNonEmptyValues($row);
            $this->contentUpdateItemPage->fillFieldWithValue($row['label'], $values);
        }
    }

    /**
     * @Given the :fieldName field is noneditable
     */
    public function verifyFieldIsNotEditable(string $fieldName): void
    {
        $field = $this->contentUpdateItemPage->getField($fieldName);
        Assert::assertEquals(NonEditableField::EXPECTED_NON_EDITABLE_TEXT, $field->getValue()[0]);
    }

    /**
     * @When I select :contentPath from Image Asset Repository for :fieldName field
     */
    public function selectContentFromIARepository(string $contentPath, string $fieldName): void
    {
        $this->contentUpdateItemPage->getField($fieldName)->selectFromRepository($contentPath);
    }

    private function filterOutNonEmptyValues(array $parameters): array
    {
        $values = $parameters;
        unset($values['label']);

        return array_filter($values, function ($element) { return !empty($element) || $element === 0;});
    }

    /**
     * @Then content fields are set
     */
    public function verifyFieldsAreSet(TableNode $table): void
    {
        foreach ($table->getHash() as $row) {
            $this->contentUpdateItemPage->verifyFieldHasValue($row['label'], $row);
        }
    }

    /**
     * @When I click on the close button
     */
    public function iClickCloseButton(): void
    {
        $this->contentUpdateItemPage->close();
    }

    /**
     * @Then article main content field is set to :intro
     */
    public function verifyArticleMainContentFieldIsSet(string $intro): void
    {
        throw new \Exception('refactor me...');

        $updateItemPage = PageObjectFactory::createPage($this->browserContext, ContentUpdateItemPage::PAGE_NAME, '');
        $fieldName = EnvironmentConstants::get('ARTICLE_MAIN_FIELD_NAME');
        $updateItemPage->contentUpdateForm->verifyFieldHasValue(['label' => $fieldName, 'value' => $intro]);
    }
}
