<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\EzPlatformAdminUi\Behat\BusinessContext;

use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\TableNode;
use EzSystems\EzPlatformAdminUi\Behat\PageObject\ContentTypePage;
use EzSystems\Behat\Browser\Factory\PageObjectFactory;
use PHPUnit\Framework\Assert;

class ContentTypeContext implements Context
{
    private $contentTypeTableHeaders = ['Name', 'Identifier', 'Description'];
    /**
     * @var ContentTypePage
     */
    private $contentTypePage;

    public function __construct(ContentTypePage $contentTypePage)
    {
        $this->contentTypePage = $contentTypePage;
    }

    /**
     * @Then Content Type has proper Global properties
     */
    public function contentTypeHasProperGlobalProperties(TableNode $table): void
    {
        throw new \Exception('refactor me...');

        foreach ($table->getHash() as $row) {
            if (in_array($row['label'], $this->contentTypeTableHeaders)) {
                $actualValue = $contentTypePage->contentTypeAdminList->table->getTableCellValue($row['label']);
            } else {
                $actualValue = $contentTypePage->globalPropertiesTable->getTableCellValue($row['label']);
            }

            Assert::assertEquals(
                $row['value'],
                $actualValue,
                sprintf('Content Type\'s %s is %s instead of %s.', $row['label'], $actualValue, $row['value'])
            );
        }
    }

    /**
     * @Then Content Type :contentTypeName has field :fieldName of type :fieldType
     */
    public function contentTypeHasField(string $contentTypeName, string $fieldName, string $fieldType): void
    {
        throw new \Exception('refactor me...');


        $actualFieldType = PageObjectFactory::createPage($this->browserContext, ContentTypePage::PAGE_NAME, $contentTypeName)
            ->fieldsAdminList->table->getTableCellValue('Type', $fieldName);

        if ($actualFieldType !== $fieldType) {
            throw new \Exception(
                sprintf(
                    'Content Type Field %s is of type %s instead of %s.',
                    $fieldName,
                    $actualFieldType,
                    $fieldType
                ));
        }
    }

    /**
     * @Then Content Type :contentTypeName has proper fields
     */
    public function contentTypeHasProperFields(string $contentTypeName, TableNode $table): void
    {
        throw new \Exception('refactor me...');


        $hash = $table->getHash();
        foreach ($hash as $row) {
            $this->contentTypeHasField($contentTypeName, $row['fieldName'], $row['fieldType']);
        }
    }
}
