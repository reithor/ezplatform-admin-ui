<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\EzPlatformAdminUi\Behat\PageElement\Fields;

use Behat\Mink\Session;
use EzSystems\Behat\Browser\Locator\VisibleCSSLocator;
use EzSystems\EzPlatformAdminUi\Behat\PageElement\UniversalDiscoveryWidget;
use PHPUnit\Framework\Assert;

class ContentRelationSingle extends FieldTypeComponent
{
    /**
     * @var UniversalDiscoveryWidget
     */
    private $universalDiscoveryWidget;

    public function specifyLocators(): array
    {
        return [
            new VisibleCSSLocator('selectContent', '.ez-relations__cta-btn-label'),
        ];
    }

    public function __construct(Browser $browser, UniversalDiscoveryWidget $universalDiscoveryWidget)
    {
        parent::__construct($browser);
        $this->universalDiscoveryWidget = $universalDiscoveryWidget;
    }

    public function setValue(array $parameters): void
    {
        if (!$this->isRelationEmpty()) {
            $itemName = explode('/', $parameters['value'])[substr_count($parameters['value'], '/')];
            if (!$this->contentRelationTable->isElementOnCurrentPage($itemName)) {
                $this->removeActualRelation();
            } else {
                return;
            }
        }

        $selectContent = $this->context->findElement(
            sprintf('%s %s', $this->fields['fieldContainer'], $this->fields['selectContent'])
        );

        Assert::assertNotNull($selectContent, sprintf('Select content button for Field %s not found.', $this->label));

        $selectContent->click();

        $this->universalDiscoveryWidget->selectContent($parameters['value']);
        $this->universalDiscoveryWidget->confirm();
    }

    public function removeActualRelation(): void
    {
        $actualItemName = $this->contentRelationTable->getCellValue(1, 2);
        $this->contentRelationTable->selectListElement($actualItemName);
        $this->contentRelationTable->clickTrashIcon();
    }

    public function getValue(): array
    {
        $fieldInput = $this->context->findElement(
            sprintf('%s %s', $this->fields['fieldContainer'], $this->fields['selectContent'])
        );

        Assert::assertNotNull($fieldInput, sprintf('Input for field %s not found.', $this->label));

        return [$this->contentRelationTable->getCellValue(1, 2)];
    }

    public function verifyValueInItemView(array $values): void
    {
        $explodedValue = explode('/', $values['value']);
        $value = $explodedValue[count($explodedValue) - 1];

        $viewPatternRegex = '/Single relation:[\w\/,: ]* %s [\w \/,:]*/';

        Assert::assertRegExp(
            sprintf($viewPatternRegex, $value),
            $this->getHTMLPage()->find($this->getLocator('fieldContainer'))->getText(),
            'Field has wrong value'
        );
    }

    public function isRelationEmpty(): bool
    {
        $selectSelector = $this->parentLocator->withDescendant($this->getLocator('selectContent'));

        return $this->getHTMLPage()->findAll($selectSelector)->any();
    }

    public function getFieldTypeIdentifier(): string
    {
        return 'ezobjectrelation';
    }
}
