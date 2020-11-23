<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\EzPlatformAdminUi\Behat\PageElement\Fields;

use EzSystems\Behat\Browser\Context\OldBrowserContext;
use EzSystems\Behat\Browser\Selector\CSSSelector;
use PHPUnit\Framework\Assert;

class TextLine extends FieldTypeComponent
{
    public function setValue(array $parameters): void
    {
        $fieldInputSelector = CSSSelector::combine("%s %s", $this->parentSelector, $this->getSelector('fieldInput'));

        $value = array_values($parameters)[0];
        $this->getHTMLPage()->find($fieldInputSelector)->setValue($value);
    }

    public function getValue(): array
    {
        $fieldInputSelector = CSSSelector::combine("%s %s", $this->parentSelector, $this->getSelector('fieldInput'));
        $value = $this->getHTMLPage()->find($fieldInputSelector)->getValue();

        return [$value];
    }

    public function verifyValueInItemView(array $values): void
    {
        Assert::assertEquals(
            $values['value'],
            $this->getHTMLPage()->find($this->getSelector('fieldContainer'))->getText(),
            'Field has wrong value'
        );
    }

    public function specifySelectors(): array
    {
        return [
                new CSSSelector('fieldInput', 'input'),
            ];
    }

    function getFieldTypeIdentifier(): string
    {
        return 'ezstring';
    }
}
