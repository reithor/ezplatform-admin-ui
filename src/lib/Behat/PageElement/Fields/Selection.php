<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\EzPlatformAdminUi\Behat\PageElement\Fields;

use EzSystems\Behat\Browser\Selector\CSSSelector;

class Selection extends FieldTypeComponent
{
    public function setValue(array $parameters): void
    {
        $value = $parameters['value'];

        $fieldSelector = CSSSelector::combine("%s %s", $this->parentSelector, $this->getSelector('selectBar'));
        $this->getHTMLPage()->find($fieldSelector)->click();
        $this->getHTMLPage()->findAll($this->getSelector('selectOption'))->getByText($value)->click();
    }

    public function getValue(): array
    {
        $fieldSelector = CSSSelector::combine("%s %s", $this->parentSelector, $this->getSelector('selectBar'));


        return [$this->getHTMLPage()->find($fieldSelector)->getValue()];
    }

    public function getFieldTypeIdentifier(): string
    {
        return 'ezselection';
    }

    public function specifySelectors(): array
    {
        return [
            new CSSSelector('selectBar', '.ez-custom-dropdown__selection-info'),
            new CSSSelector('selectOption', '.ez-custom-dropdown__item'),
            new CSSSelector('specificOption', '.ez-custom-dropdown__item:nth-child(%s)'),
        ];
    }
}
