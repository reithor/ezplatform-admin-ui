<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\EzPlatformAdminUi\Behat\PageElement\Fields;

use EzSystems\Behat\Browser\Selector\CSSSelector;
use PHPUnit\Framework\Assert;

class Checkbox extends FieldTypeComponent
{
    public function setValue(array $parameters): void
    {
        $fieldSelector = CSSSelector::combine("%s %s", $this->parentSelector, $this->getSelector('fieldInput'));

        if ($this->getValue() !== $parameters['value']) {
            $this->getHTMLPage()->find($fieldSelector)->click();
        }
    }

    public function getValue(): array
    {
        $fieldSelector = CSSSelector::combine("%s %s", $this->parentSelector, $this->getSelector('fieldInput'));

        return [
            $this->getHTMLPage()->find($fieldSelector)->hasClass($this->getSelector('checked')->getSelector())
        ];
    }

    public function verifyValueInItemView(array $values): void
    {
        Assert::assertEquals(
            'Yes',
            $this->getHTMLPage()->find($this->parentSelector)->getText(),
            'Field has wrong value'
        );
    }

    public function getFieldTypeIdentifier(): string
    {
        return 'ezboolean';
    }

    public function specifySelectors(): array
    {
        return [
            new CSSSelector('fieldInput', '.ez-data-source__indicator'),
            new CSSSelector('checkbox', '.ez-data-source__label'),
            new CSSSelector('checked', '.is-checked'),
        ];
    }
}
