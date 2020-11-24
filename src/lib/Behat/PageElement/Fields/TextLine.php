<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\EzPlatformAdminUi\Behat\PageElement\Fields;

use EzSystems\Behat\Browser\Selector\CSSSelector;

class TextLine extends FieldTypeComponent
{
    public function setValue(array $parameters): void
    {
        $fieldSelector = CSSSelector::combine("%s %s", $this->parentSelector, $this->getSelector('fieldInput'));

        $value = array_values($parameters)[0];
        $this->getHTMLPage()->find($fieldSelector)->setValue($value);
    }

    public function specifySelectors(): array
    {
        return [
                new CSSSelector('fieldInput', 'input'),
            ];
    }

    public function getFieldTypeIdentifier(): string
    {
        return 'ezstring';
    }
}
