<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\EzPlatformAdminUi\Behat\PageElement\Fields;

use EzSystems\Behat\Browser\Locator\CSSLocator;

class Selection extends FieldTypeComponent
{
    public function setValue(array $parameters): void
    {
        $value = $parameters['value'];

        $fieldSelector = $this->parentSelector->withDescendant($this->getLocator('selectBar'));
        $this->getHTMLPage()->find($fieldSelector)->click();
        $this->getHTMLPage()->findAll($this->getLocator('selectOption'))->getByText($value)->click();
    }

    public function getValue(): array
    {
        $fieldSelector = $this->parentSelector->withDescendant($this->getLocator('selectBar'));


        return [$this->getHTMLPage()->find($fieldSelector)->getValue()];
    }

    public function getFieldTypeIdentifier(): string
    {
        return 'ezselection';
    }

    public function specifyLocators(): array
    {
        return [
            new CSSLocator('selectBar', '.ez-custom-dropdown__selection-info'),
            new CSSLocator('selectOption', '.ez-custom-dropdown__item'),
            new CSSLocator('specificOption', '.ez-custom-dropdown__item:nth-child(%s)'),
        ];
    }
}
