<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\EzPlatformAdminUi\Behat\PageElement\Fields;

use EzSystems\Behat\Browser\Locator\CSSLocator;
use PHPUnit\Framework\Assert;

class Country extends FieldTypeComponent
{
    public function setValue(array $parameters): void
    {
        $this->getHTMLPage()->find($this->getLocator('dropdownSelector'))->click();
        Assert::assertTrue($this->getHTMLPage()->find($this->getLocator('dropdownExpanded'))->isVisible());
        $this->getHTMLPage()->findAll($this->getLocator('dropdownItem'))->getByText($parameters['value'])->click();
        $this->getHTMLPage()->find($this->getLocator('dropdownSelector'))->click();
    }

    public function getFieldTypeIdentifier(): string
    {
        return 'ezcountry';
    }

    public function specifyLocators(): array
    {
        return [
            new CSSLocator('fieldInput', 'select'),
            new CSSLocator('dropdownSelector', '.ez-custom-dropdown__selection-info'),
            new CSSLocator('dropdownExpanded', '.ez-custom-dropdown__selection-info:not(.ez-custom-dropdown__items--hidden)'),
            new CSSLocator('dropdownItem', '.ez-custom-dropdown__item'),
        ];
    }
}
