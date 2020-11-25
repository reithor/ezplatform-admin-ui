<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\EzPlatformAdminUi\Behat\PageElement\Fields;

use EzSystems\Behat\Browser\Locator\VisibleCSSLocator;
use PHPUnit\Framework\Assert;

class Checkbox extends FieldTypeComponent
{
    public function setValue(array $parameters): void
    {
        $fieldSelector = $this->parentLocator->withDescendant($this->getLocator('fieldInput'));

        if ($this->getValue() !== $parameters['value']) {
            $this->getHTMLPage()->find($fieldSelector)->click();
        }
    }

    public function getValue(): array
    {
        $fieldSelector = $this->parentLocator->withDescendant($this->getLocator('fieldInput'));

        return [
            $this->getHTMLPage()->find($fieldSelector)->hasClass($this->getLocator('checked')->getSelector())
        ];
    }

    public function verifyValueInItemView(array $values): void
    {
        Assert::assertEquals(
            'Yes',
            $this->getHTMLPage()->find($this->parentLocator)->getText(),
            'Field has wrong value'
        );
    }

    public function getFieldTypeIdentifier(): string
    {
        return 'ezboolean';
    }

    public function specifyLocators(): array
    {
        return [
            new VisibleCSSLocator('fieldInput', '.ez-data-source__indicator'),
            new VisibleCSSLocator('checkbox', '.ez-data-source__label'),
            new VisibleCSSLocator('checked', '.is-checked'),
        ];
    }
}
