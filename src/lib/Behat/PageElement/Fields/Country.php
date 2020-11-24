<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\EzPlatformAdminUi\Behat\PageElement\Fields;

use EzSystems\Behat\Browser\Selector\CSSSelector;
use PHPUnit\Framework\Assert;

class Country extends FieldTypeComponent
{
    public function setValue(array $parameters): void
    {
        $this->getHTMLPage()->find($this->getSelector('dropdownSelector'))->click();
        Assert::assertTrue($this->getHTMLPage()->find($this->getSelector('dropdownExpanded'))->isVisible());
        $this->getHTMLPage()->findAll($this->getSelector('dropdownItem'))->getByText($parameters['value'])->click();
        $this->getHTMLPage()->find($this->getSelector('dropdownSelector'))->click();
    }

    public function getFieldTypeIdentifier(): string
    {
        return 'ezcountry';
    }

    public function specifySelectors(): array
    {
        return [
            new CSSSelector('fieldInput', 'select'),
            new CSSSelector('dropdownSelector', '.ez-custom-dropdown__selection-info'),
            new CSSSelector('dropdownExpanded', '.ez-custom-dropdown__selection-info:not(.ez-custom-dropdown__items--hidden)'),
            new CSSSelector('dropdownItem', '.ez-custom-dropdown__item'),
        ];
    }
}
